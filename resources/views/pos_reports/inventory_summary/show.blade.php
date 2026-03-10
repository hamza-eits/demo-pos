@extends('template.tmp')
@section('title', 'Inventory Summary Report')

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
                    <h3 class="mb-sm-0 font-size-18">Inventory Summary Report</h3>
                    <p>
                        <b>Till:</b> {{ date('d-m-Y', strtotime($endDate)) }}
                        <b>From:</b> {{ date('d-m-Y', strtotime($startDate)) }}
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
                                        <th width="5%">#</th>
                                        <th width="10%">Product</th>
                                        <th width="10%">Description</th>
                                        {{-- <th width="10%">Material Type</th>
                                        <th width="10%">Model</th>
                                        <th width="10%">Custom Field</th> --}}
                                        <th width="10%">Category</th>
                                        {{-- <th width="10%">Brand</th> --}}
                                        <th width="10%">Variation</th>
                                        <th width="10%" class="text-end">{{ __('file.Qty') }} Purchase</th>
                                        <th width="10%" class="text-end">{{ __('file.Qty') }} Sold</th>
                                        <th width="10%" class="text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                    <tr>
                                        <td class="text-start">{{ $loop->iteration }}</td>
                                        <td>{{ $row['product'] }}</td>
                                        <td>{{ $row['description'] }}</td>
                                        {{-- <td>{{ $row['material'] }}</td> --}}
                                        {{-- <td>{{ $row['productModel'] }}</td> --}}
                                        {{-- <td>{{ $row['customField'] }}</td> --}}
                                        <td>{{ $row['category'] }}</td>
                                        {{-- <td>{{ $row['brand'] }}</td> --}}
                                        <td>{{ $row['variation'] }}</td>
                                        <td class="text-end">{{ $row['purchaseQty'] }}</td>
                                        <td class="text-end">{{ $row['soldQty'] }}</td>
                                        <td class="text-end">{{ $row['balance'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
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
