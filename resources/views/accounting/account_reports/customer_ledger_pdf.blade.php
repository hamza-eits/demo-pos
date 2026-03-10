<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Ledger</title>
    <style type="text/css">
        .style1 {
            font-size: 20px
        }

        body,
        td,
        th {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>

    <div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="2">
                    <div align="center" class="style1">{{ env('COMPANY_NAME') }} </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div align="center"><strong>Customer Ledger: {{ ($journals->isNotEmpty()) ? $journals[0]->customer->business_name :'' }}</strong></div>
                </td>
            </tr>
            <tr>
                <td width="50%">From {{ request()->startDate }} TO {{ request()->endDate }}</td>
                <td width="50%">
                    <div align="right">DATED: {{ date('d-m-Y') }}</div>
                </td>

            </tr>

        </table>
       
            <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
                <tbody>
                    <tr bgcolor="#CCCCCC">
                        <th width='10%' style="text-align:center;">DATE</th>
                        <th width='5%' style="text-align:center;">VHNO</th>
                        <th width='40%' style="text-align:center;">DESCRIPTION</th>
                        <th width='10%' style="text-align:right;">RECEIPTS</th>
                        <th width='10%' style="text-align:right;">PAYMENTS</th>
                        <th width='15%' style="text-align:center;">Balance</th>
                    </tr>
                </tbody>
               
                    <tbody>
                        @php
                            $bf = $broughtForward[0]->amount;
                        @endphp
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Balance Brought Forward</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right" >
                                @if ( $bf < 0 )
                                    <span style="color:red">{{ '('.number_format(abs($bf), 2).')'  }}</span>
                                @else
                                <span>{{ number_format($bf, 2) }}</span>
                                @endif
                            </td>
                           
                        </tr>
                        
                        @php
                            // Store brought forward value in a variable
                            $balance = $broughtForward[0]->amount;
                        @endphp
                        
                        @foreach ($journals as $journal)

                        @php
                            $invoiceDetails = \App\Models\Inventory\InvoiceDetail::where('invoice_master_id', $journal->invoice_master_id)
                                ->with('item') // Eager load the 'item' relationship
                                ->get();
                        @endphp

                       

                        <tr>
                            <td>{{ $journal->date }}</td>
                            <td>{{ $journal->voucher_no }}</td>
                            
                            <td>
                                {{-- if journal entry is of invoice --}}
                                @if($journal->type == "sale_order")
                                    <table style="border-collapse:collapse;" width="100%" border="0">

                                        <tr>
                                            <th width="20%" style="text-align:left">Item</th>
                                            <th width="20%" style="text-align:right">Qty.</th>
                                            <th width="20%" style="text-align:right">Rate</th>
                                            <th width="20%" style="text-align:right">Dis%</th>
                                            <th width="30%" style="text-align:right">Amount</th>
                                        </tr>
                                        @foreach ($invoiceDetails as  $detail)
                                            <tr style="border-top:1px dotted #CCCCCC;">
                                                <td width="20%" style="text-align:left" >{{ $detail->item->name }}</td>
                                                <td width="20%" style="text-align:right" >{{ number_format($detail->total_quantity,0)}}</td>
                                                <td width="20%" style="text-align:right" >{{ number_format($detail->per_unit_price,1) }}</td>
                                               
                                                <td width="20%" style="text-align:right" >{{ 
                                                    ($detail->discount_value ? (100-($detail->discount_value / $detail->per_unit_price * 100)) :'-' 
                                                
                                                )}}</td>
                                                <td width="30%" style="text-align:right" >{{ $detail->grand_total}}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @else
                                    {{ $journal->narration }}
                                @endif
                              
                            </td>
                            <td style="text-align:right;">{{ ($journal->debit) ? number_format($journal->debit, 2) : ''  }}</td>
                            <td style="text-align:right;">{{ ($journal->credit) ? number_format($journal->credit, 2) : ''  }}</td>
                        
                            <!-- Calculate the new balance -->
                            @php
                                // Update brought forward value by adding debit and subtracting credit
                                $balance += $journal->debit - $journal->credit;
                            @endphp
                        
                            <td style="text-align:right;">
                                    <!-- Check if balance is negative and format accordingly -->
                                    {{ 
                                        $balance < 0 
                                        ? '('.number_format(abs($balance), 2).')' 
                                        : number_format($balance, 2) 
                                    }}
                            </td>
                        </tr>
                        @endforeach
                        

                        <tr bgcolor="#CCCCCC" style="font-weight: bold">

                            <td colspan="2">TOTAL</td>
                            <td class="text-end"></td>
                            <td style="text-align:right;">{{ number_format($journals->sum('debit'), 2) }}</td>
                            <td style="text-align:right;"> {{ number_format($journals->sum('credit'), 2) }}</td>

                            <td style="text-align:right;">
                                {{ 
                                    $balance < 0 
                                    ? '('.number_format(abs($balance), 2).')' 
                                    : number_format($balance, 2) 
                                }} 
                            </td>
                        </tr>
                        
                    </tbody>
               

            </table>
            {{-- <p class="text-danger">No data found</p> --}}
        <p>&nbsp;</p>
    </div>
</body>

</html>



