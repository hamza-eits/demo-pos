@extends('template.tmp')
@section('title', 'Tax Report')

@section('content')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid ">
                <div class="row">
                    <x-stat-card title="Sales Tax" :value="$si" icon="bx bxs-book-bookmark" />
                    <x-stat-card title="Purchases Tax " :value="$pi" icon="bx bxs-book-bookmark" />
                    <x-stat-card title="Expenses Tax" :value="$exp" icon="bx bxs-book-bookmark" />
                    <x-stat-card title="Tax Payable" :value="$taxPayable" icon="bx bxs-book-bookmark" />

                </div>
                <div class="row">
                    <div class="card-title">Sale Invoices</div>
                    <div class="card">
                        <table id="table" class="table table-sm">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Subtotal</th>
                                    <th>tax</th>
                                    <th>Grand Total</th>
                                    <th>Tax Filing</th>
                                    
    
                                </tr>
                            </thead>
    
                            <tbody>
                                @foreach ($saleInvoices as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                        <td>
                                            <a 
                                                href="{{ route('sale-invoice.show',$row->id) }}"
                                                target="_blank"
                                                
                                                >{{ $row->invoice_no }}
                                            </a>
                                        </td>
                                        <td>{{ $row->customer->business_name ?? 'N/A'}}</td>
                                        <td>{{ $row->total_net_amount }}</td>
                                        <td>{{ $row->total_tax_amount }}</td>
                                        <td>{{ $row->grand_total }}</td>

                                        <td>
                                            @if($row->is_tax_filed == 0)
                                            <span class="text-danger">unfiled</span>
                                            @else
                                            <a href="{{ route('tax-filing.show',$row->tax_filing_id) }}">
                                                <span class="text-success">filed</span>
                                            </a>

                                            @endif
                                           
                                        </td>
    
                                    </tr>
                                @endforeach
    
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th>{{ $saleInvoices->sum('total_net_amount') }}</th>
                                <th>{{ $saleInvoices->sum('total_tax_amount') }}</th>
                                <th>{{ $saleInvoices->sum('grand_total') }}</th>
                                <th></th>
                                
                            </tfoot>
                            
                        </table>
                    </div>
                </div>



                <div class="row">
                    <div class="card-title">Purchase Invoices</div>
                    <div class="card">
                        <table id="table" class="table table-sm">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Invoice No</th>
                                    <th>Supplier</th>
                                    <th>Subtotal</th>
                                    <th>tax</th>
                                    <th>Grand Total</th>
                                    <th>Tax Filing</th>

    
                                </tr>
                            </thead>
    
                            <tbody>
                                @foreach ($purchaseInvoices as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                        <td>
                                            <a 
                                                href="{{ route('purchase-invoice.show',$row->id) }}"
                                                target="_blank"
                                                
                                                >{{ $row->invoice_no }}
                                            </a>
                                        </td>
                                        <td>{{ $row->supplier->business_name ?? 'N/A'}}</td>
                                        <td>{{ $row->total_net_amount }}</td>
                                        <td>{{ $row->total_tax_amount }}</td>
                                        <td>{{ $row->grand_total }}</td>
                                        <td>
                                            @if($row->is_tax_filed == 0)
                                            <span class="text-danger">unfiled</span>
                                            @else
                                            <a href="{{ route('tax-filing.show',$row->tax_filing_id) }}">
                                                <span class="text-success">filed</span>
                                            </a>

                                            @endif
                                           
                                        </td>
    
                                    </tr>
                                @endforeach
    
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th>{{ $purchaseInvoices->sum('total_net_amount') }}</th>
                                <th>{{ $purchaseInvoices->sum('total_tax_amount') }}</th>
                                <th>{{ $purchaseInvoices->sum('grand_total') }}</th>
                                <th></th>
                                
                            </tfoot>
                            
                        </table>
                    </div>
                </div>

               
                
                <div class="row">
                    <div class="card-title">Expenses</div>
                    <div class="card">
                        <table id="table" class="table table-sm">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Expnse No</th>
                                    <th>Supplier</th>
                                    <th>Subtotal</th>
                                    <th>tax</th>
                                    <th>Grand Total</th>
                                    <th>Tax Filing</th>
    
                                </tr>
                            </thead>
    
                            <tbody>
                                @foreach ($expenses as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                        <td>
                                            <a 
                                                href="{{ route('expense.show',$row->id) }}"
                                                target="_blank"
                                                
                                                >{{ $row->expense_no }}
                                            </a>
                                        </td>
                                        <td>{{ $row->party->business_name ?? 'N\A' }}</td>
                                        <td>{{ $row->amount_exclusive_tax }}</td>
                                        <td>{{ $row->calculated_tax_amount }}</td>
                                        <td>{{ $row->amount_inclusive_tax }}</td>
                                        <td>
                                            @if($row->is_tax_filed == 0)
                                            <span class="text-danger">unfiled</span>
                                            @else
                                            <a href="{{ route('tax-filing.show',$row->tax_filing_id) }}">
                                                <span class="text-success">filed</span>
                                            </a>

                                            @endif
                                           
                                        </td>
    
                                    </tr>
                                @endforeach
    
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th>{{ $expenses->sum('amount_exclusive_tax') }}</th>
                                <th>{{ $expenses->sum('calculated_tax_amount') }}</th>
                                <th>{{ $expenses->sum('amount_inclusive_tax') }}</th>
                                <th></th>
                                
                            </tfoot>
                            
                        </table>
                    </div>
                </div>
                


            </div>
        </div>
    </div>
    </div>

   

@endsection
