@extends('template.tmp')
@section('title', 'Units')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Units</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="#" class="btn btn-added btn-primary" data-bs-toggle="modal" data-bs-target="#add-unit"><i class="me-2"></i>Add New Unit</a>
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
                                            <th>Base Unit</th>
                                            <th>Base Value</th>
                                            <th>Operator</th>
                                            <th>Child Unit</th>
                                            <th>Child Value</th>
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

        <!-- Add Unit -->
            <div class="modal fade" id="add-unit">
                <div class="modal-dialog model-lg">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Create Unit</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body">
                                    <form id="unit-store" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Base Unit</label>
                                                    <input type="text" name="base_unit" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Child Unit </label>
                                                    <input type="text" name="child_unit"  class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="form-label">Base Unit Value</label>
                                                    <input type="number" name="base_unit_value" step="0.01" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Operator</label>
                                                    <select name="operator" id="edit_operator" class="form-select">
                                                        <option value="*">*</option>
                                                        <option value="/">/</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="form-label">Child Unit Value</label>
                                                    <input type="number" name="child_unit_value" step="0.01" class="form-control">
                                                </div>
                                            </div>
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
                                            <button type="submit" id="submit-unit-store" class="btn btn-submit btn-primary">Create Unit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /Add Unit -->

         <!-- Edit Unit -->
            <div class="modal fade" id="edit-unit">
                <div class="modal-dialog custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header">
                                    <div class="page-title">
                                        <h4>Edit Unit</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body">
                                    <form id="unit-update" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT') <!-- For PUT method -->
                                        <input type="hidden" name="id" id="unit_id"> <!-- Hidden field to store the unit ID -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Base Unit</label>
                                                    <input type="text" name="base_unit" id="edit_base_unit" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Child Unit</label>
                                                    <input type="text" name="child_unit" id="edit_child_unit"  class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="form-label">Base Unit Value</label>
                                                    <input type="number" name="base_unit_value" id="edit_base_unit_value" step="0.01" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Operator</label>
                                                    <select name="operator" id="edit_operator" class="form-select">
                                                        <option value="*">*</option>
                                                        <option value="/">/</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="form-label">Child Unit Value</label>
                                                    <input type="number" name="child_unit_value" id="edit_child_unit_value" step="0.01" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        

            
                                      
                                        <div class="modal-footer-btn">
                                            <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="submit-unit-update" class="btn btn-submit btn-primary">Update Unit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <!-- /Edit Unit -->

     <!-- Delete Unit -->
        <div class="modal fade" id="delete-unit">
            <div class="modal-dialog custom-modal-two">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Delete Unit</h4>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    
                                </button>
                            </div>
                            
                                <div class="modal-body custom-modal-body pt-3 pb-0">
                                    <p class="text-center">Are you sure you want to delete this unit?</p>
                                </div>
                                <div class="modal-footer-btn p-3 mt-2">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit shadow-sm btn-danger" id="submit-unit-destroy">Delete</button>
                                </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
    <!-- /Delete Unit -->


 


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
                ajax: "{{ route('unit.index') }}",
                columns: [
                    { data: 'id' },
                    { data: 'base_unit' },
                    { data: 'base_unit_value' },
                    { data: 'operator' },
                    { data: 'child_unit' },
                    { data: 'child_unit_value' },
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

          
            $('#unit-store').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-unit-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('unit.store') }}",
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
                        
                        submit_btn.prop('disabled', false).html('Create Unit');  

                        if(response.success == true){
                            $('#add-unit').modal('hide'); 
                            $('#unit-store')[0].reset();  // Reset all form data
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
                        submit_btn.prop('disabled', false).html('Create Unit');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });
            
            $('#unit-update').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-unit-update');
                let unit_id = $('#unit_id').val(); // Get the ID of the unit being edited

                let editFormData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('unit.update', ':id') }}".replace(':id', unit_id), // Using route name
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
                        
                        submit_btn.prop('disabled', false).html('Update Unit');  

                        if(response.success == true){
                            $('#edit-unit').modal('hide'); 
                            $('#unit-update')[0].reset();  // Reset all form data
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
                        submit_btn.prop('disabled', false).html('Update Unit');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


            $('#submit-unit-destroy').click(function() {
                let unit_id = $(this).data('id');
                var submit_btn = $('#submit-unit-destroy');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('unit.destroy', ':id') }}".replace(':id', unit_id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                            submit_btn.prop('disabled', true);
                            submit_btn.html('Processing');
                        },
                    success: function(response) {
                        
                        submit_btn.prop('disabled', false).html('Delete Unit');  

                        if(response.success == true){
                            $('#delete-unit').modal('hide'); 
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
                        submit_btn.prop('disabled', false).html('Delete Unit');
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

        });

        // Handle the delete button click
       

        function editUnit(id) {
            $.get("{{ route('unit.edit', ':id') }}".replace(':id', id), function(response) {
                $('#unit_id').val(response.id);
                $('#edit_base_unit').val(response.base_unit);
                $('#edit_base_unit_value').val(response.base_unit_value);
                $('#edit_child_unit').val(response.child_unit);
                $('#edit_child_unit_value').val(response.child_unit_value);
                $('#edit_operator').val(response.operator).trigger('change');
                $('#edit_is_active').val(response.is_active).trigger('change');              


                $('#edit-unit').modal('show');
            }).fail(function(xhr) {
                alert('Error fetching unit details: ' + xhr.responseText);
            });
        }

        function deleteUnit(id) {
            $('#submit-unit-destroy').data('id', id);
            $('#delete-unit').modal('show');
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
Units

unit_id

editUnit
deleteUnit
unit
Unit 
--}}