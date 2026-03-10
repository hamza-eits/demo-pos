<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>

                    <a href="{{ route('admin-dashboard') }}" class="waves-effect">
                        <i class=" mdi mdi-speedometer-slow mb-0"></i>
                        <span key="t-dashboards">Admin Dashboard</span>
                    </a>

                </li>


                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-finance"></i>
                        <span key="t-ecommerce">Accounting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li> <a href="{{ route('voucher.index') }}" key="t-products">Voucher</a></li>
                        <li> <a href="{{ route('expense.index') }}" key="t-products">Expense</a></li>
                        @if(env('SHOW_CHART_OF_ACCOUNTS_SECTION') == 1)
                            <li> <a href="{{ route('chart-of-account.index') }}" key="t-products">Chart of Accounts</a></li>
                        @endif


                    </ul>
                </li>

                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-finance"></i>
                        <span key="t-ecommerce">Inventory</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li> <a href="{{ route('product.index') }}" key="t-products">Product</a></li>
                        {{-- <li> <a href="{{ route('recipe.index') }}" key="t-products">Recipe</a></li> --}}
                        <li> <a href="{{ route('purchase-invoice.index') }}" key="t-products">Purchase Invoice</a></li>
                        <li> <a href="{{ route('point-of-sale.index') }}" key="t-products">Sale Invoice</a></li>



                    </ul>
                </li>
                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-finance"></i>
                        <span key="t-ecommerce">CRM</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li> <a href="{{ route('party-index', 'supplier') }}" key="t-products">Suppliers</a></li>
                        <li> <a href="{{ route('party-index', 'customer') }}" key="t-products">Customers</a></li>



                    </ul>
                </li>

                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-ecommerce">Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li> <a href="{{ route('brand.index') }}" key="t-products">Brands</a></li>
                        <li> <a href="{{ route('category.index') }}" key="t-products">Categories</a></li>
                        {{-- <li> <a href="{{ route('product-model.index') }}" key="t-products">Product Model</a></li> --}}
                        {{-- <li> <a href="{{ route('product-group.index') }}" key="t-products">Product Group</a></li> --}}
                        {{-- <li> <a href="{{ route('material.index') }}" key="t-products">Material</a></li> --}}
                        {{-- <li> <a href="{{ route('custom-field.index') }}" key="t-products">Custom Field</a></li> --}}
                        <li> <a href="{{ route('unit.index') }}" key="t-products">Units</a></li>
                        {{-- <li> <a href="{{ route('tax.index') }}" key="t-products">Tax</a></li> --}}
                        <li> <a href="{{ route('user.index') }}" key="t-products">Users</a></li>
                        <li> <a href="{{ route('variation.index') }}" key="t-products">Variations</a></li>
                        <li> <a href="{{ route('pos-setting.index') }}" key="t-products">POS Tax Settings</a></li>
                        <li> <a href="{{ route('company-info.index') }}" key="t-products">Company</a></li>

                    </ul>
                </li>



                <li>
                    <a href="#" class="waves-effect"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-power-off"></i>
                        <span key="t-calendar">Logout</span>
                    </a>
                </li>

                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
