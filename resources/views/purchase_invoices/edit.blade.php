@extends('template.tmp')
@section('title', 'Purchase Invoices')

@section('content')
    <style>
        /* Chrome, Safari, Edge, Opera : remove spin input type number*/
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;


        }

        .table>:not(caption)>*>* {
            padding: 0.15rem .15rem !important;
        }

        table tbody tr input.form-control {

            border-radius: 0rem !important;
            font-size: 11px;

        }

        #summary-table input.form-control {
            /* border: 0; */
            border-radius: 0.25rem !important;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #eff2f780 !important;
            opacity: 1;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <form id="purchase-invoice-edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="purchase-invoice-id" value="{{ $purchaseInvoice->id }}">
                    <div class="card">
                        <div class="card-body">
                                @include('purchase_invoices.partials.information', ['purchaseInvoice' => $purchaseInvoice])
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            @include('purchase_invoices.partials.detail_table', ['purchaseInvoice' => $purchaseInvoice])
                        </div>
                    </div>
                    <div class="row  mt-2">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-8 text-end">
                            <button type="submit" id="update-purchase-invoice-btn"
                                class="btn btn-success w-md">Save</button>
                            <a href="{{ route('purchase-invoice.index') }}" class="btn btn-secondary w-md ">Cancel</a>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('purchase_invoices.model')
    @include('purchase_invoices.js')

    <script>
        $(document).ready(function () {
            summaryCalculation();
        });
    </script>


    <script>
        $('#purchase-invoice-edit-form').on('submit', function(e) {
            e.preventDefault();
            var submit_btn = $('#update-purchase-invoice-btn');
            let id = $('#purchase-invoice-id').val(); // Get the ID of the brand being edited

            let editFormData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{ route('purchase-invoice.update', ':id') }}".replace(':id',
                    id), // Using route name
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
                        $('#purchase-invoice-edit-form')[0].reset(); // Reset all form data

                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });
                        // Redirect after success notification
                        setTimeout(function() {
                            window.location.href = '{{ route('purchase-invoice.index') }}';
                        }, 200);
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
    </script>
@endsection
