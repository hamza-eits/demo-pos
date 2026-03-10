@extends('template.tmp')
@section('title', 'Variaiton')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Variations</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="#" class="btn btn-added btn-primary" data-bs-toggle="modal" data-bs-target="#add-variation"><i class="bx bx-plus me-2"></i>Add</a>
                                </div>  
                            </div>



                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">

                      

                        <div class="card">

                            <div class="card-body">
                                <table id="table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
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


    <!-- Add Variation -->
    <div class="modal fade" id="add-variation">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Create Variation</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form id="variation-store" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label  class="form-label">Values</label>
                                    <div id="values-container">
                                        <input type="text" class="form-control mb-2" name="values[]" placeholder="Enter value" required>
                                    </div>
                                    <button type="button" class="btn btn-success mt-2 text-right" id="create_add_value"
                                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Click to Add More Variation">
            
                                        <span class="fa fa-plus"></span> Variation
                                    </button>
                                </div>
    
                                <div class="mb-0">
                                    <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                        <span class="status-label">Status</span>
                                        <input type="checkbox" name="is_active" id="is_active" class="check" value="1" checked>
                                        <label for="is_active" class="checktoggle"></label>
                                    </div>
                                </div>
                                
                                <div class="modal-footer-btn mt-3 text-end">
                                    <button type="button"  class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit"  class="btn btn-submit btn-primary">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- /Add Variation -->


     <!-- Edit Variation -->
     <div class="modal fade" id="edit-variation">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Edit Variation</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form id="variation-update" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') <!-- For PUT method -->
                                <input type="hidden" name="id" id="variation_id"> <!-- Hidden field to store the variation ID -->
                                <div class="mb-3">
                                    <label for="" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_values" class="form-label">Values</label>
                                    <div id="edit_values_container">
                                        <!-- Dynamic Input Fields Will Appear Here -->
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-2" id="edit_add_value">Add Value</button>
                                </div>
    
                                <div class="mb-0">
                                    <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                        <span class="status-label">Status</span>
                                        <input type="checkbox" name="is_active" id="edit_is_active" class="check" value="1">
                                        <label for="edit_is_active" class="checktoggle"></label>
                                    </div>
                                </div>
                                <div class="modal-footer-btn mt-3 text-end">
                                    <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit btn-primary">Update</button>
                                </div>
                            
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Variation -->

     <!-- Delete Variation -->
     <div class="modal fade" id="delete-variation">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Delete Variation</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        
                            <div class="modal-body custom-modal-body pt-3 pb-0">
                                <p class="text-center">Are you sure you want to delete this variation?</p>
                            </div>
                            <div class="modal-footer-btn p-3 mt-2">
                                <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-submit shadow-sm btn-danger" id="confirmDeleteButton">Delete</button>
                            </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
     
    <!-- /Delete Variation -->


    <script>

        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('variation.index') }}",
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'values' },
                    { data: 'status', orderable: false, searchable: false },
                    { data: 'action', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
            });

            $('#add-variation, #edit-variation').on('hidden.bs.modal', function () {
                $(this).find('form').trigger('reset');
            });
            

            $('#variation-store').submit(function(e) {
                e.preventDefault();
                let createformData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('variation.store') }}",
                    data: createformData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if(response.success == true){
                            $('#add-variation').modal('hide');
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
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

            $('#variation-update').submit(function(e) {
                e.preventDefault();
                let editFormData = new FormData(this);
                let id = $('#variation_id').val(); // Get the ID of the variation being edited
                $.ajax({
                    type: 'POST',
                    url: "{{ route('variation.update', ':id') }}".replace(':id', id), // Using route name
                    data: editFormData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if(response.success == true){
                            $('#edit-variation').modal('hide');
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
                        // alert('Error adding data: ' + response.responseJSON.message);
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });                    
                    }
                });
            });
           
            $('#confirmDeleteButton').click(function() {
                let id = $(this).data('id');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('variation.destroy', ':id') }}".replace(':id', id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                 
                    success: function(response) {
                        

                        if(response.success == true){
                            $('#delete-variation').modal('hide');
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
                    
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


           

        });


        function editVariation(id) {
            $.get("{{ route('variation.edit', ':id') }}".replace(':id', id), function(response) {
                $('#variation_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_is_active').prop('checked', response.is_active === 1);
                 // Clear existing input fields
            $('#edit_values_container').empty();

            // Populate the values if they exist
            if (response.values) {
                let values = JSON.parse(response.values);
                values.forEach(function(value) {
                    addEditValueField(value);
                });
            }
                $('#edit-variation').modal('show');
            }).fail(function(xhr) {
                alert('Error fetching variation details: ' + xhr.responseText);
            });
        }

        function deleteVariation(id) {
            $('#confirmDeleteButton').data('id', id);
            $('#delete-variation').modal('show');
        }

    </script>

<script>
    //create variation button code
    document.addEventListener('DOMContentLoaded', function() {
        let valuesContainer = document.getElementById('values-container');
        let addValueButton = document.getElementById('create_add_value');

        addValueButton.addEventListener('click', function() {
            let newInput = document.createElement('div');
            newInput.classList.add('input-group', 'mb-2');
            newInput.innerHTML = `
                <input type="text" class="form-control" name="values[]" placeholder="Enter value" required>
                <button type="button" class="btn btn-danger remove-value">Delete</button>
            `;
            valuesContainer.appendChild(newInput);

            // Add event listener to the delete button
            newInput.querySelector('.remove-value').addEventListener('click', function() {
                newInput.remove();
            });
        });
    });
</script>
<script>
    //edit variation button code
    function addEditValueField(value = '') {
        let newInput = document.createElement('div');
        newInput.classList.add('input-group', 'mb-2');
        newInput.innerHTML = `
            <input type="text" class="form-control" name="values[]" value="${value}" placeholder="Enter value" required>
            <button type="button" class="btn btn-danger remove-value">Delete</button>
        `;
        document.getElementById('edit_values_container').appendChild(newInput);

        // Add event listener to the delete button
        newInput.querySelector('.remove-value').addEventListener('click', function() {
            newInput.remove();
        });
    }

    // Event listener for adding new input fields in the edit modal
    document.getElementById('edit_add_value').addEventListener('click', function() {
        addEditValueField();
    });   
</script>


@endsection

