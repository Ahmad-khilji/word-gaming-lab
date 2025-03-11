@extends('admin.layouts.app')
@section('title')
    Admin Dashboard
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Three Word Game</h1>
    </div>

    <!-- create new category modal -->
    <div class="modal fade" id="createNewCategory" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleCreate">Create New Theme</h5>
                    <h5 style="display: none;" class="modal-title" id="modalTitleupdate">Edit Theme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Vertical Form -->
                    <form class="row g-3">

                        <div class="col-6">
                            <label for="theme_name" class="form-label">Theme Name</label>
                            <input type="text" class="form-control" id="theme_name">
                        </div>
                        <div class="col-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date">
                        </div>
                        <div class="col-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date">
                        </div>

                    </form>
                    <!-- Vertical Form -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveTheme()" id="saveCategoryLoader">Save
                       Theme</button>
                    <button style="display: none;" type="button" class="btn btn-primary" onclick="updateTheme()"
                        id="updateCategoryLoader">Update Theme</button>
                </div>
            </div>
        </div>
    </div>
    <!-- create new category modal -->

    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Theme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Vertical Form -->
                    <form class="row g-3">

                        <div class="col-6">
                            <label for="theme_name_edit" class="form-label">Theme Name</label>
                            <input type="text" class="form-control" id="theme_name_edit">
                        </div>

                        <div class="col-6">
                            <label for="start_date_edit" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date_edit">
                        </div>
                        <div class="col-6">
                            <label for="end_date_edit" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date_edit">
                        </div>

                    </form><!-- Vertical Form -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateTheme()"
                        id="updateCategoryLoader">Update Theme</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCategory" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete Theme</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="category_del_title"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" onclick="deleteTheme()"
                        id="deleteCategoryLoader">Yes</button>
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
                                <button data-bs-toggle="modal" data-bs-target="#createNewCategory" type="button"
                                    name="button" class="btn btn-primary">Create New Theme</button>
                            </div>
                        </div>
                        <!-- Table with stripped rows -->
                        <table id="category-table" class="table">
                            <thead>
                                <tr>

                                    <th>Theme Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th class="text-center">Action</th>
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
                    url: '{{ route('super_admin.theme.index') }}',
                    type: 'GET',
                    dataSrc: function(response) {
                        console.log('Server response:', response); // Debug server response
                        return response.data; // Ensure the response has a `data` property
                    }
                },
                columns: [{
                        data: 'theme_name',
                        name: 'theme_name',
                        orderable: false
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        orderable: false
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        orderable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: "text-center" 

                    }
                ]
            });
        });

        function saveTheme() {
            // Get form data
            $('#saveCategoryLoader').addClass('loading');
            const theme_name = $('#theme_name').val();
            const start_date = $('#start_date').val();
            const end_date = $('#end_date').val();

            // Prepare FormData
            let formData = new FormData();
            formData.append('theme_name', theme_name);
            formData.append('start_date', start_date);
            formData.append('end_date', end_date);
            let url = "{{ route('super_admin.theme.store') }}";

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
                        $('#theme_name').val('');
                        $('#start_date').val('');
                        $('#end_date').val('');
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

        function editModal(id, theme_name, start_date, end_date) {
            $('#updateCategoryLoader').attr('data-id', id);
            $('#theme_name_edit').val(theme_name);
            $('#start_date_edit').val(start_date);
            $('#end_date_edit').val(end_date).change();
            $('#editCategoryModal').modal('show');
        }

        function updateTheme() {
            // Get form data
            $('#updateCategoryLoader').addClass('loading');
            const theme_name = $('#theme_name_edit').val();
            const start_date = $('#start_date_edit').val();
            const end_date = $('#end_date_edit').val();


            // Prepare FormData
            let formData = new FormData();
            formData.append('theme_name', theme_name);
            formData.append('start_date', start_date);
            formData.append('end_date', end_date);
            let id = $('#updateCategoryLoader').attr("data-id");
            let routeTemplate = "{{ route('super_admin.theme.update', ['id' => ':id']) }}";
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
            $('#category_del_title').text('Are You Sure you want to delete this Theme Name?' + ' ' + name);
            $('#deleteCategory').modal('show');
        }

        function deleteTheme() {
            $('#deleteCategoryLoader').addClass('loading');
            let dataId = $('#deleteCategoryLoader').attr("data-id");
            let url = "{{ route('super_admin.theme.delete') }}";
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
