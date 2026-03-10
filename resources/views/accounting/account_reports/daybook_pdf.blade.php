<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Day Book Report</title>
    <style>
        body, td, th {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        .subtitle {
            font-size: 16px;
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            padding: 6px;
            border: 1px solid #000;
            vertical-align: top;
        }

        th {
            background-color: #CCCCCC;
            text-align: center;
        }

        .total-row {
            background-color: #CCCCCC;
            font-weight: bold;
        }

        .no-data {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        .right-align {
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="title">{{ env('COMPANY_NAME') }}</div>
    <div class="subtitle">DAY BOOK REPORT</div>

    <table border="0" style="border: none;">
        <tr>
            <td style="border: none;">From: {{ request()->startDate }} TO {{ request()->endDate }}</td>
            <td class="right-align" style="border: none;">Dated: {{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <!-- Two-column layout using table -->
    <table style="width: 100%; border: none;">
        <tr>
            <!-- Left Column: Invoices -->
            <td style="width: 49%; vertical-align: top; padding-right: 1%;">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">DATE</th>
                            <th style="width: 20%;">VHNO</th>
                            <th style="width: 40%;">NAME</th>
                            <th style="width: 20%;">AMOUNT</th>
                        </tr>
                    </thead>
                    @if ($invoices->isNotEmpty())
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->date }}</td>
                                    <td>{{ $invoice->invoice_no }}</td>
                                    <td>{{ $invoice->party->business_name ?? '' }}</td>
                                    <td class="right-align">
                                        {{ $invoice->grand_total ? number_format($invoice->grand_total, 2) : '' }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="3">TOTAL</td>
                                <td class="right-align">{{ number_format($invoices->sum('grand_total'), 2) }}</td>
                            </tr>
                        </tbody>
                    @else
                        <tfoot>
                            <tr>
                                <td colspan="4" class="no-data">No Data Found</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </td>

            <!-- Right Column: Journals -->
            <td style="width: 49%; vertical-align: top;">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 15%;">DATE</th>
                            <th style="width: 10%;">VHNO</th>
                            <th style="width: 45%;">NARRATION</th>
                            <th style="width: 15%;">RECEIPT</th>
                            <th style="width: 15%;">PAYMENT</th>
                        </tr>
                    </thead>
                    @if ($journals->isNotEmpty())
                        <tbody>
                            @foreach ($journals as $journal)
                                <tr>
                                    <td>{{ $journal->date }}</td>
                                    <td>{{ $journal->voucher_no }}</td>
                                    <td style="font-size: 10px;">{{ $journal->narration }}</td>
                                    <td class="right-align">
                                        {{ $journal->debit ? number_format($journal->debit, 2) : '' }}
                                    </td>
                                    <td class="right-align">
                                        {{ $journal->credit ? number_format($journal->credit, 2) : '' }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="3">TOTAL</td>
                                <td class="right-align">{{ number_format($journals->sum('debit'), 2) }}</td>
                                <td class="right-align">{{ number_format($journals->sum('credit'), 2) }}</td>
                            </tr>
                        </tbody>
                    @else
                        <tfoot>
                            <tr>
                                <td colspan="5" class="no-data">No Data Found</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
