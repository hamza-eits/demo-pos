<!DOCTYPE html>

{{-- This is sa detail view of the balance sheet. 
It shows the account name, total debit, total credit, and balance.
It also shows the total debit, total credit, and balance
 for each level of the account. 
The total debit, total credit, and balance for each level 
are calculated by summing the total debit, total credit, 
and balance of all the accounts under that level. --}}
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet</title>
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
</head>
<body>

    <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <tr bgcolor="#CCCCCC">
                <th colspan="5">BALANCE SHEET</th>
            </tr>
            <tr bgcolor="#CCCCCC">
                <th>Levels</th>
                <th>Account</th>
                
                <th>Total Debit</th>
                <th>Total Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $firstLevel)
                <tr>
                    <td  style="font-weight: bold;">{{ $firstLevel['first'] }}</td>
                    <td colspan="4"></td>
                </tr>

                @foreach($firstLevel['second'] as $secondLevel)
                    <tr>
                        <td style="padding-left: 20px; font-weight: bold;">{{ $secondLevel['second'] }}</td>
                        <td colspan="4"></td>
                    </tr>

                    @foreach($secondLevel['third'] as $thirdLevel)
                        <tr>
                            <td style="padding-left: 40px; font-weight: bold;text-align:right">{{ $thirdLevel['third'] }}</td>
                            <td colspan="4"></td>
                        </tr>

                        @foreach($thirdLevel['fourth'] as $fourthLevel)
                            <tr >
                                <td></td>
                                <td style="">{{ $fourthLevel['name'] }}</td>
                                <td  style="text-align: right;">{{ number_format($fourthLevel['total_debit'], 2) }}</td>
                                <td  style="text-align: right;">{{ number_format($fourthLevel['total_credit'], 2) }}</td>
                                <td  style="text-align: right;">{{ number_format($fourthLevel['balance'], 2) }}</td>
                            </tr>
                        @endforeach
                                
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
    <br>
    <hr>

    <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <tr bgcolor="#CCCCCC">
                <th colspan="4">SUPPLIER BALANCE</th>
            </tr>
            <tr bgcolor="#CCCCCC">
                <th width='20%' style="text-align:center;">NAME</th>
                <th width='20%' style="text-align:center;">DEBIT / PAYMENT </th>
                <th width='20%' style="text-align:right;">CREDIT / INVOICE </th>
                <th width='20%' style="text-align:right;">BALANCE</th>
                
            </tr>
        </thead>
        <tbody>     
          
                @foreach ($supplierJournals as $journal)
                    <tr>
                    
                        <td>{{ $journal->supplier->business_name ?? 'N/A' }}</td>
                        <td style="text-align:right;">{{ ($journal->total_debit) ? number_format($journal->total_debit, 2) : ''  }}</td>
                        <td style="text-align:right;">{{ ($journal->total_credit) ? number_format($journal->total_credit, 2) : ''  }}</td>
                        <td style="text-align:right;">{{  number_format($journal->total_debit - $journal->total_credit, 2)  }}</td>

                    </tr>
                @endforeach
        

                <tr  bgcolor="#CCCCCC">

                    <td colspan="1"><b>TOTAL</b></td>
                    <td style="text-align:right; font-weight:bold">
                        {{ number_format($supplierJournals->sum('total_debit'), 2) }}
                    </td>
                    <td style="text-align:right;font-weight:bold">
                        {{ number_format($supplierJournals->sum('total_credit'), 2) }}
                    </td>
                    <td style="text-align:right;font-weight:bold">
                        {{ number_format($supplierJournals->sum('total_debit') -  $supplierJournals->sum('total_credit'), 2) }}
                    </td>
                    
                </tr>
            
           
        </tbody>
    </table>
    <br>
    <hr>

    <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <tr bgcolor="#CCCCCC">
                <th colspan="4">CUSTOMER BALANCE</th>
            </tr>
            <tr bgcolor="#CCCCCC">
                <th width='20%' style="text-align:center;">NAME</th>
                <th width='20%' style="text-align:center;">DEBIT / PAYMENT </th>
                <th width='20%' style="text-align:right;">CREDIT / INVOICE </th>
                <th width='20%' style="text-align:right;">BALANCE</th>
                
            </tr>
        </thead>
        <tbody>     
          
                @foreach ($customerJournals as $journal)
                    <tr>
                    
                        <td>{{ $journal->customer->business_name ?? 'N/A' }}</td>
                        <td style="text-align:right;">{{ ($journal->total_debit) ? number_format($journal->total_debit, 2) : ''  }}</td>
                        <td style="text-align:right;">{{ ($journal->total_credit) ? number_format($journal->total_credit, 2) : ''  }}</td>
                        <td style="text-align:right;">{{  number_format($journal->total_debit - $journal->total_credit, 2)  }}</td>

                    </tr>
                @endforeach
        

                <tr  bgcolor="#CCCCCC">

                    <td colspan="1"><b>TOTAL</b></td>
                    <td style="text-align:right; font-weight:bold">
                        {{ number_format($customerJournals->sum('total_debit'), 2) }}
                    </td>
                    <td style="text-align:right;font-weight:bold">
                        {{ number_format($customerJournals->sum('total_credit'), 2) }}
                    </td>
                    <td style="text-align:right;font-weight:bold">
                        {{ number_format($customerJournals->sum('total_debit') -  $customerJournals->sum('total_credit'), 2) }}
                    </td>
                    
                </tr>
            
           
        </tbody>
    </table>
</body>
</html>
