<!DOCTYPE html>
<html lang="id">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>SIMPEG - Dasbor</title>

    <!-- Site favicon -->
    <link rel="icon" type="image/png" href="{{ url('vendor/images/favicon.ico') }}">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/styles/core.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/styles/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/styles/style.css') }}">
    @yield('styles')
    <livewire:styles />
    <style>
        .form-control:focus {
            border-color: #162c70;
            box-shadow: 0 0 0 0.2rem rgba(38, 140, 171, 0.25);
        }

        .select2-search__field:focus {
            border-color: #162c70;
            box-shadow: 0 0 0 0.2rem rgba(38, 140, 171, 0.25);
        }

        .selection {
            background: aliceblue;
        }

        .select2-container .select2-selection--single {
            border-color: aliceblue;
            background: aliceblue;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #186c8e;
        }

        .select2-label {
            color: #186c8e;
        }

        .modal-title {
            font-weight: normal;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #299fac;
        }

        .bootstrap-select .dropdown-menu li .dropdown-item.active:hover,
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #299fac;
            color: #fff;
        }

        .row {
            margin-left: 0px;
            margin-right: 0px;
        }

        .btn-group {
            margin-bottom: 25px;
        }

        .col-md-12 {
            padding-left: 0px;
            padding-right: 0px;
        }

        .menu-block {
            box-shadow: 0px 16px 4px 4px rgba(0, 0, 0, .08)
        }

        textarea.form-control {
            height: 76px;
        }

        .custom-file-input,
        .custom-file-label,
        .custom-select,
        .form-control {
            height: 38px;
        }
    </style>
</head>

