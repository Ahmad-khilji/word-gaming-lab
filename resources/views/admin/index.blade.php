@extends('admin.layouts.app')
@section('title')
    Admin Dashboard | Solar Energy
@endsection


@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->



    <section class="section dashboard">
        <div class="row">

            <!-- Left side cReportsolumns -->
            <div class="col-lg-8">

                <div class="col-lg-8">
                    <div class="panel panel-border panel-primary">
                        <!-- Morris.js CSS -->

                        <div class="panel-body">
                          <h3>User Statistics</h3>
                          <div id="morris-bar-example" style="height: 300px;"></div>
                          <p id="current-date" style="text-align: center; margin-top: 10px; font-weight: bold;"></p>
                      </div>
                    </div>
                </div>



                <div id="chartdivv"></div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

              <div id="chartdiv"></div>

              <div id="chartdivvv"></div>
              
    </section>
@endsection

@section('scripts')

<script>
  $(document).ready(function() {
      let data = [
          { label: 'Total Users', value: {{ $totalUsers }} },
          { 
              label: 'Most Played: {{ $todayTopWord ? $todayTopWord->target_word : "N/A" }}', 
              value: {{ $todayTopWord ? $todayTopWord->count : 0 }} 
          }
      ];

      // Morris.js Bar Chart
      new Morris.Bar({
    element: 'morris-bar-example',
    data: data,
    xkey: 'label',
    ykeys: ['value'],
    labels: ['Count'],
    barColors: ['#007bff', '#28a745'],
    resize: true,
    barSizeRatio: 0.5 // Ye width adjust karega (Default 0.75 hota hai)
});
      let today = new Date();
      let formattedDate = today.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
      $('#current-date').text("Date: " + formattedDate);
  });
</script>
@endsection
<!-- Styles -->
<style>
  #chartdiv {
    width: 100%;
    height: 500px;
  }
  </style>
  
  <!-- Resources -->
  <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
  
  <!-- Chart code -->
  <script>
  am5.ready(function() {
  
  // Create root element
  // https://www.amcharts.com/docs/v5/getting-started/#Root_element
  var root = am5.Root.new("chartdiv");
  
  
  // Set themes
  // https://www.amcharts.com/docs/v5/concepts/themes/
  root.setThemes([
    am5themes_Animated.new(root)
  ]);
  
  
  // Create chart
  // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
  var chart = root.container.children.push(am5percent.PieChart.new(root, {
    layout: root.verticalLayout
  }));
  
  
  // Create series
  // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
  var series = chart.series.push(am5percent.PieSeries.new(root, {
    alignLabels: true,
    calculateAggregates: true,
    valueField: "value",
    categoryField: "category"
  }));
  
  series.slices.template.setAll({
    strokeWidth: 3,
    stroke: am5.color(0xffffff)
  });
  
  series.labelsContainer.set("paddingTop", 30)
  
  
  // Set up adapters for variable slice radius
  // https://www.amcharts.com/docs/v5/concepts/settings/adapters/
  series.slices.template.adapters.add("radius", function (radius, target) {
    var dataItem = target.dataItem;
    var high = series.getPrivate("valueHigh");
  
    if (dataItem) {
      var value = target.dataItem.get("valueWorking", 0);
      return radius * value / high
    }
    return radius;
  });
  
  
  // Set data
  // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
  series.data.setAll([{
    value: 10,
    category: "One"
  }, {
    value: 9,
    category: "Two"
  }, {
    value: 6,
    category: "Three"
  }, {
    value: 5,
    category: "Four"
  }, {
    value: 4,
    category: "Five"
  }, {
    value: 3,
    category: "Six"
  }]);
  
  
  // Create legend
  // https://www.amcharts.com/docs/v5/charts/percent-charts/legend-percent-series/
  var legend = chart.children.push(am5.Legend.new(root, {
    centerX: am5.p50,
    x: am5.p50,
    marginTop: 15,
    marginBottom: 15
  }));
  
  legend.data.setAll(series.dataItems);
  
  
  // Play initial series animation
  // https://www.amcharts.com/docs/v5/concepts/animations/#Animation_of_series
  series.appear(1000, 100);
  
  }); // end am5.ready()
  </script>


