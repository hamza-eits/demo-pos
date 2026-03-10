<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-finance"></i>
                        <span key="t-ecommerce">Accounting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li> <a href="{{ route('expense.index') }}" key="t-products">Expense</a></li>


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