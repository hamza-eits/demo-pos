@extends('template.tmp')
@section('title', 'Taxes')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Taxes</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="#" class="btn btn-added btn-primary" data-bs-toggle="modal" data-bs-target="#add-tax"><i class="me-2"></i>Add New Tax</a>
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
                                            <th>Rate</th>
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

        <!-- Add Tax -->
            <div class="modal fade" id="add-tax">
                <div class="modal-dialog custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Create Tax</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body">
                                    <form id="tax-store" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rate</label>
                                            <input type="number" name="rate" step="0.01" class="form-control">
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
                                            <button type="submit" id="submit-tax-store" class="btn btn-submit btn-primary">Create Tax</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /Add Tax -->

         <!-- Edit Tax -->
            <div class="modal fade" id="edit-tax">
                <div class="modal-dialog custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Edit Tax</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body">
                                    <form id="tax-update" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT') <!-- For PUT method -->
                                        <input type="hidden" name="id" id="tax_id"> <!-- Hidden field to store the tax ID -->
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" id="edit_name" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rate</label>
                                            <input type="number" name="rate" id="edit_rate" step="0.01" class="form-control">
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
                                            <button type="submit" id="submit-tax-update" class="btn btn-submit btn-primary">Update Tax</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <!-- /Edit Tax -->

     <!-- Delete Tax -->
        <div class="modal fade" id="delete-tax">
            <div class="modal-dialog custom-modal-two">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Delete Tax</h4>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    
                                </button>
                            </div>
                            
                                <div class="modal-body custom-modal-body pt-3 pb-0">
                                    <p class="text-center">Are you sure you want to delete this tax?</p>
                                </div>
                                <div class="modal-footer-btn p-3 mt-2">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit shadow-sm btn-danger" id="submit-tax-destroy">Delete</button>
                                </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
    <!-- /Delete Tax -->


 


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
                ajax: "{{ route('tax.index') }}",
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'rate' },
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

          
            $('#tax-store').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-tax-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('tax.store') }}",
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
                        
                        submit_btn.prop('disabled', false).html('Create Tax');  

                        if(response.success == true){
                            $('#add-tax').modal('hide'); 
                            $('#tax-store')[0].reset();  // Reset all form data
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
                        submit_btn.prop('disabled', false).html('Create Tax');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });
            
            $('#tax-update').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-tax-update');
                let tax_id = $('#tax_id').val(); // Get the ID of the tax being edited

                let editFormData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('tax.update', ':id') }}".replace(':id', tax_id), // Using route name
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
                        
                        submit_btn.prop('disabled', false).html('Update Tax');  

                        if(response.success == true){
                            $('#edit-tax').modal('hide'); 
                            $('#tax-update')[0].reset();  // Reset all form data
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
                        submit_btn.prop('disabled', false).html('Update Tax');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


            $('#submit-tax-destroy').click(function() {
                let tax_id = $(this).data('id');
                var submit_btn = $('#submit-tax-destroy');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('tax.destroy', ':id') }}".replace(':id', tax_id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                            submit_btn.prop('disabled', true);
                            submit_btn.html('Processing');
                        },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Delete Tax');  

                        if(response.success == true){
                            $('#delete-tax').modal('hide'); 
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
                        submit_btn.prop('disabled', false).html('Delete Tax');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

        });

        // Handle the delete button click
       

        function editTax(id) {
            $.get("{{ route('tax.edit', ':id') }}".replace(':id', id), function(response) {
                $('#tax_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_rate').val(response.rate);
                $('#edit_is_active').val(response.is_active).trigger('change');              


                $('#edit-tax').modal('show');
            }).fail(function(xhr) {
                alert('Error fetching tax details: ' + xhr.responseText);
            });
        }

        function deleteTax(id) {
            $('#submit-tax-destroy').data('id', id);
            $('#delete-tax').modal('show');
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
Taxes

tax_id

editTax
deleteTax
tax
Tax 
--}}