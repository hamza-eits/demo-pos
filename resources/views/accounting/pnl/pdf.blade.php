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

    <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;" height="100%">
        <tr>
            <!-- Left Table -->
            <td width="50%" valign="top">
                <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
                    <thead>
                        <tr bgcolor="#CCCCCC">
                            <th colspan="5">BALANCE SHEET</th>
                        </tr>
                        <tr bgcolor="#CCCCCC">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>account</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php    
                            $leftTotal = 0;  
                        @endphp  
    
                        <tr>
                            <td style="font-weight:bold">{{ $leftLoop['first'] }}</td>
                            <td colspan="4"></td>
                        </tr>
    
                        @foreach($leftLoop['second'] as $secondLevel)
                            <tr>
                                <td></td>
                                <td style="font-weight:bold">{{ $secondLevel['second'] }}</td>
                                <td colspan="3"></td>
                            </tr>
    
                            @foreach($secondLevel['third'] as $thirdLevel)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight:bold">{{ $thirdLevel['third'] }}</td>
                                    <td colspan="2"></td>
                                </tr>
    
                                @foreach($thirdLevel['fourth'] as $fourthLevel)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $fourthLevel['name'] }}</td>
                                        <td>{{ number_format($fourthLevel['total_debit'] - $fourthLevel['total_credit'], 2) }}</td>
                                    </tr>
    
                                    @php    
                                        $leftTotal += $fourthLevel['total_debit'] - $fourthLevel['total_credit']   
                                    @endphp 
                                @endforeach
                            @endforeach
                        @endforeach
    
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight:bold">Total</td>
                            <td style="font-weight:bold">{{ number_format($leftTotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
    
            <!-- Right Table -->
            <td width="50%" valign="top">
                <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
                    <thead>
                        <tr bgcolor="#CCCCCC">
                            <th colspan="5">BALANCE SHEET</th>
                        </tr>
                        <tr bgcolor="#CCCCCC">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>account</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php    
                            $rightTotal = 0;  
                        @endphp  
    
                        @foreach($rightLoop as $firstLervel)
                            <tr>
                                <td style="font-weight:bold">{{ $firstLervel['first'] }}</td>
                                <td colspan="4"></td>
                            </tr>
    
                            @foreach($firstLervel['second'] as $secondLevel)
                                <tr>
                                    <td></td>
                                    <td style="font-weight:bold">{{ $secondLevel['second'] }}</td>
                                    <td colspan="3"></td>
                                </tr>
    
                                @foreach($secondLevel['third'] as $thirdLevel)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td style="font-weight:bold">{{ $thirdLevel['third'] }}</td>
                                        <td colspan="2"></td>
                                    </tr>
    
                                    @foreach($thirdLevel['fourth'] as $fourthLevel)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $fourthLevel['name'] }}</td>
                                            <td>{{ number_format($fourthLevel['total_credit'] - $fourthLevel['total_debit'], 2) }}</td>
                                        </tr>
    
                                        @php    
                                            $rightTotal += $fourthLevel['total_credit'] - $fourthLevel['total_debit']   
                                        @endphp 
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
    
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="font-weight:bold">Profit</td>
                            <td></td>
                            <td style="font-weight:bold">{{ number_format($profit, 2) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight:bold">Total</td>
                            <td style="font-weight:bold">{{ number_format($rightTotal + $profit, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    

    

    




    
   

    
</body>
</html>
