
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Balance Sheet: {{ date('d-m-Y', strtotime(request()->date)) }}</title>
    <link href="{{URL('/')}}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <style>
        tbody, td, tfoot, th, thead, tr {
     
        vertical-align: middle !important;
    }
    
    
    </style>
</head>
<body class="m-5">
    <div id="layout-wrapper">

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 text-start fw-bold">Date: {{ date('d-m-Y', strtotime(request()->date)) }}</div>
                        <div class="col-md-4 text-center"><h4>Profit & Loss</h4></div>
                        <div class="col-md-4 text-end fw-bold">Dated: {{ date('d-m-Y') }}</div>
                    </div>
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
                {{-- <div class="row">
                    <div class="col-md-6 text-end fw-bold "><span style="border-bottom: 1px solid; padding:10px">{{ number_format($leftTotal, 2) }}</span></div>
                    <div class="col-md-6 text-end fw-bold"><span style="border-bottom: 1px solid; padding:10px">{{ number_format($rightTotal+$profit, 2) }}</span></div>
                </div> --}}
                </div>
            </div>
        </div>  
    </div>      
</body>
</html>

   


