{{-- <div class="vertical-menu">
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
                        <span key="t-ecommerce">Account</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      
                        <li> <a href="{{ route('voucher.index') }}" key="t-products">Voucher</a></li>
                        <li> <a href="{{ route('expense.index') }}" key="t-products">Expense</a></li>
                       

                    </ul>
                </li>
              
                <li>
                    <a href="{{route('purchase-order.index')}}" class="waves-effect">
                        <i class=" bx bx-receipt mb-0"></i>
                        <span key="t-dashboards">Bill Receipt</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('recipe.index')}}" class="waves-effect">
                        <i class=" fas fa-utensils mb-0"></i>
                        <span key="t-dashboards"> Recipe</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('production.index')}}" class="waves-effect">
                        <i class=" fas fa-cubes mb-0"></i>
                        <span key="t-dashboards"> Production</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('sale-order.index')}}" class="waves-effect">
                        <i class=" bx bx-file mb-0"></i>
                        <span key="t-dashboards"> Sale Order</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('sale-invoice.index')}}" class="waves-effect">
                        <i class=" fas fa-file-invoice-dollar mb-0"></i>
                        <span key="t-dashboards"> Sale Invoice</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{route('party-index','supplier')}}" class="waves-effect">
                        <i class=" bx bxs-user mb-0"></i>
                        <span key="t-dashboards">Suppliers</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('party-index','customer')}}" class="waves-effect">
                        <i class=" fas fa-users mb-0"></i>
                        <span key="t-dashboards">Customer</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('chart-of-account.index')}}" class="waves-effect">
                        <i class=" fas fa-users mb-0"></i>
                        <span key="t-dashboards">Chart of Accounts</span>
                    </a>
                </li>
              
                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span key="t-ecommerce">Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      
                        <li> <a href="{{ route('report.fetchRawMaterailStock') }}" key="t-products">Raw Material Stock</a></li>
                        <li> <a href="{{ route('report.fetchFinishedGoodsStock') }}" key="t-products">Finshed Good Stock</a></li>
                        

                    </ul>
                </li>
                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-ecommerce">Item Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      
                        <li> <a href="{{ route('brand.index') }}" key="t-products">Brands</a></li>
                        <li> <a href="{{ route('category.index') }}" key="t-products">Categories</a></li>
                        <li> <a href="{{ route('unit.index') }}" key="t-products">Units</a></li>
                        <li> <a href="{{ route('item.index') }}" key="t-products">Items</a></li>

                    </ul>
                </li>
               
         
                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-ecommerce">Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      
                        <li> <a href="{{ route('user.index') }}" key="t-products">Users</a></li>
                        <li> <a href="{{ route('warehouse.index') }}" key="t-products">Warehouses</a></li>
                        <li> <a href="{{ route('tax.index') }}" key="t-products">Taxes</a></li>
                    </ul>
                </li>
                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-ecommerce">Opeining Balance</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      
                        <li> <a href="{{ route('opening-balance.finished-goods-stock.create') }}" key="t-products">Finished Goods Stock</a></li>
                       
                    </ul>
                </li>
             
                <li>
                    <a href="#" class="waves-effect" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
</div> --}}