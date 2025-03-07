@extends('admin.layouts.app')
@section('title')
Admin Dashboard | Solar Energy
@endsection
@section('content')

<div class="pagetitle">
    <h1>Three Word Game</h1>
    {{-- <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('super_admin.index')}}">Home</a></li>
            <li class="breadcrumb-item active">Three Word Game</li>
        </ol>
    </nav> --}}
</div><!-- End Page Title -->

<!-- create new category modal -->
<div class="modal fade" id="createNewCategory" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleCreate">Create New Three Word</h5>
                <h5 style="display: none;" class="modal-title" id="modalTitleupdate">Edit Three Word</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Vertical Form -->
                <form class="row g-3">
                   
                    <div class="col-6">
                        <label for="letter" class="form-label">Word</label>
                        <input type="text" class="form-control" id="letter">
                    </div>
                    <div class="col-6">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date">
                    </div>


                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="theme" name="theme" aria-label="Category">
                                <option value="">Theme</option>
                                <option value="1">Speak</option>
                                <option value="2">Spear</option>
                                <option value="3">Table</option>
                                <option value="4">Check</option>
                                <option value="5">Chump</option>
                            </select>
                            
                        </div>
                      </div>

                </form>
                <!-- Vertical Form -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="savethreeWord()" id="saveCategoryLoader">Save Three Word</button>
                <button style="display: none;" type="button" class="btn btn-primary" onclick="updatethreeWord()" id="updateCategoryLoader">Update hree Word</button>
            </div>
        </div>
    </div>
</div>
<!-- create new category modal -->

<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Three Word</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Vertical Form -->
                <form class="row g-3">
                    
                    <div class="col-6">
                        <label for="letter_edit" class="form-label">Word</label>
                        <input type="text" class="form-control" id="letter_edit">
                    </div>

                    <div class="col-6">
                        <label for="date_edit" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date_edit">
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select class="form-select" name="theme" id="theme_edit" aria-label="Category">
                                <option value="">Theme</option>
                                <option value="1">Speak</option>
                                <option value="2">Spear</option>
                                <option value="3">Table</option>
                                <option value="4">Check</option>
                                <option value="5">Chump</option>
                            </select>
                            
                            
                        </div>
                    </div>

                </form><!-- Vertical Form -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updatethreeWord()" id="updateCategoryLoader">Update Three Word</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCategory" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete Three Word</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="category_del_title"></p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" onclick="deletethreeWord()" id="deleteCategoryLoader">Yes</button>
            </div>
        </div>
    </div>
</div>


