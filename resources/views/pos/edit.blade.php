@extends('template.pos.app')
@section('title', "POS")
@section('content')

<section class="forms pos-section">
    <style>
        #order-summary-table input{
            border: 0px;
            width: 70%;

        }

    </style>

    


    
    <div class="container-fluid">
        
       
        <div class="row">
            {{-- **************************************LEFT SIDE :: START ************************************** --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form id="pos-screen-update" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="invoice_master_id" value="{{ $id }}">
                                <input type="hidden" id="order-status" value="{{ $order->status }}">
                                <div class="row">
                                   @include('pos.partials.information', ['order' => $order])
                                </div>



                                <div class="row">
                                    @include('pos.partials.barcode_scan')
                                </div>
                                
                                <div class="row">
                                    @include('pos.partials.order_table', ['order' => $order])
                                </div>


                                <div class="row">
                                    @include('pos.partials.summary', ['order' => $order])
                                </div>
                                

                              
                               
                            </form>
                        </div>
                    </div>
                </div>

            {{-- **************************************LEFT SIDE :: END ************************************** --}}







            {{-- **************************************RIGHT SIDE :: START ************************************** --}}
                <div class="col-md-6">
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <!-- Content inside the right column -->
                            </div>
                        </div>

                       @include('pos.partials.filter_btns')


                        <div class="card">
                            <div class="card-body">
                                @include('pos.partials.product_variation_table')
                            </div>
                        </div>
                        
                    </div>
                </div>
            {{-- **************************************RIGHT SIDE :: END ************************************** --}}    
        </div>
        <div class="row mt-3 col-md-6">
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary mx-2" type="button" id="update-order-as-completed">Cash & New</button>
                <button class="btn btn-warning mx-2" type="button" id="update-order-only">Update</button>
            <button class="btn btn-dark mx-2" type="button" id="multiple-payments">Multi Pay</button>

                {{-- <button class="btn btn-primary mx-2" type="button" id="update-order">Update Order</button> --}}
                {{-- <button class="btn btn-warning mx-2" type="button" id="store-order-as-draft">Draft</button> --}}
                {{-- <button class="btn btn-dark mx-2" type="button" id="multiple-payments">Multi Pay</button> --}}
                {{-- <button class="btn btn-info mx-2" type="button" id="fetch-completed-orders">Completed Orders</button> --}}
                {{-- <button class="btn btn-success mx-2" type="button" id="fetch-draft-orders">Draft Orders</button> --}}
                {{-- <button class="btn btn-danger mx-2" id="cancel">Cancel</button> --}}
                <button class="btn btn-danger mx-2" id="discard-order-changes">Discard Changes</button>
            </div>
        </div>
        {{-- <div class="row mt-3 col-md-6">
            <div class="d-flex justify-content-between">
                <button class="btn btn-dark mx-2" type="button" id="multiple-payments">Multi Pay</button>
                <button class="btn btn-info mx-2" type="button" id="fetch-completed-orders">Completed Orders</button>
                <button class="btn btn-success mx-2" type="button" id="fetch-draft-orders">Draft Orders</button>
                <button class="btn btn-danger mx-2" id="cancel">Cancel</button>
            </div>
        </div> --}}
    </div>


    
    

  

</section>
<script>
    $(document).ready(function (e) {

        let order = @json($order);


        $('input[name="invoice_discount_type"][value="' + order.discount_type + '"]').prop('checked', true);        
        $('input[name="invoice_discount_value"]').val(order.discount_value);
        $('#rider-name').val(order.rider_name);
        $('#shipping-address').val(order.shipping_address);
        $('#customer-phone').val(order.customer_phone);
        $('#shipping-fee').val(order.shipping_fee);





        summaryCalculation();
    });
</script>



@endsection
