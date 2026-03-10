<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ URL('/dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL('/') }}/assets/images/square.svg" alt="" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL('/') }}/assets/images/square.svg" alt="" height="40">
                        {{ env('APP_NAME') }}
                    </span>
                </a>

                <a href="{{ URL('/Dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL('/') }}/assets/images/square.svg" alt="" height="40">
                    </span>
                    <span class="logo-lg ">
                        <h5 class="mt-4 text-white"> {{ env('APP_NAME') }} </h5>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- App Search-->
            <form class="app-search  d-none d-xl-block">
                <div class="position-relative">
                    <div class="d-flex gap-2 flex-wrap">

                        @if (Auth::user()->type != 'user')
                            <div class="btn-group">
                                <button type="button" class="  btn btn-outline-light dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                        class=" text-success far fa-bookmark
                                    font-size-16 align-middle me-2"></i>Vouchers
                                    <i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu" style="margin: 0px;">


                                    <a class="dropdown-item" href="{{ route('voucher.create') }}">
                                        <i class="bx bx-plus "></i> Voucher
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('voucher.createJournalVoucher') }}">
                                        <i class="bx bx-plus "></i> Journal Voucher
                                    </a>
                                </div>
                            </div><!-- /btn-group -->
                            @if(env('SHOW_ACCOUNT_REPORTS') == 1)
                            <div class="btn-group">
                                <a href="{{ route('account-reports.request') }}" class="btn btn-outline-light">Accounts
                                    Reports
                                </a>
                            </div>
                            @endif
                            @if(env('SHOW_BALANCE_SHEET') == 1)
                                <div class="btn-group">
                                    <a href="{{ route('balance-sheet.request') }}" class="btn btn-outline-light">Balance
                                        Sheet</a>
                                </div>
                            @endif

                            <!--@if(env('SHOW_PROFIT_LOSS') == 1)    -->
                                <div class="btn-group">
                                    <a href="{{ route('pnl.request') }}" class="btn btn-outline-light">Proft & Loss
                                        Sheet</a>
                                </div>
                            <!--@endif-->
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-light dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">Pos Reports <i
                                        class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu">

                                    <a class="dropdown-item d-none"
                                        href="{{ route('pos-reports.paymentSourceSummaryRequest') }}">Invoice Payment
                                        Source</a>
                                    <a class="dropdown-item"
                                        href="{{ route('pos-reports.salesSummaryRequest') }}">Sales Report</a>
                                    <a class="dropdown-item" href="{{ route('pos-reports.taxSummaryRequest') }}">Vat
                                        Report</a>
                                    {{-- <a class="dropdown-item" href="{{ route('pos-reports.xReportRequest') }}">X Report</a> --}}
                                    <a class="dropdown-item" href="{{ route('pos-reports.zReportRequest') }}">Z
                                        Report</a>
                                    <a class="dropdown-item"
                                        href="{{ route('pos-reports.itemWiseSalesSummaryRequest') }}">Item Wise Sales
                                        Summary</a>
                                    <a class="dropdown-item"
                                        href="{{ route('pos-reports.inventorySummaryRequest') }}">Inventory Summary</a>
                                    {{-- <a class="dropdown-item"
                                        href="{{ route('pos-reports.purchaseSummaryRequest') }}">Purchase Report
                                        Detail</a> --}}
                                    {{-- <a class="dropdown-item"
                                        href="{{ route('pos-reports.inventoryDetailSummaryRequest') }}">Inventory
                                        Report</a> --}}

                                </div>
                            </div><!-- /btn-group -->
                        @endif

                        <a href="{{ route('point-of-sale.create') }}" class="btn btn-primary text-end"
                            type="button">POS
                        </a>

                    </div>
                </div>
            </form>


        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ URL('/') }}/assets/images/users/avatar-1.jpg" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 "
                        key="t-henry">{{ Auth::user()->name . '-' . Auth::user()->type }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <!--<a class="dropdown-item" href="">-->
                    <!--    <i class="bx bx-user font-size-16 align-middle me-1"></i>-->
                    <!--    <span key="t-profile">Profile</span></a>-->


                    <!--<a class="dropdown-item d-block" href=""><i class="bx bx-wrench font-size-16 align-middle me-1"></i>-->
                    <!--    <span key="t-settings">Change-->
                    <!--        Password</span></a>-->



                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i
                                class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span
                                key="t-logout">Logout</span></button>
                    </form>
                </div>
            </div>



        </div>
    </div>
</header>
