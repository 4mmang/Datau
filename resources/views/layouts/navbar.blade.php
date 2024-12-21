<!-- Start Header -->
<header id="header" class="fixed-top" style="background-color: #38527E">
    <div class="container d-flex align-items-center">
        <h1 class="logo me-auto ms-2"><a href="{{ route('beranda') }}"><i class="fad fa-database"></i> PusData Unsulbar</a>
        </h1>
        <!-- start navbar -->
        <nav id="navbar" class="navbar p-4">
            <ul>
                <li><a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                </li>
                <li class="dropdown"><a href="#"><span>Pusat Data</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="{{ url('datasets') }}">Temukan Dataset</a></li>
                        <li><a href="{{ route('sumbang-dataset') }}">Sumbang Dataset</a></li>
                        <li><a href="{{ url('my/dataset') }}">Dataset Saya</a></li>
                    </ul>
                </li>
                <li><a class="nav-link {{ Request::is('tentang-kami') ? 'active' : '' }}"
                        href="{{ route('tentang-kami') }}">Tentang Kami</a></li>
                @auth
                    <li class="dropdown"><a href="#"><span>{{ Auth::user()->email }}</span><i
                                class="bi bi-chevron-down"></i></a>
                        <ul>
                            @if (Auth::user()->role == 'admin')
                                <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                            @else
                                <li><a href="{{ route('profil') }}">Profil Saya</a></li>
                            @endif
                            <li><a href="{{ url('logout') }}">Keluar</a></li>
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
