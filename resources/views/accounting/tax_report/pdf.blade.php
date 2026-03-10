<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Filing</title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <style type="text/css">

        .style1 {font-size: 20px}
        body,td,th {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .style2 {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>
   
      
    <table width="100%">
        <tr>
            <td colspan="2">
                <div align="center" class="style1">{{ env('COMPANY_NAME') }}</div>
            </td>
        </tr>
        
        
    </table>

    <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF"
        style="border-collapse:collapse;">
        <thead>
            <tr>
                <th colspan="8">Sale Invoices</th>
            </tr>
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
    </table>       
    <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF"
        style="border-collapse:collapse; margin-top:10px">
        <thead>
            <tr>
                <th colspan="8">Purchase Invoices</th>
            </tr>
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
        @foreach ($purchaseInvoices as $row)
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
    </table>  

    <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF"
        style="border-collapse:collapse; margin-top:10px">
        <thead>
            <tr>
                <th colspan="8">Expenses</th>
            </tr>
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
                <td>{{ $row->party->business_name ?? 'N/A'}}</td>
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
    </table>       


               
                
</body>

</html>
