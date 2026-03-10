<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pos Invoice</title>
    <style>
        * {
            font-size: 10px;
            /* font-family: monospace; Use a monospaced font */
            font-family: 'Ubuntu', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 24px;

        }

        .container {
            width: 70mm;
            /* Standard thermal paper width */
            margin: 0 auto;
            padding: 5px;
        }

        .header {
            margin-top: 0px;
            text-align: center;
        }

        .header div {
            font-size: 14px;
            line-height: 20px;
            font-weight: bold;
        }

        .header img {
            width: 270px;
            height: auto;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .date {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .divider {
            border-top: 1px dashed #000;
        }


        .register-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid black;
            border-bottom: 1px dashed black;
        }

        .register-table th,
        .register-table td {
            /* border: 1px solid #000; */
            padding: 3px;
            font-size: 10px;
        }

        .register-table th {
            background-color: #f0f0f0;
        }

        @media print {
            .hidden-print {
                display: none !important;
            }

            @page {
                size: 70mm auto;
                /* Width 80mm, height auto */

            }
        }
    </style>
    <style>
        .summary-table {
            width: 100%;
            margin-bottom: 10px;

        }

        .summary-table tr td {
            padding: 1px 0;
        }

        .summary-table tr td:first-child {
            text-align: left;
        }

        .summary-table tr td:last-child {
            text-align: right;
        }

        .description {
            font-size: 7px;
        }

        .contact {
            margin-top: 7px;
            margin-bottom: 10px;
        }

        .whatsapp-logo {
            height: 16px;
            vertical-align: middle;
            margin-right: 5px;
            margin-top: 5px;
        }

        .summary-information {
            font-weight: bold;
            line-height: 14px;

        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('company-logo-1.png') }}" alt="Logo">
        {{-- @if ($invoiceHeader)
            @foreach ($invoiceHeader as $row)
                <div>{{ $row['value'] }}</div>
            @endforeach


        @endif --}}
    </div>
    <div class="container">


        <div class="divider"></div>

        <table class="summary-table">
            <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Invoice No:</td>
                    <td style="text-align: left">{{ $order->invoice_no }}</td>

                    <td style="text-align: right">Date:</td>
                    <td>{{ date('d/m/Y', strtotime($order->date)) }}</td>
                </tr>
                <tr>

                </tr>
                <tr>
                    <td>Customer:</td>
                    <td style="text-align: left">{{ $order->party->business_name ?? '-' }}</td>
                       
                    
                    <td style="text-align: right">Phone No</td>
                    <td>{{ $order->party->mobile ?? '' }}</td>
                </tr>

                <tr>
                    <td colspan="4" style="text-align: left">Address: {{ $order->party->shipping_address ?? '-' }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="divider"></div>

        <table class="register-table">
            <tr>
                <th style="text-align:left">#</th>
                <th style="text-align:left">Item</th>
                <th>{{ __('file.Qty') }}</th>
                <th>Rate</th>
                <th>Dis.</th>
                <th>Total</th>
            </tr>
            @foreach ($order->details as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $detail->productVariation->name }}
                        <div class="description">{{ $detail->productVariation->product->description ?? '' }}</div>
                    </td>
                    <td style="text-align:center">{{ number_format($detail->quantity, 3) }}</td>
                    <td style="text-align:right">{{ $detail->unit_price }}</td>



                    <td style="text-align:center">

                        @if ($detail->discount_value > 0)
                            {{ $detail->discount_type == 'percentage'
                                ? number_format($detail->discount_value) . '%'
                                : number_format($detail->discount_value) }}
                        @else
                            -
                        @endif


                    </td>
                    <td style="text-align:right">{{ $detail->grand_total }}</td>
                </tr>
            @endforeach
        </table>


        <table class="summary-table" style="border: 1px solid black;border-top: none">
            @foreach ($data as $row)
                <tr>
                    <td class="summary-information">{{ $row['name'] }}</td>
                    <td class="summary-information">{{ $row['value'] }}</td>
                </tr>
            @endforeach

            <tr>
                <td class="summary-information">Total</td>
                <td class="summary-information">{{ $order->grand_total }}</td>
            </tr>

        </table>
        <div class="divider"></div>

        {{-- <table>
            <tr>
                <td style="width:5%"><img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" class="whatsapp-logo"></td>
                <td style="width:45%">WhatsApp: 055 5511 375</td>
                <td style="width:50%; text-align:right">Land Line: 04 5521 584</td>
            </tr>
        </table> --}}




        <strong>
            <div style="text-align: center; font-size: 13px; margin-top: 10px;margin-bottom: 5px;">
                *** Thank you for your business ***
            </div>
        </strong>
        <br><br><br>
        <hr>






    </div>
</body>

</html>
