<!-- Styles -->
<style>
  #chartdiv {
    width: 100%;
    max-width:100%;
    height: 500px;
  }
  </style>

  
  <!-- Chart code -->
  <script>
  am5.ready(function() {
  
  // Create root element
  // https://www.amcharts.com/docs/v5/getting-started/#Root_element
  var root = am5.Root.new("chartdivv");
  
  // Set themes
  // https://www.amcharts.com/docs/v5/concepts/themes/
  root.setThemes([
    am5themes_Animated.new(root)
  ]);
  
  
  // Create chart
  // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
  var chart = root.container.children.push(
    am5percent.PieChart.new(root, {
      endAngle: 270,
      layout:root.verticalLayout,
      innerRadius: am5.percent(60)
    })
  );
  /*
  var bg = root.container.set("background", am5.Rectangle.new(root, {
    fillPattern: am5.GrainPattern.new(root, {
      density: 0.1,
      maxOpacity: 0.2
    })
  }))
  
  */
  
  // Create series
  // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
  var series = chart.series.push(
    am5percent.PieSeries.new(root, {
      valueField: "value",
      categoryField: "category",
      endAngle: 270
    })
  );
  
  series.set("colors", am5.ColorSet.new(root, {
    colors: [
      am5.color(0x73556E),
      am5.color(0x9FA1A6),
      am5.color(0xF2AA6B),
      am5.color(0xF28F6B),
      am5.color(0xA95A52),
      am5.color(0xE35B5D),
      am5.color(0xFFA446)
    ]
  }))
  
  var gradient = am5.RadialGradient.new(root, {
    stops: [
      { color: am5.color(0x000000) },
      { color: am5.color(0x000000) },
      {}
    ]
  })
  
  series.slices.template.setAll({
    fillGradient: gradient,
    strokeWidth: 2,
    stroke: am5.color(0xffffff),
    cornerRadius: 10,
    shadowOpacity: 0.1,
    shadowOffsetX: 2,
    shadowOffsetY: 2,
    shadowColor: am5.color(0x000000),
    fillPattern: am5.GrainPattern.new(root, {
      maxOpacity: 0.2,
      density: 0.5,
      colors: [am5.color(0x000000)]
    })
  })
  
  series.slices.template.states.create("hover", {
    shadowOpacity: 1,
    shadowBlur: 10
  })
  
  series.ticks.template.setAll({
     strokeOpacity:0.4,
  strokeDasharray:[2,2]
  })
  
  series.states.create("hidden", {
    endAngle: -90
  });
  
  // Set data
  // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
  series.data.setAll([{
    category: "Lithuania",
    value: 500
  }, {
    category: "Czechia",
    value: 300
  }, {
    category: "Ireland",
    value: 200
  }, {
    category: "Germany",
    value: 100
  }]);
  
  var legend = chart.children.push(am5.Legend.new(root, {
    centerX: am5.percent(50),
    x: am5.percent(50),
    marginTop: 15,
    marginBottom: 15,
  }));
  legend.markerRectangles.template.adapters.add("fillGradient", function() {
    return undefined;
  })
  legend.data.setAll(series.dataItems);
  
  series.appear(1000, 100);
  
  }); // end am5.ready()
  </script>
  
  <!-- HTML -->
 

  <!-- Styles -->
