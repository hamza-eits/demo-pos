
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
                        <div class="col-md-4 text-center"><h4>BALANCE SHEET</h4></div>
                        <div class="col-md-4 text-end fw-bold">Dated: {{ date('d-m-Y') }}</div>
                    </div>
                <div class="card">
                    <div class="card-body">
                    
                        <div class="row">
                            <div class="col-md-6">
                                <table width="100%" class="">
                                    <thead>
                                        <tr>
                                            <th colspan="4" > <h3>Assets</h3></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Account</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- php variable declaration --}}
                                        @php    
                                            $leftTotal = 0;  
                                        @endphp  
                                    
                
                                        @foreach($leftLoop['second'] as $secondLevel)
                                            <tr style="border-top:1px solid black">
                                                <td class="fw-bold">{{ $secondLevel['second'] }}</td>
                                                <td colspan="3"></td>
                                            </tr>
                
                                            @foreach($secondLevel['third'] as $thirdLevel)
                                                <tr  style="border-top:1px solid black">
                                                    <td></td>
                                                    <td class="fw-bold" style="border-left:1px solid black">{{ $thirdLevel['third'] }}</td>
                                                    <td colspan="2"></td>
                                                </tr>
                
                                                @foreach($thirdLevel['fourth'] as $fourthLevel)
                                                    <tr >
                                                        <td></td>
                                                        <td style="border-left:1px solid black"></td>
                                                        <td>{{ $fourthLevel['name'] }}</td>
                                                        <td class="text-end">{{ number_format($fourthLevel['total_debit'] - $fourthLevel['total_credit'], 2) }}</td>
                                                    </tr>
        
                                                    @php    
                                                        $leftTotal += $fourthLevel['total_debit'] - $fourthLevel['total_credit']   
                                                    @endphp 
        
                                                @endforeach
                                                        
                                            @endforeach
                                        @endforeach
                                        <tr style="border-top:1px solid black">
                                            <td></td>
                                            <td></td>
                                            <td class="fw-bold">Total</td>
                                            <td style="border-bottom:1px double black;" class="fw-bold text-end" >{{ number_format($leftTotal, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
        
        
        
                            
                            <div class="col-md-6">
                                <table width="100%" class="">
                                    <thead>
                                        <tr>
                                            <th colspan="4" > <h3> Liabilities	</h3></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Account</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
        
                                        {{-- php variable declaration --}}
                                        @php    
                                            $rightTotal = 0;  
                                        @endphp  
        
                                        @foreach($rightLoop as $firstLervel)
            
                                        
                    
                                            @foreach($firstLervel['second'] as $secondLevel)
                                                <tr style="border-top:1px solid black">
                                                    <td class="fw-bold">{{ $secondLevel['second'] }}</td>
                                                    <td colspan="3"></td>
                                                </tr>
                    
                                                @foreach($secondLevel['third'] as $thirdLevel)
                                                    <tr style="border-top:1px solid black">
                                                        <td></td>
                                                        <td style="border-left:1px solid black"  class="fw-bold">{{ $thirdLevel['third'] }}</td>
                                                        <td colspan="2"></td>
                                                    </tr>
                    
                                                    @foreach($thirdLevel['fourth'] as $fourthLevel)
                                                        <tr >
                                                            <td></td>
                                                            <td style="border-left:1px solid black"></td>
                                                            <td>{{ $fourthLevel['name'] }}</td>
                                                            <td class="text-end">{{ number_format($fourthLevel['total_credit'] - $fourthLevel['total_debit'], 2) }}</td>
                                                        </tr>
        
                                                        @php    
                                                            $rightTotal += $fourthLevel['total_credit'] - $fourthLevel['total_debit']   
                                                        @endphp 
                                                    @endforeach
                                                            
                                                @endforeach
                                            @endforeach
                                        @endforeach
        
        
                                        <tr style="border-top:1px solid black">
                                            <td class="fw-bold">Profit/Loss</td>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-bold text-end">{{ number_format($profit, 2) }}</td>
                                        </tr>
                                        <tr style="border-top:1px solid black">
                                            <td></td>
                                            <td></td>
                                            <td class="fw-bold">Total</td>
                                            <td style="border-bottom:1px double black;" class="fw-bold text-end">{{ number_format($rightTotal+$profit, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 text-end fw-bold "><span style="border-bottom: 1px solid; padding:10px">{{ number_format($leftTotal, 2) }}</span></div>
                    <div class="col-md-6 text-end fw-bold"><span style="border-bottom: 1px solid; padding:10px">{{ number_format($rightTotal+$profit, 2) }}</span></div>
                </div>
                </div>
            </div>
        </div>  
    </div>      
</body>
</html>

   


