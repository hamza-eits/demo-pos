@extends('template.tmp')
@section('title', __('file.Tax') . ' Summary Report')

@section('content')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    .dataTables_filter {
        display: none; /* Hide default search */
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h3 class="mb-sm-0 font-size-18">{{ __('file.Tax') }} Summary Report</h3>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table" class="table table-striped table-sm display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th class="text-end">{{ __('file.Quantity') }}</th>
                                        <th class="text-end">Cost</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Tax(input)</th>
                                        <th class="text-end">Status</th>
                                        <th class="text-end">Shipping</th>
                                        <th class="text-end">Discount</th>
                                        <th class="text-end">Grand Total</th>
                                        <th class="text-end">Received</th>
                                        <th class="text-end">Due</th>
                                        <th class="text-end">Sell Price</th>
                                        <th class="text-end">Sell Price Total</th>
                                        <th class="text-start">Tax (output)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $value)
                                    <tr>
                                        <td class="text-start">{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $value->invoice_no }}</td>
                                        <td class="text-start">{{ date('d-m-Y', strtotime($value->date)) }}</td>
                                        <td class="text-start">{{ $value->party->business_name ?? 'N/A' }}</td>
                                        <td class="text-end">{{ $value->total_quantity }}</td>
                                        <td class="text-end">{{ $value->total_cost_amount }}</td>
                                        <td class="text-end">{{ $value->subtotal_before_discount }}</td>
                                        <td class="text-end">{{ $value->tax_type == 'inclusive' ? $value->tax_amount : '-' }}</td>
                                        <td class="text-end">{{ $value->status }}</td>
                                        <td class="text-end">{{ $value->shipping_fee }}</td>
                                        <td class="text-end">{{ $value->discount_amount }}</td>
                                        <td class="text-end">{{ $value->grand_total }}</td>
                                        <td class="text-end">{{ $value->grand_total }}</td>
                                        <td>0</td>
                                        <td></td>
                                        <td class="text-end">{{ $value->grand_total }}</td>
                                        <td class="text-end">{{ $value->tax_type == 'exclusive' ? $value->tax_amount : '-' }}</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end"><strong>{{ $data->sum('total_quantity') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('total_cost_amount') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('subtotal_before_discount') }}</strong></td>
                                        <td class="text-end"><strong>{{$taxInput  }}</strong></td>
                                        <td></td>
                                        <td class="text-end"><strong>{{ $data->sum('shipping') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('discount_amount') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('grand_total') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('grand_total') }}</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center"><strong>{{$taxOutput  }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables & Export Buttons Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> <!-- JSZip first -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script> <!-- Then buttons.html5 -->

<script>
$(document).ready(function() {
    $('#table').DataTable({
        paging: false,
        searching: false,
        ordering: true,
        info: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Tax_Summary_Report',
                text: 'Export to Excel',
                className: 'btn btn-success btn-sm'
            }
        ],
        scrollX: true
    });
});
</script>

@endsection
