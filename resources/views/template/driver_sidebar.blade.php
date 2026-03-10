@include('template.sidebars.admin')
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li> 
                    <a href="{{ route("driver-dashboard") }}" class="waves-effect">
                        <i class="mdi mdi-speedometer-slow mb-0"></i>
                        <span key="t-dashboards">Driver Dashboard</span>
                    </a>
                </li>
                <li> 
                    <a href="{{ route("order.index") }}" class="waves-effect">
                        <i class="bx bx-copy-alt mb-0"></i>
                        <span key="t-dashboards">Orders</span>
                    </a>
                </li>
                <li> 
                    <a href="{{ route("fuel.index") }}" class="waves-effect">
                        <i class="bx bx-copy-alt mb-0"></i>
                        <span key="t-dashboards">Fuel</span>
                    </a>
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