<body class="sidebar-light">

    @if (app('router')->getRoutes()->match(Request::create($previousUrl))->getName() == 'login')
        <div class="pre-loader">
            <div class="pre-loader-box">
                <div class="loader-logo"><img src="{{ url('vendor/images/simpeg.gif') }}" alt=""></div>
                <div class='loader-progress' id="progress_div">
                    <div class='bar' id='bar1'></div>
                </div>
                <div class='percent' id='percent1'>0%</div>
                <div class="loading-text">
                    Memuat...
                </div>
            </div>
        </div>
    @endif

    <div class="header">
        <div class="header-left ml-4">
            <div class="brand-logo mr-5">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ url('vendor/images/simpeg.png') }}" alt="" class="dark-logo">
                    <img src="{{ url('vendor/images/simpeg.png') }}" alt="" class="light-logo">
                </a>
            </div>
            {{-- <div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
            <div class="header-search">
                <form>
                    <div class="form-group mb-0">
                        <i class="dw dw-search2 search-icon"></i>
                        <input name="cari" type="text" class="form-control search-input"
                            placeholder="Cari Disini">
                    </div>
                </form>
            </div> --}}
        </div>
        <div class="header-right">
            <div class="user-notification">
                <div class="dropdown">
                    <a class="text-lg text-dark mr-4" href="{{ Route('dashboard') }}" role="button">
                        Dasbor
                    </a>
                    <a class="text-dark mr-4" href="{{ Route('list-usulan') }}" role="button">
                        Layanan
                    </a>
                    @if(Auth::user()->hasAccessCode(9))
                    <a class="text-lg text-dark mr-2" href="{{ Route('perangkat') }}" role="button">
                        Perangkat
                    </a>
                    @endif
                    <a class="dropdown-toggle no-arrow" href="#" role="button">
                        {{-- <i class="icon-copy dw dw-notification"></i> --}}
                        {{-- <span class="badge notification-active"></span> --}}
                    </a>
                </div>
            </div>
            <div class="user-info-dropdown mr-4">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <img style="width: 46px; height: 46px; object-fit: cover;"
                                src="{{ route('photo-pegawai', Auth::user()->username) }}" alt="">
                        </span>
                        <span
                            class="user-name">{{ Auth::user()->v2Profile->nama . ', ' . Auth::user()->v2Profile->gelar_belakang }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="#"><i class="dw dw-user1"></i> Profil</a>
                        <a class="dropdown-item" href="#"><i class="dw dw-settings2"></i> Pengaturan</a>
                        <a class="dropdown-item" href="#"><i class="dw dw-help"></i> Bantuan</a>
                        {{-- <form method="POST" action="" x-data>
                            @csrf --}}
                            <li class="dropdown-item" style="cursor:pointer">
                                <a href="{{route('saml.logout','83b957af-0643-4f97-8d44-e2ef07d77e6d')}}">
                                    <i class="dw dw-logout"></i> {{ __('Keluar') }}
                                </a>
                            </li>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="left-side-bar d-none">

        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li class="dropdown @if (Route::currentRouteName() == 'dashboard') show @endif">
                        <a href="{{ route('dashboard') }}" class="dropdown-toggle no-arrow rounded-lg ml-3 mr-3">
                            <span class="micon dw dw-house-1"></span><span class="mtext">Dasbor</span>
                        </a>
                    </li>
                    <li class="dropdown @if (Route::currentRouteName() == 'riwayat-hukdis') show @endif">
                        <a href="javascript:;" class="dropdown-toggle rounded-lg ml-3 mr-3">
                            <span class="micon dw dw-counterclockwise"></span><span class="mtext">Riwayat</span>
                        </a>
                        <ul class="submenu" @if (Route::currentRouteName() == 'riwayat-hukdis') style="display: block" @endif>
                            <li><a href="#" class="rounded-lg ml-3 mr-3">Jabatan</a></li>
                            <li><a href="#" class="rounded-lg ml-3 mr-3">Pangkat/Golongan</a></li>
                            <li><a href="{{ route('riwayat-hukdis') }}"
                                    @if (Route::currentRouteName() == 'riwayat-hukdis') class="active rounded-lg ml-3 mr-3" @else class="rounded-lg ml-3 mr-3" @endif>Hukuman
                                    Disiplin</a></li>
                            <li><a href="#" class="rounded-lg ml-3 mr-3">Pendidikan</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container d-flex justify-content-center col-md-12">
        <div class="py-12 col-md-11">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-4">
                        <div class="min-height-200px">
                            <div class="row">
                                @if (Route::currentRouteName() == 'data-pegawai' ||
                                        Route::currentRouteName() == 'list-usulan' ||
                                        Route::currentRouteName() == 'detail-usulan')
                                    {{ $slot }}
                                @else
                                    @if (str_contains(url()->current(), '/pegawai/'))
                                        {{-- <x-profile-sidebar :kind="1" :user="$user" /> --}}
                                    @elseif(Route::currentRouteName() == 'data-pegawai' ||
                                            Route::currentRouteName() == 'list-usulan' ||
                                            Route::currentRouteName() == 'detail-usulan')
                                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mb-30">
                                            <x-profile-sidebar :kind="2" />
                                        </div>
                                    @else
                                        {{-- <x-profile-sidebar :kind="1" :user="json_encode([
                                                'nip_baru' => Auth::user()->v2Profile->nip_baru,
                                                'nama' => Auth::user()->v2Profile->nama,
                                                'gelar_belakang' => Auth::user()->v2Profile->gelar_belakang,
                                                'jabatan_nama' => Auth::user()->v2Profile->jabatan_nama,
                                                'unor_induk_nama' => Auth::user()->v2Profile->unor_induk_nama,
                                            ])" /> --}}
                                    @endif
                                    <div
                                        class="@if (Route::currentRouteName() == 'data-pegawai' ||
                                                Route::currentRouteName() == 'list-usulan' ||
                                                Route::currentRouteName() == 'detail-usulan') col-xl-9 col-lg-9 col-md-9 col-sm-12 mb-30 @else col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30 @endif">
                                        <div class="card-box">
                                            <div class="page-header">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12">
                                                        <nav aria-label="breadcrumb" role="navigation">
                                                            <ol class="breadcrumb">
                                                                <li class="breadcrumb-item"><a
                                                                        href="{{ route('dashboard') }}">Dasbor</a>
                                                                </li>
                                                                @if (Route::currentRouteName() != 'dashboard')
                                                                    <li class="breadcrumb-item active"
                                                                        aria-current="page">@yield('breadcrumb')</li>
                                                                @endif
                                                            </ol>
                                                        </nav>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-box height-100-p overflow-hidden">
                                            <div class="profile-tab height-100-p">
                                                <div class="tab height-100-p">
                                                    <ul class="nav nav-tabs customtab" role="tablist">
                                                        @if (str_contains(Route::currentRouteName(), 'perangkat'))
                                                            <li class="nav-item">
                                                                <a class="nav-link @if (Route::currentRouteName() == 'perangkat') active @endif"
                                                                    href="{{ route('perangkat') }}">Perangkat</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link @if (Route::currentRouteName() == 'perangkat-dibekukan') active @endif"
                                                                    href="{{ route('perangkat-dibekukan') }}">Akun Dibekukan</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link @if (Route::currentRouteName() == 'perangkat.atur-sandi') active @endif"
                                                                    href="{{ route('perangkat.atur-sandi') }}">Atur Ulang Kata Sandi</a>
                                                            </li>
                                                        @else
                                                            @if (!str_contains(url()->current(), '/pegawai/'))
                                                                <li class="nav-item">
                                                                    <a class="nav-link @if (Route::currentRouteName() == 'dashboard') active @endif"
                                                                        href="{{ route('dashboard') }}">Dasbor</a>
                                                                </li>
                                                            @endif
                                                            <li class="nav-item">
                                                                <a class="nav-link @if (Route::currentRouteName() == 'riwayat-jabatan' || Route::currentRouteName() == 'jabatan.pegawai') active @endif"
                                                                    href="{{ !str_contains(url()->current(), '/pegawai/') ? route('riwayat-jabatan') : route('jabatan.pegawai', Crypt::encrypt(json_decode($user)->nip_baru)) }}">Jabatan</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link @if (Route::currentRouteName() == 'riwayat-diklat' || Route::currentRouteName() == 'diklat.pegawai') active @endif"
                                                                    href="{{ !str_contains(url()->current(), '/pegawai/') ? route('riwayat-diklat') : route('diklat.pegawai', Crypt::encrypt(json_decode($user)->nip_baru)) }}">Diklat</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link @if (Route::currentRouteName() == 'riwayat-hukdis' || Route::currentRouteName() == 'hukdis.pegawai') active @endif"
                                                                    href="{{ !str_contains(url()->current(), '/pegawai/') ? route('riwayat-hukdis') : route('hukdis.pegawai', Crypt::encrypt(json_decode($user)->nip_baru)) }}">Hukdis</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link @if (Route::currentRouteName() == 'data-utama' || Route::currentRouteName() == 'data-utama.pegawai') active @endif"
                                                                    href="{{ !str_contains(url()->current(), '/pegawai/') ? route('data-utama') : route('data-utama.pegawai', Crypt::encrypt(json_decode($user)->nip_baru)) }}">Data
                                                                    Utama</a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    <div class="tab-content">
                                                        <!-- Timeline Tab start -->
                                                        <div class="tab-pane fade show active" role="tabpanel">
                                                            <div class="pd-20">
                                                                {{ $slot }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="footer-wrap pd-20 card-box position-fixed fixed-bottom">
                        <div>Sistem Informasi Kepegawaian Pemerintah Kota Padang</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- js -->
    <script src="{{ url('vendor/scripts/core.js') }}"></script>
    <script src="{{ url('vendor/scripts/script.min.js') }}"></script>
    <script src="{{ url('vendor/scripts/process.js') }}"></script>
    <script src="{{ url('vendor/scripts/layout-settings.js') }}"></script>
    @yield('scripts')
    <livewire:scripts />
</body>

</html>