<style>
  #chartdiv {
    width: 100%;
    height: 500px;
  }
  </style>
  

  
  <!-- Chart code -->
  <script>
  am5.ready(function() {
  
  // Define data for each year
  var chartData = {
    "1995": [
      { sector: "Agriculture", size: 6.6 },
      { sector: "Mining and Quarrying", size: 0.6 },
      { sector: "Manufacturing", size: 23.2 },
      { sector: "Electricity and Water", size: 2.2 },
      { sector: "Construction", size: 4.5 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 14.6 },
      { sector: "Transport and Communication", size: 9.3 },
      { sector: "Finance, real estate and business services", size: 22.5 } ],
    "1996": [
      { sector: "Agriculture", size: 6.4 },
      { sector: "Mining and Quarrying", size: 0.5 },
      { sector: "Manufacturing", size: 22.4 },
      { sector: "Electricity and Water", size: 2 },
      { sector: "Construction", size: 4.2 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 14.8 },
      { sector: "Transport and Communication", size: 9.7 },
      { sector: "Finance, real estate and business services", size: 22 } ],
    "1997": [
      { sector: "Agriculture", size: 6.1 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 20.9 },
      { sector: "Electricity and Water", size: 1.8 },
      { sector: "Construction", size: 4.2 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 13.7 },
      { sector: "Transport and Communication", size: 9.4 },
      { sector: "Finance, real estate and business services", size: 22.1 } ],
    "1998": [
      { sector: "Agriculture", size: 6.2 },
      { sector: "Mining and Quarrying", size: 0.3 },
      { sector: "Manufacturing", size: 21.4 },
      { sector: "Electricity and Water", size: 1.9 },
      { sector: "Construction", size: 4.2 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 14.5 },
      { sector: "Transport and Communication", size: 10.6 },
      { sector: "Finance, real estate and business services", size: 23 } ],
    "1999": [
      { sector: "Agriculture", size: 5.7 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 20 },
      { sector: "Electricity and Water", size: 1.8 },
      { sector: "Construction", size: 4.4 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 15.2 },
      { sector: "Transport and Communication", size: 10.5 },
      { sector: "Finance, real estate and business services", size: 24.7 } ],
    "2000": [
      { sector: "Agriculture", size: 5.1 },
      { sector: "Mining and Quarrying", size: 0.3 },
      { sector: "Manufacturing", size: 20.4 },
      { sector: "Electricity and Water", size: 1.7 },
      { sector: "Construction", size: 4 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 16.3 },
      { sector: "Transport and Communication", size: 10.7 },
      { sector: "Finance, real estate and business services", size: 24.6 } ],
    "2001": [
      { sector: "Agriculture", size: 5.5 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 20.3 },
      { sector: "Electricity and Water", size: 1.6 },
      { sector: "Construction", size: 3.1 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 16.3 },
      { sector: "Transport and Communication", size: 10.7 },
      { sector: "Finance, real estate and business services", size: 25.8 } ],
    "2002": [
      { sector: "Agriculture", size: 5.7 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 20.5 },
      { sector: "Electricity and Water", size: 1.6 },
      { sector: "Construction", size: 3.6 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 16.1 },
      { sector: "Transport and Communication", size: 10.7 },
      { sector: "Finance, real estate and business services", size: 26 } ],
    "2003": [
      { sector: "Agriculture", size: 4.9 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 19.4 },
      { sector: "Electricity and Water", size: 1.5 },
      { sector: "Construction", size: 3.3 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 16.2 },
      { sector: "Transport and Communication", size: 11 },
      { sector: "Finance, real estate and business services", size: 27.5 } ],
    "2004": [
      { sector: "Agriculture", size: 4.7 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 18.4 },
      { sector: "Electricity and Water", size: 1.4 },
      { sector: "Construction", size: 3.3 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 16.9 },
      { sector: "Transport and Communication", size: 10.6 },
      { sector: "Finance, real estate and business services", size: 28.1 } ],
    "2005": [
      { sector: "Agriculture", size: 4.3 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 18.1 },
      { sector: "Electricity and Water", size: 1.4 },
      { sector: "Construction", size: 3.9 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 15.7 },
      { sector: "Transport and Communication", size: 10.6 },
      { sector: "Finance, real estate and business services", size: 29.1 } ],
    "2006": [
      { sector: "Agriculture", size: 4 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 16.5 },
      { sector: "Electricity and Water", size: 1.3 },
      { sector: "Construction", size: 3.7 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 14.2 },
      { sector: "Transport and Communication", size: 12.1 },
      { sector: "Finance, real estate and business services", size: 29.1 } ],
    "2007": [
      { sector: "Agriculture", size: 4.7 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 16.2 },
      { sector: "Electricity and Water", size: 1.2 },
      { sector: "Construction", size: 4.1 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 15.6 },
      { sector: "Transport and Communication", size: 11.2 },
      { sector: "Finance, real estate and business services", size: 30.4 } ],
    "2008": [
      { sector: "Agriculture", size: 4.9 },
      { sector: "Mining and Quarrying", size: 0.3 },
      { sector: "Manufacturing", size: 17.2 },
      { sector: "Electricity and Water", size: 1.4 },
      { sector: "Construction", size: 5.1 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 15.4 },
      { sector: "Transport and Communication", size: 11.1 },
      { sector: "Finance, real estate and business services", size: 28.4 } ],
    "2009": [
      { sector: "Agriculture", size: 4.7 },
      { sector: "Mining and Quarrying", size: 0.3 },
      { sector: "Manufacturing", size: 16.4 },
      { sector: "Electricity and Water", size: 1.9 },
      { sector: "Construction", size: 4.9 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 15.5 },
      { sector: "Transport and Communication", size: 10.9 },
      { sector: "Finance, real estate and business services", size: 27.9 } ],
    "2010": [
      { sector: "Agriculture", size: 4.2 },
      { sector: "Mining and Quarrying", size: 0.3 },
      { sector: "Manufacturing", size: 16.2 },
      { sector: "Electricity and Water", size: 2.2 },
      { sector: "Construction", size: 4.3 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 15.7 },
      { sector: "Transport and Communication", size: 10.2 },
      { sector: "Finance, real estate and business services", size: 28.8 } ],
    "2011": [
      { sector: "Agriculture", size: 4.1 },
      { sector: "Mining and Quarrying", size: 0.3 },
      { sector: "Manufacturing", size: 14.9 },
      { sector: "Electricity and Water", size: 2.3 },
      { sector: "Construction", size: 5 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 17.3 },
      { sector: "Transport and Communication", size: 10.2 },
      { sector: "Finance, real estate and business services", size: 27.2 } ],
    "2012": [
      { sector: "Agriculture", size: 3.8 },
      { sector: "Mining and Quarrying", size: 0.3 },
      { sector: "Manufacturing", size: 14.9 },
      { sector: "Electricity and Water", size: 2.6 },
      { sector: "Construction", size: 5.1 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 15.8 },
      { sector: "Transport and Communication", size: 10.7 },
      { sector: "Finance, real estate and business services", size: 28 } ],
    "2013": [
      { sector: "Agriculture", size: 3.7 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 14.9 },
      { sector: "Electricity and Water", size: 2.7 },
      { sector: "Construction", size: 5.7 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 16.5 },
      { sector: "Transport and Communication", size: 10.5 },
      { sector: "Finance, real estate and business services", size: 26.6 } ],
    "2014": [
      { sector: "Agriculture", size: 3.9 },
      { sector: "Mining and Quarrying", size: 0.2 },
      { sector: "Manufacturing", size: 14.5 },
      { sector: "Electricity and Water", size: 2.7 },
      { sector: "Construction", size: 5.6 },
      { sector: "Trade (Wholesale, Retail, Motor)", size: 16.6 },
      { sector: "Transport and Communication", size: 10.5 },
      { sector: "Finance, real estate and business services", size: 26.5 } ]
  };
  
  var root = am5.Root.new("chartdivvv");
  
  root.setThemes([
    am5themes_Animated.new(root)
  ]);
  
  var chart = root.container.children.push(am5percent.PieChart.new(root, {
    innerRadius: 100,
    layout: root.verticalLayout
  }));
  
  
  var series = chart.series.push(am5percent.PieSeries.new(root, {
    valueField: "size",
    categoryField: "sector"
  }));
  
  series.data.setAll([
    { sector: "Agriculture", size: 6.6 },
    { sector: "Mining and Quarrying", size: 0.6 },
    { sector: "Manufacturing", size: 23.2 },
    { sector: "Electricity and Water", size: 2.2 },
    { sector: "Construction", size: 4.5 },
    { sector: "Trade (Wholesale, Retail, Motor)", size: 14.6 },
    { sector: "Transport and Communication", size: 9.3 },
    { sector: "Finance, real estate and business services", size: 22.5 }
  ]);
  
  

  series.appear(1000, 100);
  
  
  // Add label
  var label = root.tooltipContainer.children.push(am5.Label.new(root, {
    x: am5.p50,
    y: am5.p50,
    centerX: am5.p50,
    centerY: am5.p50,
    fill: am5.color(0x000000),
    fontSize: 50
  }));
  
  
  // Animate chart data
  var currentYear = 1995;
  function getCurrentData() {
    var data = chartData[currentYear];
    currentYear++;
    if (currentYear > 2014)
      currentYear = 1995;
    return data;
  }
  
  function loop() {
    label.set("text", currentYear);
    var data = getCurrentData();
    for(var i = 0; i < data.length; i++) {
      series.data.setIndex(i, data[i]);
    }
    chart.setTimeout( loop, 4000 );
  }
  
  loop();
  
  }); // end am5.ready()
  </script>
  
  <!-- HTML -->
