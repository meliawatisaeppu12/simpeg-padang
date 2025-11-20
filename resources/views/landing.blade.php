<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ url('/img/pdg.ico') }}">
    <title>SIMPEG Kota Padang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.2.0/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans|Roboto:100,300,400,500,700' rel='stylesheet'>

    <style>
        .bg-landing {
            /* background-image: url('{{ url('/img/bg.jpg') }}'); */
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        body {
            font-family: 'Roboto';
        }
    </style>
</head>

<body>
    <div>
        <nav
            class="fixed z-50 w-full bg-white top-0 flex flex-wrap items-center justify-between px-2 py-2 navbar-expand-lg shadow-md">
            <div class="container pl-4 mx-auto flex flex-wrap items-center justify-between">
                <div class="flex-none">
                    <img class="w-8 mr-5" src="{{ url('/img/pdg.png') }}" alt="" srcset="">
                </div>
                <div class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start">
                    <a class="text-sm leading-relaxed inline-block mr-4 py-2 whitespace-no-wrap text-lg text-gray-800"
                        href="/">
                        SIMPEG Kota Padang
                        <!-- <img src="https://www.askfavr.com/_nuxt/img/c88a184.png" alt="..." width="110"
               class="max-w-full h-auto align-middle border-none"/> -->
                    </a>
                    {{-- <button
                        class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none"
                        type="button"><i class="fas fa-bars"></i></button> --}}
                </div>
                <div class="lg:flex flex-grow items-center">
                    <ul class="flex flex-col lg:flex-row list-none lg:ml-auto">
                        @if (Route::has('login'))
                            @if (Auth::check())
                                <li class="nav-item">
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <button
                                            class="rounded-md bg-white text-gray-900 bg-white active:bg-white px-6 py-3 outline-none focus:outline-none lg:mr-1 lg:mb-0 ml-3 mb-3"
                                            type="submit"
                                            style="transition: all 0.15s ease 0s;">{{ Auth::user()->V2Profile()->first()->nama }}
                                        </a>
                                    </form>

                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ url('/dashboard') }}"
                                        class="rounded-md bg-white border border-gray-500 text-white px-6 py-3 shadow hover:shadow-md outline-none focus:outline-none lg:mr-1 lg:mb-0 ml-3 mb-3"
                                        type="button" style="transition: all 0.15s ease 0s; background: #71a8bc;" onmouseover="this.style.backround='#437f95'">Masuk ke Akun SIMPEG
                                        {{-- <i class="fas fa-arrow-right"></i> --}}
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if (Route::has('register'))
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <main class="mt-5">
            <section class="bg-landing header relative items-start flex my-32 md:py-16 md:my-0 base64"
                style="max-height: 860px; height:88vh">
                <div class="container bg-white rounded-lg mx-auto px-8 my-16 ">
                    <div class="items-center flex flex-col md:flex-row-reverse">
                        <div class="w-full md:w-5/12 px-4 mr-auto ml-auto my-16 md:my-0">
                            <img alt="..." class="max-w-full rounded-lg" src="{{ url('/img/asn.png') }}" />
                        </div>
                        <div class="w-10/12 md:w-6/12 px-4 mr-auto ml-auto">
                            <div class="">
                                <h2 style="font-family: Roboto; font-weight: 500"
                                    class="font-semibold text-4xl text-gray-900">
                                    Sistem Informasi Kepegawaian
                                </h2>
                                <p style="font-family: Roboto; font-weight: 300"
                                    class="mt-4 text-lg leading-relaxed text-gray-900">
                                    SIMPEG Kota Padang Merupakan sistem pengelolaan data pegawaian di lingkungan
                                    pemerintah Kota Padang.
                                </p>
                                <div class="mt-12">
                                    <div class="flex flex-wrap">
                                        <div class="w-6/12 md:w-5/12 p-1">
                                            <a href="https://play.google.com/store/apps/details?id=com.favrofficial.favr"
                                                target="_blank">
                                                <!-- <img
                          src="https://www.askfavr.com/_nuxt/img/08f43be.png"
                          alt="..."
                          class="shadow rounded max-w-full h-auto align-middle border-none max-w-xs"/> -->
                                            </a>
                                        </div>
                                        <div class="w-6/12 md:w-5/12 p-1">
                                            <a href="https://itunes.apple.com/us/app/favr-get-things-done/id1449477383?mt=8"
                                                target="_blank">
                                                <!-- <img
                          src="https://askfavr.com/assets/img/download/apple-badge.png"
                          alt="Android app"
                          class="shadow rounded max-w-full h-auto align-middle border-none max-w-xs"/> -->
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer class="relative bg-white pt-8 pb-6">
            {{-- <hr class="my-6 border-gray-400"> --}}
            <div class="flex flex-wrap items-start md:justify-between justify-start">
                <div class="w-1/3 pl-5 grid grid-cols-2">
                    <div class="font-semibold text-gray-700 text-center">SIMPEG</div>
                    <div></div>
                </div>
                <div class="w-2/3 flex grid grid-cols-3 gap-4 pr-8">
                    <div></div>
                    <div></div>
                    <div class="flex grid grid-cols-3 gap-4">
                        <div>
                            <a href="#bantuan" class="text-gray-800 underline">
                                Bantuan
                            </a>
                        </div>
                        <div>
                            <a href="#privasi" class="text-gray-800 underline">
                                Privasi
                            </a>
                        </div>
                        <div>
                            <a href="#ketentuan" class="text-gray-800 underline">
                                Ketentuan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>

</html>
