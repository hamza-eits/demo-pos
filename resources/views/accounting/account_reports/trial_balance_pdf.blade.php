<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial Balance</title>
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
                    <div align="center"><strong>Trail Balance</strong></div>
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
                <thead>
                    <tr bgcolor="#CCCCCC">
                        <th width='50' style="text-align:center;">CODE</th>
                        <th width='50' style="text-align:center;">TYPE</th>
                        <th width='200' style="text-align:center;">NAME</th>
                        <th width='100' style="text-align:center;">DEBIT</th>
                        <th width='100' style="text-align:right;">CREDIT</th>
                       
                    </tr>
                </thead>
                @if ($journals->isNotEmpty())
                    <tbody>     
                        @foreach ($journals as $journal)
                        <tr>
                            <td>{{ $journal->chartOfAccount->id ?? 'N/A' }}</td>
                            <td>{{ $journal->chartOfAccount->type ?? 'N/A' }}</td>
                            <td>{{ $journal->chartOfAccount->name ?? 'N/A' }}</td>
                            <td style="text-align:right;">{{ ($journal->total_debit) ? number_format($journal->total_debit, 2) : ''  }}</td>
                            <td style="text-align:right;">{{ ($journal->total_credit) ? number_format($journal->total_credit, 2) : ''  }}</td>

                        </tr>
                        @endforeach
                        

                        <tr class="table-active">

                            <td colspan="3" bgcolor="#CCCCCC"><b>TOTAL</b></td>
                            <td style="text-align:right; font-weight:bold" bgcolor="#CCCCCC">
                                {{ number_format($journals->sum('total_debit'), 2) }}
                            </td>
                            <td style="text-align:right;font-weight:bold" bgcolor="#CCCCCC">
                            {{ number_format($journals->sum('total_credit'), 2) }}
                            </td>
                        
                        </tr>
                        
                    </tbody>
                @else
                    <tfoot>
                        <tr>
                            <td colspan="5" style="color: red; text-align:center"><b>No Data Found</b></td>
                        </tr>
                    </tfoot>
                @endif

                
            </table>
            {{-- <p class="text-danger">No data found</p> --}}
        <p>&nbsp;</p>
    </div>
</body>

</html>



