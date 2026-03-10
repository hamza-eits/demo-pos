@extends('template.tmp')
@section('title', 'Parties List')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All {{ ($type) ? ucfirst($type) : 'Party' }}</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="#" class="btn btn-added btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#add-party"><i class="me-2 bx bx-plus"></i><span class="text-capitalize">{{ ($type) ? ucfirst($type) : 'Party' }}</span></a>
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
                                            <th>Address</th>
                                            <th>Shipping Address</th>
                                            <th>Primary Contact No</th>
                                            <th>Email</th>
                                            <th>Secondary Contact No</th>
                                            <th>Credit Period</th>
                                            <th>status</th>
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

    <!-- Add Party -->
    <div class="modal fade" id="add-party">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Create {{ ($type) ? ucfirst($type) : 'Party' }}</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form id="party-store" enctype="multipart/form-data">
                                @csrf
                                @include('startups.parties.create_modal_fields')

                                <div class="modal-footer-btn text-end">
                                    <button type="button" class="btn btn-cancel me-2 btn-dark"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="submit-party-store" class="btn btn-submit btn-primary">Create
                                        {{ ($type) ? ucfirst($type) : 'Party' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Party -->

    <!-- Edit Party -->
    <div class="modal fade" id="edit-party">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Edit {{ ($type) ? ucfirst($type) : 'Party' }}</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form id="party-update" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') <!-- For PUT method -->
                                <input type="hidden" name="id" id="party_id">
                                <!-- Hidden field to store the party ID -->
                                @include('startups.parties.edit_modal_fields')

                                <div class="modal-footer-btn text-end">
                                    <button type="button" class="btn btn-cancel me-2 btn-dark "
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="submit-party-update"
                                        class="btn btn-submit btn-primary">Update {{ ($type) ? ucfirst($type) : 'Party' }}</button>
                                </div>
                            </form>

                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Party -->

    <!-- Delete Party -->
    <div class="modal fade" id="delete-party">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Delete {{ ($type) ? ucfirst($type) : 'Party' }}</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>

                        <div class="modal-body custom-modal-body pt-3 pb-0">
                            <p class="text-center">Are you sure you want to delete this {{ $type }}?</p>
                        </div>
                        <div class="modal-footer-btn p-3 mt-2">
                            <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-submit shadow-sm btn-danger"
                                id="submit-party-destroy">Delete</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Delete Party -->





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


            $('.individual').addClass('d-none');
            $('.business').addClass('d-none');

            $('#type-individual, #edit-type-individual').on('click', function() {
                $('.business').addClass('d-none');
                $('.individual').removeClass('d-none');
            });

            $('#type-business, #edit-type-business').on('click', function() {
                $('.individual').addClass('d-none');
                $('.business').removeClass('d-none');
            });

            $('#btn-more-info, #edit-btn-more-info').on('click', function() {

                if ($('.more-info').hasClass('d-none')) {
                    $('.more-info').removeClass('d-none');
                } else {
                    $('.more-info').addClass('d-none');
                }

            });
        });
    </script>

    <script>
        $(document).ready(function() {

            var type = "{{ $type }}"; // Retrieve the type value passed to the view (supplier or customer)

            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('party-index', ':type') }}".replace(':type', type), // Using route name
                    type: 'GET' // Specify the request type
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'business_name'
                    },
                    {
                        data: 'address_line_1'
                    },
                    {
                        data: 'shipping_address'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'alternate_number'
                    },
                    {
                        data: 'credit_limit'
                    },
                    {
                        data: 'is_active',
                        className: 'text-center', // This applies the text-center class to the entire column
                        render: function(data, type, row) {

                            if (data == 1)
                                return '<span class="badge bg-success font-size-12 text-center">Active</span>';
                            else
                                return '<span class="badge bg-danger font-size-12">Inactive</span>';

                        }
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ],

                dom: 'Bfrtip', // "B" enables buttons at the top
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Export to Excel', // Add icon with text
                        title: type+' list',// supplier or customer list 
                        className: 'btn btn-success btn-sm mx-2', // Custom Bootstrap classes
                        
                    },
                    {
                        extend: 'pdfHtml5',
                        title:  type+' list',
                        text: '<i class="fas fa-file-pdf"></i> Export to PDF',
                        className: 'btn btn-danger btn-sm',


                    }
                ]
            });

            $('#party-store').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-party-store');
                let createformData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('party.store') }}",
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

                        submit_btn.prop('disabled', false).html('Create');

                        if (response.success == true) {
                            $('#add-party').modal('hide');
                            $('#party-store')[0].reset(); // Reset all form data
                            table.ajax.reload();

                            notyf.success({
                                message: response.message,
                                duration: 3000
                            });
                        } else {
                            notyf.error({
                                message: response.message,
                                duration: 5000
                            });
                        }
                    },
                    error: function(e) {
                        submit_btn.prop('disabled', false).html('Create');

                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

            $('#party-update').on('submit', function(e) {
                e.preventDefault();
                var submit_btn = $('#submit-party-update');
                let party_id = $('#party_id').val(); // Get the ID of the party being edited

                let editFormData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('party.update', ':id') }}".replace(':id',
                    party_id), // Using route name
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

                        submit_btn.prop('disabled', false).html('Update');

                        if (response.success == true) {
                            $('#edit-party').modal('hide');
                            $('#party-update')[0].reset(); // Reset all form data
                            table.ajax.reload();

                            notyf.success({
                                message: response.message,
                                duration: 3000
                            });
                        } else {
                            notyf.error({
                                message: response.message,
                                duration: 5000
                            });
                        }
                    },
                    error: function(e) {
                        submit_btn.prop('disabled', false).html('Update');

                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


            $('#submit-party-destroy').click(function() {
                let party_id = $(this).data('id');
                var submit_btn = $('#submit-party-destroy');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('party.destroy', ':id') }}".replace(':id',
                    party_id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                        submit_btn.prop('disabled', true);
                        submit_btn.html('Processing');
                    },
                    success: function(response) {

                        submit_btn.prop('disabled', false).html('Delete Party');

                        if (response.success == true) {
                            $('#delete-party').modal('hide');
                            table.ajax.reload();

                            notyf.success({
                                message: response.message,
                                duration: 3000
                            });
                        } else {
                            notyf.error({
                                message: response.message,
                                duration: 5000
                            });
                        }
                    },
                    error: function(e) {
                        submit_btn.prop('disabled', false).html('Delete Party');

                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


        });




        // Handle the delete button click


        function editParty(id) {
            $.get("{{ route('party.edit', ':id') }}".replace(':id', id), function(response) {
                $('#party_id').val(response.id);
                $('#edit_party_type').val(response.party_type).trigger('change');
                $('#edit_business_name').val(response.business_name);
                $('#edit_prefix').val(response.prefix);
                $('#edit_first_name').val(response.first_name);
                $('#edit_middle_name').val(response.middle_name);
                $('#edit_last_name').val(response.last_name);
                $('#edit_mobile').val(response.mobile);
                $('#edit_alternate_number').val(response.alternate_number);
                $('#edit_landline').val(response.landline);
                $('#edit_email').val(response.email);
                $('#edit_tax_number').val(response.tax_number);
                $('#edit_opening_balance').val(response.opening_balance);
                $('#edit_pay_term_type').val(response.pay_term_type);
                $('#edit_credit_limit').val(response.credit_limit);
                $('#edit_address_line_1').val(response.address_line_1);
                $('#edit_address_line_2').val(response.address_line_2);
                $('#edit_city').val(response.city);
                $('#edit_state').val(response.state);
                $('#edit_country').val(response.country);
                $('#edit_zip_code').val(response.zip_code);
                $('#edit_shipping_address').val(response.shipping_address);
                $('#edit_is_active').val(response.is_active);
                $('#edit_is_default').val(response.is_default);
                $('#edit_is_active').val(response.is_active).trigger('change');

                if (response.type == 'individual') {
                    $('#edit-type-individual').val(response.type).prop('checked', true);
                    $('.individual').removeClass('d-none');
                    $('.business').addClass('d-none');

                } else {
                    $('#edit-type-business').val(response.type).prop('checked', true);
                    $('.individual').addClass('d-none');
                    $('.business').removeClass('d-none');
                }


                $('#edit-party').modal('show');
            }).fail(function(xhr) {
                alert('Error fetching party details: ' + xhr.responseText);
            });
        }

        function deleteParty(id) {
            $('#submit-party-destroy').data('id', id);
            $('#delete-party').modal('show');
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
Contacts

party_id

editParty
deleteBrand
party
Party 
--}}
