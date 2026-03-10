@extends('template.tmp')
@section('title', 'Admin Dashboard')

@section('content')
    <style>
        
       .order-card h2,h6{
            color:white;
            font-size: 20px;
       }
        .bg-c-blue {
            background: linear-gradient(45deg,#4099ff,#73b4ff);
        }
       

        .bg-c-green {
            background: linear-gradient(45deg,#2ed8b6,#59e0c5);
        }

        .bg-c-yellow {
            background: linear-gradient(45deg,#FFB64D,#ffcb80);
        }

        .bg-c-pink {
            background: linear-gradient(45deg,#FF5370,#ff869a);
        }
        .card .card-block {
            padding: 25px;
        }

        .order-card i {
            font-size: 26px;
        }

        .f-left {
            float: left;
        }

        .f-right {
            float: right;
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                
                <div class="row">
                    <div class="col-md-4 col-xl-3">
                        <div class="card bg-c-blue order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Today's Sales</h6>
                                <h2 class="text-end"><i class="fa fa-cart-plus f-left"></i><span>{{ $today['totalSales'] }}</span></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-xl-3">
                        <div class="card bg-c-green order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Today's Order</h6>
                                <h2 class="text-end"><i class="fa fa-rocket f-left"></i><span>{{ $today['totalOrders'] }}</span></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-xl-3">
                        <div class="card bg-c-yellow order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Avg. order Value</h6>
                                <h2 class="text-end"><i class="fa fa-refresh f-left"></i><span>{{ $today['AverageOrderValue'] }}</span></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-xl-3">
                        <div class="card bg-c-pink order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Today Expenses</h6>
                                <h2 class="text-end"><i class="fa fa-credit-card f-left"></i><span>{{ $today['totalExpenses'] }}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body border-secondary border-top border-3 rounded-top">

                            <div class="text-muted mt-4">
                                <div id="cash-and-bank-summary"></div>
                                <div class="d-flex">
                                    <span class="ms-2 text-truncate mt-3"> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
         </div>
    </div>
    

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>


    <script>
        // Decode the PHP JSON data into JavaScript variable
        var cashAndBankSummary = @json($cashAndBankSummary);

        // Create the chart
        Highcharts.chart('cash-and-bank-summary', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Cash and Bank Summary'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Amount'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}' // Formats the number with one decimal point
                    }
                }
            },
            series: [{
                name: "",
                colorByPoint: true,
                data: cashAndBankSummary.map(function(summary) {
                    return {
                        name: summary.name, // Custom label for item
                        y: parseFloat(summary.balance) // Value for item
                    };
                })
            }]
        });
    </script>

    
@endsection
