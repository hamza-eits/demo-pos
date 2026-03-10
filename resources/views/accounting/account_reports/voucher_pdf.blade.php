<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vouchers</title>
    
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
    @foreach ($vouchers as $voucher)
        <div align="center">
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <div align="center" class="style1">{{ env('COMPANY_NAME') }}</div>
                    </td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div align="center" class="style2"><u>{{ $voucher->type }}</u></div>
                    </td>
                </tr>

                <tr>
                    <td width="50%" height="18" valign="top">VOUCHER # {{ $voucher->voucher_no }}</td>
                    <td width="50%" valign="top">
                        <div align="right">DATED: {{ date('d-m-Y', strtotime($voucher->date)) }}</div>
                    </td>
                </tr>
            </table>

            <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF"
                style="border-collapse:collapse;">
                <tr>
                    <th>Account</th>
                    <th>Narration</th>
                    <th>Customer </th>
                    <th>Supplier</th>
                    <th>DEBIT</th>
                    <th>CREDIT</th>
                </tr>

                @foreach ($voucher->details as $detail)
                    <tr>
                        <td>{{ $detail->chartOfAccount->name }}</td>
                        <td>{{ $detail->narration }}</td>
                        <td>{{ $detail->customer->business_name ?? '' }}</td>
                        <td>{{ $detail->supplier->business_name ?? '' }}</td>
                        <td align="right">{{ ($detail->debit) ? number_format($detail->debit,2) : ''  }}</td>
                        <td align="right">{{ ($detail->credit) ? number_format($detail->credit,2) : ''  }}</td>
                    </tr>   
                @endforeach
                   


                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-start" colspan="4"><strong>Total</strong> </td>
                    <td align="right"><strong>{{ number_format($voucher->details->sum('debit'),2) }}</strong></td>
                    <td align="right"><strong>{{ number_format($voucher->details->sum('credit'),2) }}</strong></td>
                </tr>
            </table>
            <p><br>
            </p>
            <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="33%">PAID / CHECK BY </td>
                    <td width="33%">
                        <div align="center">AUTHORIZED BY </div>
                    </td>
                    <td width="33%">
                        <div align="right">RECEIVED BY </div>
                    </td>
                </tr>
                <tr>
                    <td width="33%">(Operator : Administrator </td>
                    <td width="33%">&nbsp;</td>
                    <td width="33%">&nbsp;</td>
                </tr>
            </table>
            <p>&nbsp;</p>
            <p style="page-break-after: always;">&nbsp;</p>
        </div>
    @endforeach

</body>

</html>
