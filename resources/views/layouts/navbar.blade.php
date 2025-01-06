<!-- Start Header -->
<header id="header" class="fixed-top" style="background-color: #38527E">
    <div class="container d-flex align-items-center">
        <h1 class="logo me-auto ms-2"><a href="{{ route('beranda') }}"><i class="fad fa-database"></i> PusData</a>
        </h1>
        <!-- start navbar -->
        <nav id="navbar" class="navbar p-4">
            <ul>
                <li><a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                </li>
                <li class="dropdown"><a class="{{ Request::is('dataset*') ? 'active' : '' }}" href="#"><span>Pusat Data</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="{{ route('dataset.index') }}" class="{{ Request::is('dataset') ? 'text-primary' : '' }}">Temukan Dataset</a></li>
                        <li><a href="{{ route('dataset.create') }}" class="{{ Request::is('dataset/create') ? 'text-primary' : '' }}">Sumbang Dataset</a></li>
                    </ul>
                </li>
                <li><a class="nav-link {{ Request::is('artikel*') ? 'active' : '' }}" href="{{ url('/artikel') }}">Artikel</a>
                </li>
                <li><a class="nav-link {{ Request::is('tentang-kami') ? 'active' : '' }}"
                        href="{{ route('tentang-kami') }}">Tentang Kami</a></li>

                @auth
                    <li class="dropdown"><a href="#"><span>{{ Auth::user()->full_name }}</span><i
                                class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('logout') }}">Keluar</a></li>
                        </ul>
                    </li>
                @endauth
                
                <li>
                    @guest
                        <a href="{{ url('login') }}"
                            class="text-center {{ Request::is('login') ? 'active' : '' }}">Masuk</a>
                    @endguest
                </li>
            </ul>

            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        <!-- end navbar -->
    </div>
</header>
<!-- End Header -->
