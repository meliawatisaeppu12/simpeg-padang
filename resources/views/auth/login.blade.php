<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Site favicon -->
	<link rel="icon" type="image/png" href="{{url('vendor/images/favicon.ico')}}">

    <!-- Scripts -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            /* background-color: #eeeeee; */
            background-color: #ffffff;
        }

        img {
            display: block;
            width: 80px;
            margin: 30px auto;
            /* box-shadow: 0 5px 10px -7px #333333; */
            /* border-radius: 50%; */
        }

        @media (max-width: 639px) {
            .form {
                width: 90%;
                margin: 40px auto 10px auto;
            }

            input {
                width: 100%;
            }

            button {
                width: 100%;
            }
        }

        @media (min-width: 640px) {
            .form {
                width: 440px;
                margin: 90px auto 10px auto;
            }

            input {
                width: 70%;
            }

            button {
                width: 70%;
            }
        }

        .form {
            background-color: #ffffff;
            /* width: 500px; */
            /* margin: 90px auto 10px auto; */
            border: 1px solid #00000045;
            padding: 30px;
            border-radius: 8px;
            overflow: hidden;
            /* box-shadow: 0 3px 6px -3px #333; */
            text-align: center;
        }

        input {
            border-radius: 100px;
            padding: 10px 15px;
            /* width: 50%; */
            border: 1px solid #00000045;
            outline: none;
            display: block;
            margin: 20px auto 20px auto;
        }

        input:focus {
            border-radius: 100px;
            padding: 10px 15px;
            /* width: 50%; */
            border: 2px solid #D9D9D9;
            outline: none;
            display: block;
            margin: 20px auto 20px auto;
        }

        button {
            border-radius: 100px;
            border: none;
            background: #71a8bc;
            /* width: 50%; */
            padding: 10px;
            color: #FFFFFF;
            margin-top: 25px;
            box-shadow: 0 2px 10px -3px #719BE6;
            display: block;
            margin: 55px auto 10px auto;
        }

        button:hover {
            border-radius: 100px;
            border: none;
            background: #437f95;
            /* width: 50%; */
            padding: 10px;
            color: #FFFFFF;
            margin-top: 25px;
            box-shadow: 0 2px 10px -3px #719BE6;
            display: block;
            margin: 55px auto 10px auto;
            cursor: pointer;
        }

        a {
            text-align: center;
            margin-top: 30px;
            color: #719BE6;
            text-decoration: none;
            padding: 5px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }

        .text-gray-600 {
            color: rgb(75 85 99);
        }

        .text-red-600 {
            color: rgb(220 38 38);
        }

        .text-gray-500 {
            color: rgb(107 114 128);
        }

        .footer {
            text-align: center;
        }

        strong {
            font-size: 20pt
        }
    </style>
</head>

<body>
    <div class="form">
        <form action="{{ route('login') }}" method="post">
            @csrf
            @samlidp
            <img src="{{ route('image', '256-logo-padang.png') }}">

            <div class="text-gray-800">
                <strong>Masuk</strong><br>untuk menggunakan SIMPEG Kota Padang
            </div>

            <input type="text" name="username" oninvalid="this.setCustomValidity('NIP Tidak Boleh Kosong')" placeholder="Nomor Induk Pegawai" value="{{ old('username') }}"
                required />

            <input type="password" name="password" oninvalid="this.setCustomValidity('Kata Sandi Tidak Boleh Kosong')" placeholder="Kata Sandi" required />

            <div>
                @if ($errors->has('username'))
                    <small class="text-red-600">
                        {{ $errors->first('username') }}
                    </small>
                @endif
            </div>

            {{-- @if(!session()->has('url.intended'))
                {{session(['url.intended' => url()->previous()])}}
            @endif --}}
            {{-- {{url()->previous()}} --}}
            <button type="submit">Masuk</button>

        </form>
    </div>
    <div class="footer">
        <a href="#bantuan" class="text-gray-600" href="">Bantuan</a>
        <a href="#privasi" class="text-gray-600" href="">Privasi</a>
        <a href="#ketentuan" class="text-gray-600" href="">Ketentuan</a>
    </div>
</body>

</html>
