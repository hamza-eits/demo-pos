<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KOT {{ $order->invoice_no }}</title>
    <style>
        

        @media print {
            .hidden-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div style="max-width: 400px">
        <table width="100%">
            <tr  style="text-align:center">
                <td>
                    KOT Print
                </td>
            </tr>
        </table>
        <table style="border: 1px dashed black;" width="100%">
            <tr>
                <td width="50%" >{{ $order->invoice_no }}</td>
                <td width="50%" align="right">{{ date('d-m-Y', strtotime($order->date)) }}</td>
            </tr>
            <tr>
                <td width="50%" >{{  $order->party->business_name ?? '-' }}</td>
                <td width="50%" align="right">{{  $order->serving_type }}</td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <th width="10%"  style="text-align: left">#</th>
                <th width="70%" style="text-align: left">Item</th>
                <th width="20%" style="text-align:right">{{ __('file.Qty') }}</th>
            </tr>
            @foreach ($order->details as $detail)
                <tr>
                    <td  style="text-align: left">{{ $loop->iteration }}</td>
                    <td style="text-align: left">
                        {{ $detail->productVariation->name }}
                    </td>    
                    <td style="text-align:right">{{ number_format($detail->quantity,3) }}</td>                
                </tr>
            @endforeach
        </table>  
        <hr>
        <table width="100%">
            <tr>
                <th align="left"> Total {{ __('file.Quantity') }}</th>
                <th align="right">{{  number_format($order->details->sum('quantity'),3) }}</th>
            </tr>
        </table>
        <br>
        
     
        <hr>
        
    </div>
</body>


</html> 