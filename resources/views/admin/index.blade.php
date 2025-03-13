@extends('admin.layouts.app')
@section('title')
    Admin Dashboard
@endsection
<style>
    rect {
        width: 60px !important;
    }

    svg {
        width: 100%;
    }

    #morris-bar-attempts {
        margin-top: 0px !important;
    }

    #morris-bar-time {
        margin-top: 0px !important;
    }
</style>

@section('content')
    <div class="pagetitle d-flex justify-content-between">
        <h1>Dashboard</h1>

    </div>



    <section class="section dashboard">
        <div class="row">

            <!-- Left side cReportsolumns -->
            <div class="">


                <div class="panel panel-border panel-primary">
                    <div class="panel-body">
                        <div class="class d-flex justify-content-between align-items-center mt-3 mb-3">
                            <h3>User Statistics</h3>
                            <div class="pagetitle d-flex gap-3 align-items-center">
                                <h1>Today Date</h1>
                                <p class="mb-0">{{ \Carbon\Carbon::today()->format('F d, Y') }}</p>
                            </div>
                            <input type="date" class="form-control" id="date" style="width: 25%">
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Three Word Letter</th>
                                    <th scope="col">Five Word Letter</th>
                                    <th scope="col">Seven Word Letter</th>
                                    <th scope="col">Theme</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $threeLetterWord->letter ?? 'N/A' }}</td>
                                    <td>{{ $fiveLetterWord->letter ?? 'N/A' }}</td>
                                    <td>{{ $sevenLetterWord->letter ?? 'N/A' }}</td>
                                    <td>
                                        {{ $threeLetterWord->theme_name ?? ($fiveLetterWord->theme_name ?? ($sevenLetterWord->theme_name ?? 'N/A')) }}
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="card text-center mt-3 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Game Statistics</h5>
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold mb-0">Total Games: <span
                                            class="badge bg-primary">{!! json_encode($totalGames) !!}</span></p>
                                    <p class="fw-bold mb-0">Wins: <span
                                            class="badge bg-success">{!! json_encode($wins) !!}</span></p>
                                    <p class="fw-bold mb-0">Losses: <span
                                            class="badge bg-danger">{!! json_encode($losses) !!}</span></p>
                                </div>
                            </div>
                        </div>




                        <div id="chartdivone" style="width: 100%; height: 500px;"></div>

                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="pagetitle">
                                    <h1>Average Attempts Won</h1>

                                </div>
                                <div id="morris-bar-attempts" class="mt-5" style="height: 300px;"></div>
                                <p id="current-date" style="text-align: center; margin-top: 10px; font-weight: bold;"></p>
                            </div>

                            <div class="col-md-6">
                                <div class="pagetitle">
                                    <h1>Average Attempts Duration</h1>

                                </div>
                                <div id="morris-bar-time" class="mt-5" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>



                </div>
            </div><!-- End Left side columns -->



        </div>
        <div>

            <div class="pagetitle mt-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h2 class="mb-0">Today Words Difficulty Levels</h2>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <h5 class="fw-bold">3-Letter Word</h5>
                                    <span class="badge bg-primary fs-5 p-2">{{ $threeLetterDifficulty }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <h5 class="fw-bold">5-Letter Word</h5>
                                    <span class="badge bg-success fs-5 p-2">{{ $fiveLetterDifficulty }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <h5 class="fw-bold">7-Letter Word</h5>
                                    <span class="badge bg-warning fs-5 p-2 text-dark">{{ $sevenLetterDifficulty }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </div>
        {{-- <div id="cha</div>rtdivtwo"></div> --}}
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Handle date change event
            $('#date').on('change', function() {
                let selectedDate = $(this).val();
                window.location.href = "{{ route('super_admin.index') }}?date=" + selectedDate;
            });

            // Set the selected date in the input field
            let selectedDate = "{{ $selectedDate }}";
            $('#date').val(selectedDate);
        });
    </script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <!-- Chart code -->


    <!-- Chart code -->
    <script>
        am5.ready(function() {
            // Get winRate and lossRate from Laravel
            let winRate = {!! json_encode($winRate) !!};
            let lossRate = {!! json_encode($lossRate) !!};

            // Create the chart
            var root = am5.Root.new("chartdivone");

            root.setThemes([am5themes_Animated.new(root)]);

            var chart = root.container.children.push(
                am5percent.PieChart.new(root, {
                    endAngle: 270,
                    layout: root.verticalLayout,
                    innerRadius: am5.percent(50)
                })
            );

            var series = chart.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    endAngle: 270
                })
            );

            series.set("colors", am5.ColorSet.new(root, {
                colors: [
                    am5.color(0x28a745), // Green for win rate
                    am5.color(0xdc3545) // Red for loss count
                ]
            }));

            // Pass dynamic values from Laravel
            series.data.setAll([{
                    category: "Win Rate (%)",
                    value: winRate
                },
                {
                    category: "Loss Rate (%)",
                    value: lossRate
                }
            ]);

            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50),
                x: am5.percent(50),
                marginTop: 15,
                marginBottom: 15,
            }));

            legend.data.setAll(series.dataItems);

            series.appear(1000, 100);

            // Check if both winRate and lossRate are 0
            if (winRate === 0 && lossRate === 0) {
                // Display "No records found" message below the chart
                let noRecordsMessage = document.createElement('p');
                noRecordsMessage.className = 'text-center';
                noRecordsMessage.style.marginTop = '20px';
                noRecordsMessage.style.color = '#dc3545'; // Red color for emphasis
                noRecordsMessage.textContent = 'No records found for the selected date.';

                // Append the message below the chart container
                document.getElementById('chartdivone').appendChild(noRecordsMessage);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            let data = [{
                    label: '3-Letter',
                    value: {{ $averageAttempts['threeLetter'] }}
                },
                {
                    label: '5-Letter',
                    value: {{ $averageAttempts['fiveLetter'] }}
                },
                {
                    label: '7-Letter',
                    value: {{ $averageAttempts['sevenLetter'] }}
                }
            ];

            // Morris.js Bar Chart
            new Morris.Bar({
                element: 'morris-bar-example',
                data: data,
                xkey: 'label',
                ykeys: ['value'],
                labels: ['Count'],
                barColors: ['#007bff', '#28a745', '#ffcc00', '#dc3545'],
                resize: true,
                barSizeRatio: 0.5
            });

            let today = new Date();
            let formattedDate = today.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            // $('#current-date').text("Date: " + formattedDate);
        });
    </script>


    <script>
        $(document).ready(function() {
            let attemptData = [{
                    label: '3-Letter',
                    value: {{ $averageAttempts['threeLetter'] }}
                },
                {
                    label: '5-Letter',
                    value: {{ $averageAttempts['fiveLetter'] }}
                },
                {
                    label: '7-Letter',
                    value: {{ $averageAttempts['sevenLetter'] }}
                }
            ];

            let timeData = [{
                    label: '3-Letter',
                    value: {{ $averageTimeTaken['threeLetter'] }}
                },
                {
                    label: '5-Letter',
                    value: {{ $averageTimeTaken['fiveLetter'] }}
                },
                {
                    label: '7-Letter',
                    value: {{ $averageTimeTaken['sevenLetter'] }}
                }
            ];

            // Morris.js Bar Chart for Average Attempts
            new Morris.Bar({
                element: 'morris-bar-attempts',
                data: attemptData,
                xkey: 'label',
                ykeys: ['value'],
                labels: ['Count'],
                barColors: ['#007bff', '#28a745', '#ffcc00', '#dc3545'],
                resize: true,
                barSizeRatio: 0.5
            });

            // Morris.js Bar Chart for Average Time Taken
            new Morris.Bar({
                element: 'morris-bar-time',
                data: timeData,
                xkey: 'label',
                ykeys: ['value'],
                labels: ['Seconds'],
                barColors: ['#17a2b8', '#fd7e14', '#6610f2'],
                resize: true,
                barSizeRatio: 0.5
            });

            let today = new Date();
            let formattedDate = today.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            // $('#current-date').text("Date: " + formattedDate);
        });
    </script>
@endsection