<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            {{-- <h5 class="card-title">Company Three Word</h5> --}}
                        </div>
                        <div class="mt-3 mb-3">
                            <button data-bs-toggle="modal" data-bs-target="#createNewCategory" type="button" name="button" class="btn btn-primary">Create New Three Word</button>
                        </div>
                    </div>
                    <!-- Table with stripped rows -->
                    <table id="category-table" class="table">
                        <thead>
                            <tr>
                              
                                <th>Word</th>
                                <th>Date</th>
                                <th>Theme</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTables -->
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    let table;
    $(document).ready(function() {
    console.log('Initializing DataTables...');
    table = $('#category-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("super_admin.threeword.index") }}',
            type: 'GET',
            dataSrc: function(response) {
                console.log('Server response:', response); // Debug server response
                return response.data; // Ensure the response has a `data` property
            }
        },
        columns: [
            { data: 'letter', name: 'letter', orderable: false },
            { data: 'date', name: 'date', orderable: false },
            { data: 'theme', name: 'theme', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

    function savethreeWord() {
        // Get form data
        $('#saveCategoryLoader').addClass('loading');
        const letter = $('#letter').val();
        const date = $('#date').val();
        const theme = $('#theme').val();

        // Prepare FormData
        let formData = new FormData();
        formData.append('letter', letter);
        formData.append('date', date);
        formData.append('theme', theme);
        let url = "{{ route('super_admin.threeword.store') }}";

        // AJAX Request
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false, // Required for FormData
            processData: false, // Prevent jQuery from processing the data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#saveCategoryLoader').removeClass('loading');
                if (response.status) {
                    // Success response
                    $('#letter').val('');
                    $('#date').val('');
                    $('#theme').val('');
                    $('#createNewCategory').modal('hide'); // Hide modal
                    toastifySuccess(response.message); // Display success message
                    table.draw(); // Reload data table
                } else {
                    toastifyError(response.message || 'Something went wrong.');
                }
            },
            error: function(request) {
                $('#saveCategoryLoader').removeClass('loading');
                let errorResponse = JSON.parse(request.responseText);

                if (errorResponse.errors == null) {
                    toastifyError(errorResponse.message);
                } else {
                    let error_list = '<ul>';
                    $.each(errorResponse.errors, function(field_name, error) {
                        error_list += '<li>' + error + '</li>';
                    });
                    error_list += '</ul>';
                    toastifyError(error_list);
                }
            },
        });
    }

    function editModal(id, letter,date, theme) {
        $('#updateCategoryLoader').attr('data-id', id);
        $('#letter_edit').val(letter);
        $('#date_edit').val(date);
        $('#theme_edit').val(theme).change(); 
        $('#editCategoryModal').modal('show');
    }

    function updatethreeWord() {
        // Get form data
        $('#updateCategoryLoader').addClass('loading');
        const letter = $('#letter_edit').val();
        const date = $('#date_edit').val();
        const theme = $('#theme_edit').val();


        // Prepare FormData
        let formData = new FormData();
        formData.append('letter', letter);
        formData.append('date', date);
        formData.append('theme', theme);
        let id = $('#updateCategoryLoader').attr("data-id");
        let routeTemplate = "{{ route('super_admin.threeword.update', ['id' => ':id']) }}";
        let url = routeTemplate.replace(':id', id);
        // AJAX Request
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false, // Required for FormData
            processData: false, // Prevent jQuery from processing the data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#updateCategoryLoader').removeClass('loading');
                if (response.status) {
                    // Success response
                    $('#editCategoryModal').modal('hide'); // Hide modal
                    toastifySuccess(response.message); // Display success message
                    table.draw(); // Reload data table
                } else {
                    toastifyError(response.message || 'Something went wrong.');
                }
            },
            error: function(request) {
                $('#updateCategoryLoader').removeClass('loading');
                let errorResponse = JSON.parse(request.responseText);

                if (errorResponse.errors == null) {
                    toastifyError(errorResponse.message);
                } else {
                    let error_list = '<ul>';
                    $.each(errorResponse.errors, function(field_name, error) {
                        error_list += '<li>' + error + '</li>';
                    });
                    error_list += '</ul>';
                    toastifyError(error_list);
                }
            },
        });
    }

    function deleteModal(id, name) {
        $('#deleteCategoryLoader').attr('data-id', id);
        $('#category_del_title').text('Are You Sure you want to delete this Three Word?' + ' ' + name);
        $('#deleteCategory').modal('show');
    }

    function deletethreeWord() {
        $('#deleteCategoryLoader').addClass('loading');
        let dataId = $('#deleteCategoryLoader').attr("data-id");
        let url = "{{ route('super_admin.threeword.delete') }}";
        $.ajax({
            type: "post",
            url: url,
            data: {
                id: dataId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#deleteCategoryLoader').removeClass('loading');
                console.log(response);
                if (response.status == true) {
                    // todo toastr
                    toastifySuccess(response.message);
                    $('#deleteCategory').modal('hide');
                    table.draw();
                }
            },
            error: function(request) {
                $('#deleteCategoryLoader').removeClass('loading');
                let errorResponse = JSON.parse(request.responseText);

                if (errorResponse.errors == null) {
                    toastifyError(errorResponse.message);
                } else {
                    let error_list = '<ul>';
                    $.each(errorResponse.errors, function(field_name, error) {
                        error_list += '<li>' + error + '</li>';
                    });
                    error_list += '</ul>';
                    toastifyError(error_list);
                }
            },
        });



    }
</script>
@endsection