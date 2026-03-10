@extends('template.pos.app')
@section('title', 'POS')
@section('content')

    <section class="forms pos-section">
        <style>
            #order-summary-table input {
                border: 0px;
                width: 70%;

            }
        </style>






        <div class="container-fluid">


            <div class="row">
                {{-- **************************************LEFT SIDE :: START ************************************** --}}

                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <form id="pos-screen-store" method="POST">
                                @csrf
                                <div class="row">
                                    
                                        @include('pos.partials.information', ['order' => ''])
                                    
                                </div>
                                <div class="row">
                                    @include('pos.partials.barcode_scan')
                                </div>
                                
                                <div class="row">
                                    @include('pos.partials.order_table', ['order' => ''])
                                </div>


                                <div class="row">
                                    @include('pos.partials.summary', ['order' => ''])
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- **************************************LEFT SIDE :: END ************************************** --}}







                {{-- **************************************RIGHT SIDE :: START ************************************** --}}
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <!-- Content inside the right column -->
                            <div class="row col-12">

                                <div class="col-3">
                                    <button class="btn btn-info mx-2" type="button" id="cash-register-btn">CashRegister</button>
                                </div>
                                <div class="col-3">
                                    @if(Auth::user()->type != "user")
                                        <a href="{{ route('admin-dashboard') }}" class="btn btn-primary mx-2">Dashboard</a>
                                    @else
                                        <a href="{{ route('expense.index') }}" class="btn btn-primary mx-2">Dashboard</a>
                                    @endif
                                </div>
                                <div class="col-3">
                                    <a 
                                        href="#" 
                                        class="waves-effect btn btn-danger" 
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bx bx-power-off"></i>
                                        <span key="t-calendar">Logout</span>
                                    </a>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                        @csrf
                                    </form>
                                </div>

                                <div class="col-3">
                                    <button class="btn btn-warning mx-2" type="button" data-bs-toggle="modal" data-bs-target="#add-expense-modal">Expense</button>
                                </div>

                               
                               
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
            <div class="row col-12">
                <div class="d-flex justify-content-start">
                    <button class="btn btn-primary mx-2" type="button" id="store-order-as-completed">Cash & New</button>
                    <button class="btn btn-warning mx-2" type="button" id="store-order-as-draft">Draft</button>
                    <button class="btn btn-dark mx-2" type="button" id="multiple-payments">Multi Pay</button>
                    <button class="btn btn-info mx-2" type="button" id="fetch-completed-orders">Completed
                        Orders</button>
                    <button class="btn btn-success mx-2" type="button" id="fetch-draft-orders">Draft Orders</button>
                    <button class="btn btn-danger mx-2" id="cancel">Cancel</button>
                </div>
            </div>

        </div>





    </section>



@endsection
