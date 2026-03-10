@extends('template.tmp')
@section('title', 'pagetitle')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="card">
                    <div class="card-body">
                    
                        <div class="row">
                            <div class="col-md-12">
                                @php    
                                    $totalExpense = 0;  
                                @endphp  

                                @foreach($expenseData[0]['second'] as $secondLevel)
                                    @foreach($secondLevel['third'] as $thirdLevel)
                                        <table width="100%" class="mb-4">
                                            <thead>
                                                <tr>
                                                    <th colspan="2"><h4>{{ $thirdLevel['third'] }}</h4></th>
                                                </tr>
                                                <tr>
                                                    <th>Account</th>
                                                    <th class="text-end">Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $groupTotal = 0;
                                                @endphp

                                                @foreach($thirdLevel['fourth'] as $fourthLevel)
                                                    @php
                                                        $balance = $fourthLevel['total_credit'] - $fourthLevel['total_debit'];
                                                        $groupTotal += $balance;
                                                        $totalExpense += $balance;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $fourthLevel['name'] }}</td>
                                                        <td class="text-end">{{ number_format($balance, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr style="border-top:1px solid black">
                                                    <td class="fw-bold">Subtotal</td>
                                                    <td class="fw-bold text-end">{{ number_format($groupTotal, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endforeach
                                @endforeach

                                <table width="100%">
                                    <tr>
                                        <td class="fw-bold">Total Expense</td>
                                        <td class="fw-bold text-end" style="border-top: 2px double black;">{{ number_format($totalExpense, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12">
                                @php    
                                    $totalExpense = 0;  
                                @endphp  

                                @foreach($revenueData[0]['second'] as $secondLevel)
                                    @foreach($secondLevel['third'] as $thirdLevel)
                                        <table width="100%" class="mb-4">
                                            <thead>
                                                <tr>
                                                    <th colspan="2"><h4>{{ $thirdLevel['third'] }}</h4></th>
                                                </tr>
                                                <tr>
                                                    <th>Account</th>
                                                    <th class="text-end">Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $groupTotal = 0;
                                                @endphp

                                                @foreach($thirdLevel['fourth'] as $fourthLevel)
                                                    @php
                                                        $balance = $fourthLevel['total_credit'] - $fourthLevel['total_debit'];
                                                        $groupTotal += $balance;
                                                        $totalExpense += $balance;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $fourthLevel['name'] }}</td>
                                                        <td class="text-end">{{ number_format($balance, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr style="border-top:1px solid black">
                                                    <td class="fw-bold">Subtotal</td>
                                                    <td class="fw-bold text-end">{{ number_format($groupTotal, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endforeach
                                @endforeach

                                <table width="100%">
                                    <tr>
                                        <td class="fw-bold">Total Expense</td>
                                        <td class="fw-bold text-end" style="border-top: 2px double black;">{{ number_format($totalExpense, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                           
        
                            
                            
                        </div>
                    </div>
                </div>
             
            </div>
         </div>
    </div>
    

@endsection
