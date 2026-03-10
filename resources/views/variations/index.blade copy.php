@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Variation Listing</h4>
            <div class="page-title-right">
                <button type="button" class="btn btn-primary" id="addBrandBtn" data-bs-toggle="modal"
                    data-bs-target="#createModel">
                    Add Variation
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Variations</h4>
                <table class="table datatable table-hover dt-responsive nowrap w-100 table-sm" id="table">
                    <thead>
                        <tr>
                            <th scope="col-md-2">S.No</th>
                            <th scope="col-md-4">Name</th>
                            <th scope="col-md-4">values</th>
                            <th scope="col-md-4">Status</th>

                            <th scope="col-md-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Variation Modal -->
<div class="modal fade" id="createModel" tabindex="-1" aria-labelledby="createModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModelLabel">Add New Variation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="values-container" class="form-label">Values</label>
                        <div id="values-container">
                            <input type="text" class="form-control" name="values[]" placeholder="Enter value" required>
                        </div>
                        <button type="button" class="btn btn-success mt-2 text-right" id="create_add_value"
                            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Click to Add More Variation">

                            <span class="fa fa-plus"></span> Variation
                        </button>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Status</label>
                        <select id="is_active" name="is_active" class="form-select" required>
                            <option selected value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>


                    <button type="submit" class="btn btn-primary">Save Variation</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Variation Modal -->
<div class="modal fade" id="editModel" tabindex="-1" aria-labelledby="editModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModelLabel">Edit Variation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">

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

                    <div class="mb-3">
                        <label for="" class="form-label">Status</label>
                        <select id="edit_is_active" name="is_active" class="form-select" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this variation?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
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
    $(document).ready(function() {
        // Initialize DataTable with server-side processing
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('variation.index') }}",
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'values' },
                { data: 'is_active', render: function(data) {
                    return data ? 'Active' : 'Inactive';
                }},
                { data: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],
        });

        // Reset form when modal is hidden
        $('#createModel, #editModel').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        // Handle form submission for creating a new variation
        $('#createForm').submit(function(e) {
            e.preventDefault();
            let createformData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('variation.store') }}",
                data: createformData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#createModel').modal('hide');
                    table.ajax.reload();
                },
                error: function(response) {
                    alert('Error adding variation: ' + response.responseText);
                }
            });
        });

        // Handle form submission for editing a variation
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            var url = "{{ route('variation.update', ':id') }}".replace(':id', $('#edit_id').val());
            $.ajax({
                url: url,
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                   
                    $('#editModel').modal('hide');
                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert('Error updating variation: ' + xhr.responseText);
                }
            });
        });

        // Handle delete variation
        $('#confirmDeleteButton').on('click', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('variation.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                  
                    $('#deleteConfirmationModal').modal('hide');
                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert('Error deleting variation: ' + xhr.responseText);
                }
            });
        });
    });

    function editVariation(id) {
        $.get("{{ route('variation.edit', ':id') }}".replace(':id', id), function(response) {
            // Populate the form fields with the fetched data
            $('#edit_id').val(response.id);
            $('#edit_name').val(response.name);
            $('#edit_is_active').val(response.is_active);

            // Clear existing input fields
            $('#edit_values_container').empty();

            // Populate the values if they exist
            if (response.values) {
                let values = JSON.parse(response.values);
                values.forEach(function(value) {
                    addEditValueField(value);
                });
            }

            // Show the modal
            $('#editModel').modal('show');
        }).fail(function(xhr) {
            // Handle errors
            alert('Error fetching variation details: ' + xhr.responseText);
        });
    }

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


    function deleteVariation(id) {
        $('#confirmDeleteButton').data('id', id);
        $('#deleteConfirmationModal').modal('show');
    }
</script>
@endsection