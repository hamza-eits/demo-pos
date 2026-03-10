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
                    <div align="center"><strong>Customer Ledger {{ ($journals->isNotEmpty()) ? $journals[0]->chartOfAccount->name :'' }}</strong></div>
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
                        <th width='50' style="text-align:center;">DATE</th>
                        <th width='50' style="text-align:center;">VHNO</th>
                        <th width='200' style="text-align:center;">Narration</th>
                        <th width='75' style="text-align:right;">RECEIPTS</th>
                        <th width='75' style="text-align:right;">PAYMENTS</th>
                        <th width='75' style="text-align:right;">Balance</th>
                        <th width='75' style="text-align:center;">PARTY</th>
                        <th width='75' style="text-align:center;">SUPPLIER</th>
                    </tr>
                </tbody>
                @if($journals->isNotEmpty())
                    <tbody>
                        @php
                            $bf = $broughtForward[0]->amount;
                        @endphp
                        <tr>
                            <td></td>
                            <td></td>
                            <td>By Balance Brought Forward</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">
                                @if ( $bf < 0 )
                                    <span style="color:red">{{ '('.number_format(abs($bf), 2).')'  }}</span>
                                @else
                                <span>{{ number_format($bf, 2) }}</span>
                                @endif
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        
                        @php
                            // Store brought forward value in a variable
                            $balance = $broughtForward[0]->amount;
                        @endphp
                        
                        @foreach ($journals as $journal)
                        <tr>
                            <td>{{ $journal->date }}</td>
                            <td>{{ $journal->voucher_no }}</td>
                            <td>{{ $journal->narration }}</td>
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
                        
                            <td>{{ ($journal->customer_id) ? $journal->customer->business_name : '' }}</td>
                            <td>{{ ($journal->supplier_id) ? $journal->supplier->business_name : '' }}</td>
                        </tr>
                        @endforeach
                        

                        <tr class="table-active">

                            <td></td>
                            <td bgcolor="#CCCCCC">TOTAL</td>
                            <td bgcolor="#CCCCCC" class="text-end"></td>
                            <td style="text-align:right;" bgcolor="#CCCCCC">
                                {{ number_format($journals->sum('debit'), 2) }}
                            </td>
                            <td style="text-align:right;" bgcolor="#CCCCCC">
                            {{ number_format($journals->sum('credit'), 2) }}
                            </td>

                            <td style="text-align:right;" bgcolor="#CCCCCC">
                            
                                {{ 
                                    $balance < 0 
                                    ? '('.number_format(abs($balance), 2).')' 
                                    : number_format($balance, 2) 
                                }}
                                
                            </td>
                            <td ></td>
                            <td ></td>
                        </tr>
                        
                    </tbody>
                @else    
                    <tr>
                        <td colspan="8" style="text-align: center; color:red"><b>No Data Found</b></td>
                    </tr>
                @endif    

            </table>
            {{-- <p class="text-danger">No data found</p> --}}
        <p>&nbsp;</p>
    </div>
</body>

</html>



