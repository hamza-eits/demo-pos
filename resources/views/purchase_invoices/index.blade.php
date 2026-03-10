@extends('template.tmp')
@section('title', 'All Purchase Invoices')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Purchase Invoices</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="{{ route('purchase-invoice.create') }}" class="btn btn-added btn-primary"><i
                                            class="bx bx-plus"></i> Purchase Invoice</a>
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
                                            <th>Invoice No</th>
                                            <th>Subject</th>
                                            <th>PO Number</th>
                                            <th>Description</th>
                                            <th>Supplier</th>
                                            <th>Credit Period</th>
                                            <th>Date</th>
                                            <th>Payment Mode</th>
                                            <th>Due Date</th>
                                            <th>Total</th>
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



    <!-- Delete Item -->
    <div class="modal fade" id="delete-purchase-invoice">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Delete Item</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>

                        <div class="modal-body custom-modal-body pt-3 pb-0">
                            <p class="text-center">Are you sure you want to delete this purchase-invoice?</p>
                        </div>
                        <div class="modal-footer-btn p-3 mt-2">
                            <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-submit shadow-sm btn-danger"
                                id="delete-purchase-invoice-btn">Delete</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Delete Item -->

    <!-- Post Item -->
    <div class="modal fade" id="post-purchase-invoice">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Post Purchase Invoice</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>

                        <div class="modal-body custom-modal-body pt-3 pb-0">
                            <p class="text-center">Are you sure you want to post this purchase invoice?</p>
                        </div>
                        <div class="modal-footer-btn p-3 mt-2">
                            <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-submit shadow-sm btn-success"
                                id="post-purchase-invoice-btn">Post</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Post Item -->





    <!-- END: Content-->
    <!-- JS for Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<!-- JSZip (required for Excel export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('purchase-invoice.index') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'invoice_no'
                    },
                    {
                        data: 'subject'
                    },
                    {
                        data: 'reference_no'
                    },
                    {
                        data: 'description',
                    },
                    {
                        data: 'supplier_name'
                    },
                    {
                        data: 'credit_period'
                    },
                    {
                        data: 'date',
                    },
                    {
                        data: 'payment_mode',
                    },
                    {
                        data: 'due_date'
                    },
                    {
                        data: 'grand_total'
                    },
                    {
                        data: 'status',
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
                dom: 'Bfrtip', // Add Buttons
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Expenses Export',
                        exportOptions: {
                            columns: ':not(:last-child)' // exclude 'action' column
                        }
                    }
                ]
            });



            $('#delete-purchase-invoice-btn').click(function() {
                let id = $(this).data('id') || 0;
                var submit_btn = $('#delete-purchase-invoice-btn');

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('purchase-invoice.destroy', ':id') }}".replace(':id',
                        id), // Using route name
                    data: {
                        _token: "{{ csrf_token() }}" // Add CSRF token
                    },
                    beforeSend: function() {
                        submit_btn.prop('disabled', true);
                        submit_btn.html('Processing');
                    },
                    success: function(response) {

                        submit_btn.prop('disabled', false).html('Delete Item');

                        if (response.success == true) {
                            $('#delete-purchase-invoice').modal('hide');
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
                        submit_btn.prop('disabled', false).html('Delete Item');

                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


        });


        function deletePurchaseInvoice(id) {
            $('#delete-purchase-invoice-btn').data('id', id);
            $('#delete-purchase-invoice').modal('show');
        }

        function posting(id) {
            $('#post-purchase-invoice-btn').data('id', id);
            $('#post-purchase-invoice').modal('show');
        }


        function lock(id) {
            if (confirm("Are you sure you want to lock this purchase invoice?")) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('purchase-invoice.lock', ':id') }}".replace(':id', id),
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success == true) {
                            notyf.success({
                                message: response.message,
                                duration: 3000
                            });
                            $('#table').DataTable().ajax.reload();
                        } else {
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
            }
        }
    </script>



@endsection

{{-- 
Items

item_id

editItem
deleteItem
purchase-invoice
Item 
--}}
