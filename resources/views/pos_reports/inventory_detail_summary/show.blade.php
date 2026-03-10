@extends('template.tmp')
@section('title', 'Inventory Detail Summary Report')

@section('content')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    .dataTables_filter {
        display: none; /* Hide default search box */
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h3 class="mb-sm-0 font-size-18">Inventory Detail Summary Report</h3>
                    <p>
                        <b>Till:</b> {{ date('d-m-Y', strtotime($endDate)) }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table" class="table table-striped table-sm display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Material Type</th>
                                        <th>Model</th>
                                        <th>Custom Field</th>
                                        <th>PO Number</th>
                                        <th>Cost Level</th>
                                        <th>PO Date</th>
                                        <th class="text-end">Aging</th>
                                        <th class="text-end">Unit</th>
                                        <th class="text-end">Cost</th>
                                        <th class="text-end">Total Cost</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total Price</th>
                                        <th class="text-end">Qty In</th>
                                        <th class="text-end">Qty Out</th>
                                        <th class="text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                        <tr>
                                            <td>{{ $row['name'] }}</td>
                                            <td>{{ $row['description'] }}</td>
                                            <td>{{ $row['category'] }}</td>
                                            <td>{{ $row['materialType'] }}</td>
                                            <td>{{ $row['model'] }}</td>
                                            <td>{{ $row['customField'] }}</td>
                                            <td>{{ $row['poNumber'] }}</td>
                                            <td>{{ $row['costlevel'] }}</td>
                                            <td class="text-start">{{ date('d-m-Y', strtotime($row['poDate'])) }}</td>
                                            <td class="text-end">{{ $row['aging'] }}</td>
                                            <td class="text-end">{{ $row['unit'] }}</td>
                                            <td class="text-end">{{ $row['cost'] }}</td>
                                            <td class="text-end">{{ $row['totalCost'] }}</td>
                                            <td class="text-end">{{ $row['price'] }}</td>
                                            <td class="text-end">{{ $row['totalPrice'] }}</td>
                                            <td class="text-end">{{ $row['qtyIn'] }}</td>
                                            <td class="text-end">{{ $row['qtyOut'] }}</td>
                                            <td class="text-end">{{ $row['balance'] }}</td>
                                        </tr>


                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9" class="text-end">Total</th>
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{ number_format($totalCost, 2) }}</th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{ number_format($totalPrice, 2) }}</th>
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
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

<!-- DataTables and Export Scripts -->
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
        dom: 'Bfrtip',           // Buttons on top
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Inventory_Summary_Report',
                text: 'Export to Excel',
                className: 'btn btn-success btn-sm'
            }
        ],
        scrollX: true // Optional for better layout with many columns
    });
});


</script>

@endsection
