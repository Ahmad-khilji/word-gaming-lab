@extends('admin.layouts.app')
@section('title', 'Admin Dashboard | Word Gaming Lab')

@section('content')
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Change Password</h5>
            <form onsubmit="event.preventDefault(); changePassword();">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Change Email</label>
                        <input type="email" class="form-control" id="email">
                    </div>
                <div class="col-6">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password">
                </div>
                <div class="col-6">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password">
                </div>
                <div class="col-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password">
                </div>
            </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function changePassword() {
        let formData = new FormData();
        formData.append('current_password', $('#current_password').val());
        formData.append('new_password', $('#new_password').val());
        formData.append('confirm_password', $('#confirm_password').val());
        formData.append('email', $('#email').val());

        $.ajax({
            type: "POST",
            url: "{{ route('super_admin.password.update') }}",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    $('#email, #current_password, #new_password, #confirm_password').val('');
                    toastifySuccess(response.message);
                } else {
                    toastifyError(response.message || 'Something went wrong.');
                }
            },
            error: function(request) {
                let errorResponse = request.responseJSON;
                if (errorResponse.errors) {
                    let errorList = '<ul>';
                    $.each(errorResponse.errors, function(field, error) {
                        errorList += '<li>' + error + '</li>';
                    });
                    errorList += '</ul>';
                    toastifyError(errorList);
                } else {
                    toastifyError(errorResponse.message);
                }
            },
        });
    }
</script>
@endsection
