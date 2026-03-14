@extends('template.tmp')
@section('title', 'Biller Wise Sales Report')

@section('content')
<style>
    .dataTables_filter { display: none; }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h3 class="mb-sm-0 font-size-18">Biller Wise Sales Report</h3>
                    <p>
                        <b>Biller:</b> {{ $billerName }} &nbsp;|&nbsp;
                        <b>From:</b> {{ date('d-m-Y', strtotime($startDate)) }} -
                        <b>To:</b> {{ date('d-m-Y', strtotime($endDate)) }}
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="table" class="table table-striped table-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Item No</th>
                                        <th>Item Name</th>
                                        <th>Date</th>
                                        <th>Unit Price</th>
                                        <th>{{ __('file.Quantity') }}</th>
                                        <th>Subtotal</th>
                                        <th>Discount</th>
                                        <th>Grand Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->invoice_no }}</td>
                                            <td>{{ $value->variation_barcode }}</td>
                                            <td>{{ $value->product_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($value->date)) }}</td>
                                            <td>{{ $value->unit_price }}</td>
                                            <td>{{ $value->quantity }}</td>
                                            <td>{{ $value->subtotal }}</td>
                                            <td>{{ $value->discount_amount > 0 ? $value->discount_amount : '-' }}</td>
                                            <td>{{ $value->grand_total }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No sale data found for this biller in the selected date range</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    @if($data->count() > 0)
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-start"><strong>Total:</strong></th>
                                        <th></th>
                                        <th class="text-start">{{ $data->sum('quantity') }}</th>
                                        <th class="text-start">{{ $data->sum('subtotal') }}</th>
                                        <th class="text-start">{{ $data->sum('discount_amount') }}</th>
                                        <th class="text-start">{{ $data->sum('grand_total') }}</th>
                                    </tr>
                                    @endif
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function() {
    $('#table').DataTable({
        fixedHeader: true,
        paging: false,
        searching: false,
        ordering: true,
        info: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Biller_Wise_Sales_Report',
                text: 'Export to Excel',
                className: 'btn btn-success btn-sm'
            }
        ],
        scrollX: true
    });
});
</script>
@endsection