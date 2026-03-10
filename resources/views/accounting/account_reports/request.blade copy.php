@extends('tmp')

@section('title', 'Kohisar')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Voucher</h4>
                        </div>
                    </div>
                </div>

                <form action="" method="post" name="form" id="form">
                    <div class="card">
                        <div class="card-body">
                        <!-- enctype="multipart/form-data" -->
                            @csrf

                            <div class="row">
                             
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <div class="mb-1">
                                            <label >Chart of Accounts</label>
                                            <select name="coa_id" id="coa-id" class="select2 form-control" style="width: 100%">
                                                <option value="0">All</option>
                                                @foreach ($chartOfAccounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->id.'-'.$account->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-1">
                                            <label >Voucher Types</label>
                                            <select name="voucher_type_code" class="select2 form-control" style="width: 100%">
                                                <option value="0">All</option>
                                                @foreach ($voucherTypes as $type)
                                                    <option value="{{ $type->code }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
        
                                    <div class="col-md-12">
                                        <div class="mb-1">
                                            <label >Current Assets Accounts</label>
                                            <select name="current_coa_id" id="current-coa-id" class="select2 form-control">
                                                <option value="0">All</option>
                                                @foreach ($currentAssetAccounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->id.'-'.$account->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-1">
                                            <label >Top Level Accounts</label>
                                            <select name="top_level_coa_id" id="top-level-coa-id" class="select2 form-control">
                                                <option value="0">All</option>
                                                @foreach ($topLevelAccounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->id.'-'.$account->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <div class="mt-3 mb-1">
                                            <label >Select Date Range</label>
                                            <!-- Date Range Selector -->
        
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle btn-block" type="button"
                                                    id="dateRangeDropdown" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Select Date Range <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dateRangeDropdown">
                                                    <a class="dropdown-item" href="#" data-range="today">Today</a>
                                                    <a class="dropdown-item" href="#" data-range="this_week">This Week</a>
                                                    <a class="dropdown-item" href="#" data-range="this_month">This Month</a>
                                                    <a class="dropdown-item" href="#" data-range="this_quarter">This
                                                        Quarter</a>
                                                    <a class="dropdown-item" href="#" data-range="this_year">This Year</a>
                                                    <a class="dropdown-item" href="#" data-range="ytd">Year to Date</a>
                                                    <a class="dropdown-item" href="#" data-range="yesterday">Yesterday</a>
                                                    <a class="dropdown-item" href="#" data-range="previous_week">Previous
                                                        Week</a>
                                                    <a class="dropdown-item" href="#" data-range="previous_month">Previous
                                                        Month</a>
                                                    <a class="dropdown-item" href="#" data-range="previous_quarter">Previous
                                                        Quarter</a>
                                                    <a class="dropdown-item" href="#" data-range="previous_year">Previous
                                                        Year</a>
                                                    <a class="dropdown-item" href="#" data-range="custom">Custom Range</a>
                                                </div>
                                            </div>
        
                                            <!-- Date inputs -->
                                        </div>
                                    </div>
        
        
        
                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <div class="form-group">
                                                <label for="StartDate">Start Date</label>
                                                <input type="date" name="startDate" id="StartDate" class="form-control" required>
                                                <div id="start"></div>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label for="EndDate">End Date</label>
                                                <input type="date" name="endDate" id="EndDate" class="form-control" required>
                                                <div id="end"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <div class="mb-1">
                                            <label >Customers</label>
                                            <select name="customer_id" id="customer-id" class="select2 form-control" style="width: 100%">
                                                <option value="0">All</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->business_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-1">
                                            <label >Debitor / Creditor</label>
                                            <select name="balance_report_type" class="select2 form-control" style="width: 100%">
                                                <option selected value="debitor">Debitor</option>
                                                <option value="creditor">Creditor</option>
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-1">
                                            <label >Suppliers</label>
                                            <select name="supplier_id" id="supplier-id" class="select2 form-control" style="width: 100%">
                                                <option value="0">All</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->business_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                        
                                    
                                </div>
                                
                            </div> 
                           
                        </div>
                        <!-- Submit buttons -->
                        <div class="card-footer bg-light">
                          
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="voucher-pdf">Voucher PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="cashbook-pdf">Cashbook PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="gernal-ledger-pdf">Gernal Ledger PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="daybook-pdf">Daybook PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="trail-balance-pdf">Trail Balance PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="customer-balance-pdf">Customer Balance PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="supplier-balance-pdf">Supplier Balance PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="expense-pdf">Expense PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="customer-ledger-pdf">Customer Ledger PDF</button>
                            <button type="submit" class="btn btn-success w-lg float-right mt-2" id="supplier-ledger-pdf">Supplier Ledger PDF</button>
                            <a href="{{ URL('/') }}" class="btn btn-secondary w-lg float-right mt-2">Cancel</a>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#voucher-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.voucherPDF') }}');
                $('#form').submit();
            });
            $('#cashbook-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.cashbookPDF') }}');
                $('#form').submit();
            });
            $('#gernal-ledger-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.gernalLedgerPDF') }}');
                $('#form').submit();
            });
            $('#daybook-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.daybookPDF') }}');
                $('#form').submit();
            });
            $('#trail-balance-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.trialBalancePDF') }}');
                $('#form').submit();
            });
            $('#customer-balance-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.customerBalancePDF') }}');
                $('#form').submit();
            });
            $('#supplier-balance-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.supplierBalancePDF') }}');
                $('#form').submit();
            });
            $('#expense-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.expensePDF') }}');
                $('#form').submit();
            });
            $('#customer-ledger-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.customerLedgerPDF') }}');
                $('#form').submit();
            });
            $('#supplier-ledger-pdf').click(function(e) {
                e.preventDefault();
                $('#form').removeAttr('target');
                $('#form').attr('action', '{{ route('account-reports.supplierLedgerPDF') }}');
                $('#form').submit();
            });
            
        });
    </script>

    <script>
        $(document).ready(function() {
            const dateRangeDisplay = $('#selectedRange');
            const startDateInput = $('#StartDate');
            const endDateInput = $('#EndDate');

            // Function to display selected range
            function setDateRange(start, end) {
                dateRangeDisplay.text(`${start.format('YYYY-MM-DD')} to ${end.format('YYYY-MM-DD')}`);
                startDateInput.val(start.format('YYYY-MM-DD'));
                endDateInput.val(end.format('YYYY-MM-DD'));
            }

            // Handle predefined date ranges
            $('.dropdown-menu a').click(function() {
                let range = $(this).data('range');
                let start, end;

                switch (range) {
                    case 'today':
                        start = end = moment();
                        break;
                    case 'this_week':
                        start = moment().startOf('week');
                        end = moment().endOf('week');
                        break;
                    case 'this_month':
                        start = moment().startOf('month');
                        end = moment().endOf('month');
                        break;
                    case 'this_quarter':
                        start = moment().startOf('quarter');
                        end = moment().endOf('quarter');
                        break;
                    case 'this_year':
                        start = moment().startOf('year');
                        end = moment().endOf('year');
                        break;
                    case 'ytd':
                        start = moment().startOf('year');
                        end = moment();
                        break;
                    case 'yesterday':
                        start = end = moment().subtract(1, 'days');
                        break;
                    case 'previous_week':
                        start = moment().subtract(1, 'weeks').startOf('week');
                        end = moment().subtract(1, 'weeks').endOf('week');
                        break;
                    case 'previous_month':
                        start = moment().subtract(1, 'months').startOf('month');
                        end = moment().subtract(1, 'months').endOf('month');
                        break;
                    case 'previous_quarter':
                        start = moment().subtract(1, 'quarters').startOf('quarter');
                        end = moment().subtract(1, 'quarters').endOf('quarter');
                        break;
                    case 'previous_year':
                        start = moment().subtract(1, 'years').startOf('year');
                        end = moment().subtract(1, 'years').endOf('year');
                        break;
                    case 'custom':
                        $('#customDateRange').daterangepicker({
                            opens: 'right',
                            locale: {
                                format: 'YYYY-MM-DD'
                            }
                        }, function(start, end) {
                            setDateRange(start, end);
                        });
                        return; // Exit here to prevent predefined ranges logic
                }

                setDateRange(start, end);
            });

            // Initialize custom range datepicker
            $('#customDateRange').daterangepicker({
                opens: 'right',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

@endsection
