@extends('template.tmp')

@section('title', 'pagetitle')


@section('content')



    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->


                <div class="card">
                    <div class="card-body">
                        <div class="invoice-title">
                            <h4 class="float-end font-size-16">Date: {{ date('d-m-Y', strtotime($data->date)) }}</h4>
                            <div class="auth-logo mb-4">

                                <h4># {{ $data->invoice_no }}</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <address>
                                    <h5>Billed To:</h5><br>
                                    <span class="fw-bold">Customer: </span> {{ $data->party->business_name ?? 'N/A' }}<br>
                                    <span class="fw-bold">Contact: </span> {{ $data->party->mobile  ?? 'N/A'}}<br>
                                </address>
                            </div>

                        </div>
                        {{-- <div class="row">
                            <div class="col-sm-6 mt-3">
                                <address>
                                    <strong>Payment Method:</strong><br>
                                    Visa ending **** 4242<br>
                                    jsmith@email.com
                                </address>
                            </div>
                            <div class="col-sm-6 mt-3 text-sm-end">
                                <address>
                                    <strong>Order Date:</strong><br>
                                    October 16, 2019<br><br>
                                </address>
                            </div>
                        </div> --}}
                        <div class="py-2 mt-3">
                            <h3 class="font-size-15 fw-bold">Order summary</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:10%">No.</th>
                                        <th style="width:45%">Item</th>
                                        <th style="width:15%" class="text-end">price</th>
                                        <th style="width:15%" class="text-end">Qty</th>
                                        <th style="width:15%" class="text-end">Total </th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach ($data->invoiceDetails as $detail)
                                        <tr class="@if($detail->type == 'addon') text-warning  @endif">
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->productVariation->name }}</td>
                                            <td class="text-end">{{ $detail->per_unit_price}}</td>
                                            <td class="text-end">{{ $detail->total_quantity }}</td>
                                            <td class="text-end">{{ $detail->grand_total }}</td>
                                            
                                        </tr>
                                        
                                    @endforeach

                                    <tr>
                                        <td colspan="3">
                                            <b> Total</b>
                                         </td>
                                        <td style="text-align: right">
                                            <b>{{ number_format($data->invoiceDetails->sum('total_quantity'),2) }}</b>
                                        </td>
                                        <td style="text-align: right">
                                            <b>{{ number_format($data->invoiceDetails->sum('grand_total'),2) }}</b>
                                        </td>
                                        <td colspan="4" ></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="4">Subtotal</td>
                                        <td>1</td>
                                    </tr>
                                  
                                </tbody>
                            </table>
                        </div>
                        

                    </div>
                </div>

            </div>
        </div>

    </div>
    </div>
    </div>
    <!-- END: Content-->

@endsection
