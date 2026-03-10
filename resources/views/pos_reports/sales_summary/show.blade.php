@extends('template.tmp')
@section('title', 'Sales Summary Report')

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
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h3 class="mb-sm-0 font-size-18">Sales Summary Report</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table" class="table table-striped table-sm display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Method</th>
                                        <th>Seller Name</th>
                                        <th class="text-end">Cost</th>
                                        <th class="text-end">Sold</th>
                                        <th class="text-end">{{ __('file.Quantity') }}</th>
                                        <th class="text-end">Item Total</th>
                                        <th class="text-end">Invoice Total</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                    <tr>
                                        <td class="text-start">{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $row->invoice_no }}</td>
                                        <td class="text-start">{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                        <td class="text-start">{{ $row->party->business_name ?? 'N/A' }}</td>
                                        <td class="text-start">{{ $row->payments[0]->mode ?? 'N/A' }}</td>
                                        <td class="text-start">{{ $row->biller->name ?? 'N/A' }}</td>
                                        <td class="text-end">{{ $row->total_cost_amount }}</td>
                                        <td class="text-end">{{ $row->subtotal_after_discount }}</td>
                                        <td class="text-end">{{ $row->total_quantity }}</td>
                                        <td class="text-end">{{ $row->subtotal_before_discount }}</td>
                                        <td class="text-end">{{ $row->grand_total }}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end"><strong>{{ $data->sum('total_cost_amount') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('subtotal_after_discount') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('total_quantity') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('subtotal_before_discount') }}</strong></td>
                                        <td class="text-end"><strong>{{ $data->sum('grand_total') }}</strong></td>
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

<!-- DataTables and Buttons JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(document).ready(function() {
    $('#table').DataTable({
        paging: false,           // Disable pagination
        searching: false,        // Disable search box
        ordering: true,          // Optional: keep column sorting
        info: false,             // Hide table info
        dom: 'Bfrtip',           // Enable buttons
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Sales_Summary_Report',
                text: 'Export to Excel',
                className: 'btn btn-success btn-sm'
            }
        ],
        scrollX: true // Optional: horizontal scroll for better UX with many columns
    });
});
</script>
@endsection
