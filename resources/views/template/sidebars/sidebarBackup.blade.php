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
                        <li> <a href="{{route('chart-of-account.index')}}" key="t-products">Chart of Accounts</a></li>
                        <li> <a href="{{route('tax-filing.index')}}" key="t-products">Tax Filing</a></li>
                       

                    </ul>
                </li>


                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-finance"></i>
                        <span key="t-ecommerce">CRM</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      
                        <li> <a href="{{ route('party-index','supplier') }}" key="t-products">Suppliers</a></li>
                        <li> <a href="{{ route('party-index','customer') }}" key="t-products">Customers</a></li>
                        <li> <a href="{{route('lead.index')}}" key="t-products">Lead</a></li>
                        <li> <a href="{{route('job.index')}}" key="t-products">Job</a></li>
                        <li> <a href="{{route('follow-up.index')}}" key="t-products">Follow Ups</a></li>
                       

                    </ul>
                </li>
                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-finance"></i>
                        <span key="t-ecommerce">Inventory</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      
                        <li> <a href="{{route('item.index')}}" key="t-products">Item</a></li>
                        <li> <a href="{{route('material-requisition.index')}}" key="t-products">Material Requisition</a></li>
                        <li> <a href="{{route('purchase-invoice.index')}}" key="t-products">Purchase Invoice</a></li>
                        <li> <a href="{{route('boq.index')}}" key="t-products">BOQ</a></li>
                        <li> <a href="{{route('sale-invoice.index')}}" key="t-products">Sale Invoice</a></li>
                        <li> <a href="{{route('purchase-invoice-return.index')}}" key="t-products">Purchase Invoice Return</a></li>
                       

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
                        <li> <a href="{{ route('unit.index') }}" key="t-products">Units</a></li>
                        <li> <a href="{{ route('tax.index') }}" key="t-products">Tax</a></li>
                        <li> <a href="{{ route('user.index') }}" key="t-products">Users</a></li>

                    </ul>
                </li>
   
                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-ecommerce">Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      
                        <li> <a href="{{ route('reports.item-inventory-request') }}" key="t-products">Item Inventory</a></li>
                        <li> <a href="{{ route('tax-report.request') }}" key="t-products">Tax Report</a></li>
                        <li> <a href="{{ route('lead-report.request') }}" key="t-products">Lead Report</a></li>
                       

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
</div>