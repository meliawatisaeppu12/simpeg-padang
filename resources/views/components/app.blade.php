<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ url('/img/pdg.ico') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    @yield('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.3/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans">
    <div class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex bg-gray-200">
            <div class="flex-shrink-0 w-64 bg-gray-900">
                <a href="{{ route('dashboard') }}">
                    <div class="flex items-center h-16 px-6 py-12 bg-gray-900 text-xl text-white font-medium">
                        <img class="w-10" src="{{ url('/img/pdg.png') }}" alt="logo-pdg">
                        <div class="ml-2" style="padding-top: 2px;">SIMPEG</div>
                    </div>
                </a>
                <div>
                    <div class="px-2 py-2">
                        <div>
                            <!---->
                        </div>
                    </div>
                    <div class="px-6 py-6 border-t border-gray-700">
                        <h4 class="text-sm text-gray-600 uppercase font-bold tracking-widest">
                            Kepegawaian
                        </h4>
                        <ul class="mt-3 text-white">
                            <li class="mt-3">
                                <a href="{{ route('data-pegawai-index') }}" class="">
                                    Data Pegawai
                                </a>
                            </li>
                            <li class="mt-3">
                                <a href="{{ route('kelompokabsen.index') }}" class="">
                                    Kelompok Absen
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="w-full flex flex-col bg-white">
                <div class="relative shadow-md bg-white flex-shrink-0" style="width: 93%;">
                    <div class="flex justify-between items-center h-16 px-12">
                        @if(Route::currentRouteName()=='data-pegawai-index')
                            <div class="mt-5">
                        @endif
                            <div class="relative w-64">
                                <div class="relative z-50">
                                    <input type="text"
                                        class="block w-full py-2 pl-12 pr-4 bg-gray-200 rounded-full border border-transparent focus:bg-white focus:border-gray-300 focus:outline-none"
                                        placeholder="Cari" />
                                    <div class="flex items-center absolute left-0 inset-y-0 pl-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-6 w-6 fill-current text-gray-600">
                                            <path
                                                d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <!---->
                                <!---->
                            </div>
                        @if(Route::currentRouteName()=='data-pegawai-index')
                            </div>
                        @endif
                        <div class="flex items-center">
                            <div class="ml-6">
                                <!---->
                                <div class="dropdown dropdown-end">
                                    <label tabindex="0" class="btn btn-ghost">
                                        <div class="w-10 rounded-full">
                                            <img src="{{ url('/img/usr.png') }}" />
                                        </div>
                                        <div class="ml-2">
                                            {{ Auth::user()->v2Profile->NAMA }}
                                        </div>
                                    </label>
                                    <ul tabindex="0"
                                        class="mt-3 p-2 shadow-md menu menu-compact dropdown-content bg-white rounded-box w-52">
                                        <li>
                                            <a href="{{ route('profile.show') }}" class="justify-between">
                                                Profile
                                            </a>
                                        </li>
                                        <li><a>Settings</a></li>
                                        <form method="POST" action="{{ route('logout') }}" x-data>
                                            @csrf
                                            <li onclick="this.parentNode.submit();">
                                                <a>
                                                    {{ __('Log Out') }}
                                                </a>
                                            </li>
                                        </form>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(Route::currentRouteName() != 'dashboard')
                    <div class="bg-white h-20 border border-solid p-5">
                        <div class="text-sm breadcrumbs" style="width: 80%;">
                            <ul>
                                <li><a href="{{ url('/') }}">Beranda</a></li>
                                @yield('breadcrumb')
                            </ul>
                        </div>
                    </div>
                @endif
                {{ $slot }}
            </div>
        </div>
        <div class="vue-portal-target"></div>
        <!---->
    </div>
    @yield('script')
</body>
</html>
