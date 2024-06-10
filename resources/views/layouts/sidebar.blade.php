<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon  ">
            {{-- <i class="fas fa-laugh-wink"></i> --}}
            <img class="" src="{{asset('Cash_App-Logo.wine.png')}}" style="width: 60px">
        </div>
        <div class="sidebar-brand-text mx-3">Cash App</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{route('dashboard')}}"wire:navigate>
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Emails Data
    </div>
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('emailtransactions')}}" >
            <i class="fas fa-envelope text-white"style="font-size: 20px;"></i>
            <span>Emails</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link " href="{{route('receive-amount')}}"wire:navigate>
            <i class="fas fa-hand-holding-usd text-warning"style="font-size: 20px;"></i>
            <span>Recieve Amount</span>
        </a>
        
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('sent-amount')}}" wire:navigate>
            <i class="fas fa-paper-plane text-success"style="font-size: 20px;"></i>
            <span>Sent Amount</span>
        </a>
        
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('refunds')}}" wire:navigate>
            <i class="fas fa-wallet text-info"style="font-size: 20px;"></i>
            <span>Refunds<span>
        </a>
        
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('bitcionpurchase')}}" wire:navigate>
            <i class="fab fa-btc text-danger" style="font-size: 20px;"></i>
            {{-- <i class="fas fa-bold"></i> --}}
            <span>Bitcion Purchased</span>
        </a>
        
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('bitcionsell')}}" wire:navigate>
            <i class="fab fa-btc text-warning" style="font-size: 20px;"></i>
            <span>Bitcion Sell</span>
        </a>
        
    </li>
 {{--
    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li> --}}

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
    {{-- <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
        <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
        <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
    </div> --}}

</ul>
<!-- End of Sidebar -->