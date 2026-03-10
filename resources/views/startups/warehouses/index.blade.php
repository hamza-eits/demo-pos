@extends('template.tmp')
@section('title', 'Warehouses')


@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Warehouses</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="#" class="btn btn-added btn-primary" data-bs-toggle="modal" data-bs-target="#add-warehouse"><i class="me-2"></i>Add New Warehouse</a>
                                </div>  
                            </div>



                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">

                        @if (session('error'))
                            <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">

                                {{ Session::get('error') }}
                            </div>
                        @endif
                        @if (count($errors) > 0)

                            <div>
                                <div class="alert alert-danger pt-3 pl-0   border-3">
                                    <p class="font-weight-bold"> There were some problems with your input.</p>
                                    <ul>

                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                        @endif

                        <div class="card">

                            <div class="card-body">
                                <table id="table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Address</th>
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

        <!-- Add Warehouse -->
            <div class="modal fade" id="add-warehouse">
                <div class="modal-dialog custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Create Warehouse</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body">
                                    <form id="warehouse-store" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="number" name="phone" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control">
                                        </div>
                                      
                                        <div class="mb-3 ">
                                            <label class="col-form-label">Status</label>
                                            <select name="is_active" id="is_active" class="form-select form-control" style="width:100%">
                                                <option selected value="1" >Active</option>
                                                <option value="0">Inactive</option>
                                               
                                            </select>
                                        </div>
            
                                    
                                        <div class="modal-footer-btn">
                                            <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="submit-warehouse-store" class="btn btn-submit btn-primary">Create Warehouse</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /Add Warehouse -->

         <!-- Edit Warehouse -->
            <div class="modal fade" id="edit-warehouse">
                <div class="modal-dialog custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Edit Warehouse</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body">
                                    <form id="warehouse-update" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT') <!-- For PUT method -->
                                        <input type="hidden" name="id" id="warehouse_id"> <!-- Hidden field to store the warehouse ID -->
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" id="edit_name" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="number" name="phone" id="edit_phone" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" id="edit_email" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="address" id="edit_address" class="form-control">
                                        </div>
                                        <div class="mb-3 ">
                                            <label class="col-form-label">Status</label>
                                            <select name="is_active" id="edit_is_active" class="form-select form-control" style="width:100%">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                               
                                            </select>
                                        </div>
                                        

            
                                      
                                        <div class="modal-footer-btn">
                                            <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="submit-warehouse-update" class="btn btn-submit btn-primary">Update Warehouse</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <!-- /Edit Warehouse -->

     <!-- Delete Warehouse -->
        <div class="modal fade" id="delete-warehouse">
            <div class="modal-dialog custom-modal-two">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Delete Warehouse</h4>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    
                                </button>
                            </div>
                            
                                <div class="modal-body custom-modal-body pt-3 pb-0">
                                    <p class="text-center">Are you sure you want to delete this warehouse?</p>
                                </div>
                                <div class="modal-footer-btn p-3 mt-2">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit shadow-sm btn-danger" id="submit-warehouse-destroy">Delete</button>
                                </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
    <!-- /Delete Warehouse -->


 


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
                ajax: "{{ route('warehouse.index') }}",
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'phone' },
                    { data: 'email' },
                    { data: 'address' },
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
                    { data: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
            });

          
            $('#warehouse-store').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-warehouse-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('warehouse.store') }}",
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
                        
                        submit_btn.prop('disabled', false).html('Create Warehouse');  

                        if(response.success == true){
                            $('#add-warehouse').modal('hide'); 
                            $('#warehouse-store')[0].reset();  // Reset all form data
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
                        submit_btn.prop('disabled', false).html('Create Warehouse');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });
            
            $('#warehouse-update').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-warehouse-update');
                let warehouse_id = $('#warehouse_id').val(); // Get the ID of the warehouse being edited

                let editFormData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('warehouse.update', ':id') }}".replace(':id', warehouse_id), // Using route name
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
                        
                        submit_btn.prop('disabled', false).html('Update Warehouse');  

                        if(response.success == true){
                            $('#edit-warehouse').modal('hide'); 
                            $('#warehouse-update')[0].reset();  // Reset all form data
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
                        submit_btn.prop('disabled', false).html('Update Warehouse');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


            $('#submit-warehouse-destroy').click(function() {
                let warehouse_id = $(this).data('id');
                var submit_btn = $('#submit-warehouse-destroy');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('warehouse.destroy', ':id') }}".replace(':id', warehouse_id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                            submit_btn.prop('disabled', true);
                            submit_btn.html('Processing');
                        },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Delete Warehouse');  

                        if(response.success == true){
                            $('#delete-warehouse').modal('hide'); 
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
                        submit_btn.prop('disabled', false).html('Delete Warehouse');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

        });

        // Handle the delete button click
       

        function editWarehouse(id) {
            $.get("{{ route('warehouse.edit', ':id') }}".replace(':id', id), function(response) {
                $('#warehouse_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_phone').val(response.phone);
                $('#edit_email').val(response.email);
                $('#edit_address').val(response.address);
                $('#edit_is_active').val(response.is_active).trigger('change');              


                $('#edit-warehouse').modal('show');
            }).fail(function(xhr) {
                alert('Error fetching warehouse details: ' + xhr.responseText);
            });
        }

        function deleteWarehouse(id) {
            $('#submit-warehouse-destroy').data('id', id);
            $('#delete-warehouse').modal('show');
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

{{-- 
Warehouses

warehouse_id

editWarehouse
deleteWarehouse
warehouse
Warehouse 
--}}