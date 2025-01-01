<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color: #38527E" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-database"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PUSDATA<sup>usb</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        menu
    </div>
    <li class="nav-item {{ Request::is('admin/dataset*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dataset.index') }}">
            <i class="fas fa-fw fa-database"></i>
            <span>Dataset</span></a>
    </li>
    @if (Auth::user()->role == 'admin')
    <li class="nav-item {{ Request::is('admin/artikel*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.artikel.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Artikel</span></a>
    </li>
        <li class="nav-item {{ Request::is('admin/user') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.user.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span></a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
