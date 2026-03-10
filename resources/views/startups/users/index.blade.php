@extends('template.tmp')
@section('title', 'Users')


@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Users</h3>

                            <div class="page-title-right d-flex ">

                                <div class="page-btn">
                                    <a href="#" class="btn btn-added btn-primary" data-bs-toggle="modal" data-bs-target="#add-user"><i class="me-2"></i>Add New User</a>
                                </div>  

                                {{-- <button type="button" id="importButton" class="btn btn-secondary mr-2 mx-2 text-end mb-2"
                                        data-bs-toggle="modal" data-bs-target="#import-model">Import
                                    </button>  --}}
                            </div>



                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">

                        @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {!! session('error') !!}
                                    
                                  
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {!! session('success') !!}
                                   
                                </div>
                            @endif

                        <div class="card">

                            <div class="card-body">
                                <table id="table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>mobile No</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Add User -->
            <div class="modal fade" id="add-user">
                <div class="modal-dialog custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Create User</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body">
                                    <form id="user-store" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mobile No</label>
                                            <input type="text" name="mobile_no"  class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" name="email" class="form-control" autocomplete="off">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" autocomplete="new-password">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                        </div>
                            
                                        <div class="mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image" id="image" class="form-control" style="width: 100%;">
                                        </div>
                                        <div class="mb-3 ">
                                            <label class="col-form-label">Type</label>
                                            <select name="type" id="type" class="form-select form-control" style="width:100%">
                                                <option value="">Select Type</option>
                                                @foreach ($types as $type)

                                                    <option value="{{ $type }}">{{ $type }}</option>

                                                @endforeach
                                               
                                            </select>
                                        </div>
            
                                    
                                        <div class="modal-footer-btn">
                                            <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="submit-user-store"  class="btn btn-submit btn-primary">Create User</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /Add User -->

        <!-- Edit User -->
            <div class="modal fade" id="edit-user">
                <div class="modal-dialog custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Edit User</h4>
                                    </div>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body">
                                    <form id="user-update" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT') <!-- For PUT method -->
                                        <input type="hidden" name="id" id="user_id"> <!-- Hidden field to store the user ID -->
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" id="edit_name" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mobile No</label>
                                            <input type="text" name="mobile_no" id="edit_mobile_no" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" name="email" id="edit_email" class="form-control" autocomplete="off">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            {{-- hint is used for password becasue we cant unhash passowrd --}}
                                            <input type="text" name="hint" id="edit_hint" class="form-control" autocomplete="off">
                                        </div>
                                      
                                        <div class="mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image" id="edit_image" class="form-control" style="width: 100%;">
                                        </div>

                                        <div class="mb-3 ">
                                            <label class="col-form-label">Type</label>
                                            <select name="type" id="edit_type" class="form-select form-control" style="width:100%">
                                                <option value="">Select Type</option>
                                                @foreach ($types as $type)

                                                    <option value="{{ $type }}">{{ $type }}</option>

                                                @endforeach
                                               
                                            </select>
                                        </div>

                                        {{-- <div class="mb-3 ">
                                            <label class="col-form-label">Type</label>
                                            <select name="type" id="edit_type" class="form-select form-control" style="width:100%">
                                                <option value="">Select Type</option>
                                                <option value="admin">Admin</option>
                                                <option value="driver">Driver</option>
                                               
                                            </select>
                                        </div> --}}
            
                                      
                                        <div class="modal-footer-btn">
                                            <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="submit-user-update"  class="btn btn-submit btn-primary">Update User</button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /Edit User -->

        <!-- Delete User -->
            <div class="modal fade" id="delete-user">
                <div class="modal-dialog custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Delete User</h4>
                                    </div>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                
                                    <div class="modal-body custom-modal-body pt-3 pb-0">
                                        <p class="text-center">Are you sure you want to delete this user?</p>
                                    </div>
                                    <div class="modal-footer-btn p-3 mt-2">
                                        <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-submit shadow-sm btn-danger" id="submit-user-destroy">Delete</button>
                                    </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        <!-- /Delete User -->

        <!-- /import CSV File -->
            <div class="modal fade exampleModal" id="import-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import Users</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <form action="{{ route('user.uploadFile') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <span>Click <a
                                                href="{{ route('user.downloadSampleFile') }}">here</a>
                                            to download the sample CSV file.</span>
                                        <hr>
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Upload File *</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="file" name="csv_file" required
                                                            accept=".csv">
                                                        <label class="custom-file-label" for="file">Choose file</label>
                                                    </div>
                                                </div>
                                                {{-- <div class="alert-warning alert alert-warning fade show" role="alert"><h5 class="alert-heading">Dear User</h5>
                                                    <p>
                                                        Please double-check the drivers' mobile numbers before uploading the CSV file.
                                                    </p><hr class="border-success-subtle"><p class="mb-0">Otherwise, the field will be empty, and you'll need to assign it manually</p>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!-- /import CSV File -->



    <!-- END: Content-->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        // Create an instance of Notyf
        let notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top',
            },
        });
    </script>

    
    
    <script>

        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.index') }}",
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'mobile_no' },
                    { data: 'email' },
                    { data: 'type' },
                    { 
                        data: 'is_active',
                        className: 'text-center', // This applies the text-center class to the entire column
                        render: function(data,type,row){
                            
                            if(data == 1)
                                return '<span class="badge bg-success font-size-12 text-center">Active</span>';
                            else
                                return '<span class="badge bg-danger font-size-12">Inactive</span>';
                    
                        }
                    
                     },
                     { data: 'image' },
                   
                    { data: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
            });

            $('#add-user, #edit-user').on('hidden.bs.modal', function () {
                $(this).find('form').trigger('reset');
            });


            $('#user-store').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-user-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.store') }}",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: createformData,
                    enctype: "multipart/form-data",
                    beforeSend: function() {
                        submit_btn.prop('disabled', true);
                        submit_btn.html('Processing');
                    },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Create User');  

                        if(response.success == true){
                            $('#add-user').modal('hide'); 
                            table.ajax.reload();
                        
                            notyf.success({
                                message: response.message, 
                                duration: 3000
                            });
                        }else{
                            notyf.error({
                                message: response.message,
                                duration: 5000
                            });
                        }   
                    },
                    error: function(e) {
                        submit_btn.prop('disabled', false).html('Create User');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


            $('#user-update').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-user-update');
                let user_id = $('#user_id').val(); // Get the ID of the customer being edited

                let editFormData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.update', ':id') }}".replace(':id', user_id), // Using route name
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: editFormData,
                    enctype: "multipart/form-data",
                    beforeSend: function() {
                        submit_btn.prop('disabled', true);
                        submit_btn.html('Processing');
                    },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Update User');  

                        if(response.success == true){
                            $('#edit-user').modal('hide'); 
                            table.ajax.reload();
                        
                            notyf.success({
                                message: response.message, 
                                duration: 3000
                            });
                        }else{
                            notyf.error({
                                message: response.message,
                                duration: 5000
                            });
                        }   
                    },
                    error: function(e) {
                        submit_btn.prop('disabled', false).html('Update User');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

          
            $('#submit-user-destroy').click(function() {
                let data_id = $(this).data('id');
                var submit_btn = $('#submit-user-destroy');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('user.destroy', ':id') }}".replace(':id', data_id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                            submit_btn.prop('disabled', true);
                            submit_btn.html('Processing');
                        },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Delete User');  

                        if(response.success == true){
                            $('#delete-user').modal('hide'); 
                            table.ajax.reload();
                        
                            notyf.success({
                                message: response.message, 
                                duration: 3000
                            });
                        }else{
                            notyf.error({
                                message: response.message,
                                duration: 5000
                            });
                        }   
                    },
                    error: function(e) {
                        submit_btn.prop('disabled', false).html('Delete User');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


           

        });

        function editUser(id) {
            $.get("{{ route('user.edit', ':id') }}".replace(':id', id), function(response) {
                $('#user_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_email').val(response.email);
                $('#edit_mobile_no').val(response.mobile_no);
                $('#edit_hint').val(response.hint);
                $('#edit_type').val(response.type).trigger('change');
                $('#edit_is_active').val(response.is_active).trigger('change');
                console.log(response.is_active);              
                $('#edit-user').modal('show');
            }).fail(function(xhr) {
                alert('Error fetching user details: ' + xhr.responseText);
            });
        }

        function deleteUser(id) {
            $('#submit-user-destroy').data('id', id);
            $('#delete-user').modal('show');
        }

    </script>


    <script>
        $(document).ready(function() {
            $('#table thead tr').clone(true).appendTo('#table thead');
            $('#table thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="  ' + title +
                    '"  class="form-control form-control-sm" />');


                // hide text field from any column you want too
                if (title == 'Action') {
                    $(this).hide();
                }

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });

            });
            var table = $('#table').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                retrieve: true,
                paging: false

            });
        });
    </script>
@endsection
