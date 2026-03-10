@extends('template.tmp')
@section('title', 'Payment Source Summary Report')

@section('content')
<style>
    /* Hide the default search box */
.dataTables_filter {
    display: none;
}
</style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">Payment Source Summary Report</h3>



                        </div>
                    </div>
                </div>
                  
                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="mini-stats-wid card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Total Sales ({{ $countInvoiceRecords }})</p>
                                        <h4 class="mb-0">{{ number_format($cashSale + $cardSale + $bankSale, 2) }}</h4>
                                    </div>
                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                        <span class="avatar-title rounded-circle bg-primary">
                                            <i class="bx bx-trending-up font-size-24"></i> <!-- Equalizer icon -->
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mini-stats-wid card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Cash ({{ $countCashRecords }})</p>
                                        <h4 class="mb-0">{{ $cashSale }}</h4>
                                    </div>
                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                        <span class="avatar-title rounded-circle bg-primary">
                                            <i class="bx bx-money  font-size-24"></i> <!-- Package icon -->
                                        </span>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mini-stats-wid card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Card ({{ $countCardRecords }})</p>
                                        <h4 class="mb-0">{{ number_format($cardSale, 2) }}</h4>
                                    </div>
                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                        <span class="avatar-title rounded-circle bg-primary">
                                            <i class="bx bx-credit-card font-size-24"></i> <!-- Shopping cart icon -->
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mini-stats-wid card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Bank ({{ $countBankRecords }})</p>
                                        <h4 class="mb-0">{{ number_format($bankSale, 2) }}</h4>
                                    </div>
                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                        <span class="avatar-title rounded-circle bg-primary">
                                            <i class="bx bx-building  font-size-24"></i> <!-- Factory icon -->
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified col-md-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab"  id="invoice-list-tab" role="tab" aria-selected="true">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">All</span> 
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" id="cash-sale-tab" role="tab" aria-selected="false" tabindex="-1">
                                    
                                    <span class="d-none d-sm-block">Cash Sale</span> 
                                </a>  
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" id="bank-sale-tab" role="tab" aria-selected="false" tabindex="-1">
                                    
                                    <span class="d-none d-sm-block">Bank Sale</span> 
                                </a>  
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" id="card-sale-tab" role="tab" aria-selected="false" tabindex="-1">
                                    
                                    <span class="d-none d-sm-block">Card Sale</span> 
                                </a>  
                            </li>
                            
                           
                        </ul>
                        <div class="card">
                            <div class="card-body">
                                <table id="table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Invoice No</th>
                                            <th>Customer</th>
                                            <th>Biller</th>
                                            <th>No. Payments</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                
                                    @include('pos_reports.payment_source_summary.partials.invoice_list')

                                    
                                    @include('pos_reports.payment_source_summary.partials.cash_invoice_list')

                                    @include('pos_reports.payment_source_summary.partials.card_invoice_list')
                                    
                                    @include('pos_reports.payment_source_summary.partials.bank_invoice_list')
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

  
    <script>
        $(document).ready(function () {
            $('#invoice-list-tab').trigger('shown.bs.tab');
        });
   
        $('#cash-sale-tab').on('shown.bs.tab', function () {
           $('tbody').addClass('d-none');
           $('#cash-invoice-list').removeClass('d-none');
        });

        $('#card-sale-tab').on('shown.bs.tab', function () {
            $('tbody').addClass('d-none');
            $('#card-invoice-list').removeClass('d-none');
        });

        $('#bank-sale-tab').on('shown.bs.tab', function () {
            $('tbody').addClass('d-none');
            $('#bank-invoice-list').removeClass('d-none');
        });

        

        $('#invoice-list-tab').on('shown.bs.tab', function () {
            $('tbody').addClass('d-none');
            $('#invoice-list').removeClass('d-none');
        });

    </script>
  
@endsection
