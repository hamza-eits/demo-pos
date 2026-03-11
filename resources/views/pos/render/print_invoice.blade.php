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
            font-family: 'Ubuntu', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 24px;
        }

        .container {
            width: 70mm;
            margin: 0 auto;
            padding: 5px;
        }

        .header {
            margin-top: 0px;
            text-align: center;
        }

        .header img {
            width: 270px;
            height: auto;
        }

        .company-details {
            text-align: center;
            margin: 5px 0;
            line-height: 16px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            line-height: 20px;
        }

        .company-info {
            font-size: 10px;
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

        .summary-information {
            font-weight: bold;
            line-height: 14px;
        }
    </style>
</head>

<body>

    {{-- LOGO --}}
    <div class="header">
        @if ($company && $company->logo)
            <img src="{{ asset('company/' . $company->logo) }}" alt="Logo">
        @endif
    </div>

    {{-- COMPANY DETAILS --}}
    <div class="company-details">
        @if ($company)
            @if ($company->name)
                <div class="company-name">{{ $company->name }}</div>
            @endif
            @if ($company->address)
                <div class="company-info">{{ $company->address }}</div>
            @endif
            @if ($company->contact_no)
                <div class="company-info">Tel: {{ $company->contact_no }}</div>
            @endif
            @if ($company->trn_no)
                <div class="company-info">TRN: {{ $company->trn_no }}</div>
            @endif
        @endif
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
                    <td>Customer:</td>
                    <td style="text-align: left">{{ $order->party->business_name ?? '-' }}</td>
                    <td style="text-align: right">Phone No</td>
                    <td>{{ $order->party->mobile ?? '' }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: left">Address: {{ $order->party->shipping_address ?? '-' }}</td>
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

        <table class="summary-table" style="border: 1px solid black; border-top: none">
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

        <strong>
            <div style="text-align: center; font-size: 13px; margin-top: 10px; margin-bottom: 5px;">
                *** Thank you for your business ***
            </div>
        </strong>
        <br><br><br>
        <hr>

    </div>
</body>

</html>