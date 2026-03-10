@extends('template.tmp')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid col-md-8">
                <!-- start page title -->
                <h2>Profit & Loss Statement</h2>
                <hr>
                <h4>Revenue</h4>
                    <div class="card">
                        <div class="card-body">
                        {{-- <p><strong>As of:</strong> {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</p> --}}

                            @php $totalRevenue = 0; @endphp
                            <table class="table ">
                                
                                <tbody>
                                    @foreach($revenueData as $group)
                                        <tr>
                                            <td><strong>{{ $group['level3Name'] }}</strong></td>
                                            <td class="text-right"><strong></strong></td>
                                        </tr>
                                        @foreach($group['level4'] as $child)
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;{{ $child['name'] }}</td>
                                                <td class="text-right">{{ $child['credit'] - $child['debit'] }}</td>
                                            </tr>
                                        @endforeach
                                        @php $totalRevenue += floatval(str_replace(',', '', $group['level3Balance'])); @endphp
                                    @endforeach
                                    <tr>
                                        <th>Total Revenue</th>
                                        <th class="text-right">{{ number_format($totalRevenue, 2) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h4>Expenses</h4>
                    @php $totalExpense = 0; @endphp
                    <div class="card">
                        <div class="card-bod">
                             <table class="table ">
                       
                        <tbody>
                            @foreach($expenseData as $group)
                                <tr>
                                    <td><strong>{{ $group['level3Name'] }}</strong></td>
                                    <td class="text-right"><strong></strong></td>
                                </tr>
                                @foreach($group['level4'] as $child)
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;{{ $child['name'] }}</td>
                                        <td class="text-right">{{ $child['debit'] - $child['credit'] }}</td>
                                    </tr>
                                @endforeach
                                @php $totalExpense += floatval(str_replace(',', '', $group['level3Balance'])); @endphp
                            @endforeach
                            <tr>
                                <th>Total Expenses</th>
                                <th class="text-right">{{ number_format($totalExpense, 2) }}</th>
                            </tr>
                        </tbody>
                    </table>
                        </div>
                    </div>

                    <h4>Net Profit: {{ number_format($totalRevenue - $totalExpense, 2) }}</h4>
               
             
            </div>
         </div>
    </div>
    

@endsection
@section('content')

@endsection
