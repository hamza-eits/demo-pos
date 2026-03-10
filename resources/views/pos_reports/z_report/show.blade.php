@extends('template.pos.print')
@section('title','Z Report')
@section('content')

<style>
    .summary-table {
        width: 100%;
        margin-bottom: 10px;
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

</style>

    <div class="container">
        <div class="header">
            <div class="title">Z REPORT</div>
            <div class="date">{{ date('d-m-Y', strtotime($startDate)) }} to {{ date('d-m-Y', strtotime($endDate)) }}</div>
        </div>

        <div class="divider"></div>

        <table class="summary-table">
            @foreach ($data as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ number_format($row['amount'], 2) }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <div class="title" style="text-align: center; margin: 5px 0;">Cash Register History</div>
        <table class="register-table">
            <tr>
                <th>Name</th>
                <th>Opening Time</th>
                <th>Opening</th>
                <th>Closing</th>
                <th>Closing Time</th>
            </tr>
            @forelse ($cashRegisterHistory as $history)
            <tr>
                <td>{{ $history->user->name }}</td>
                <td>{{ date('d/m h:i', strtotime($history->opened_at)) }}</td>
                <td style="text-align: right">{{ number_format($history->opening_cash, 2) }}</td>
                @if($history->status == 1)
                    <td colspan="2" style="text-align: center;font-style:italic">Active</td>
                @else
                    <td style="text-align: right">{{ number_format($history->closing_cash, 2) }}</td>
                    <td>{{ date('d/m h:i', strtotime($history->closed_at)) }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;font-style:italic">No data found</td>
            </tr>
            @endforelse
        </table>

        
        <div style="text-align: center; font-size: 9px; margin-top: 10px;">
            *** End of Report ***
        </div>
        <br>
        <hr>
    </div>
@endsection