<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pegawai - Kabupaten Kepulauan Mentawai</title>

    <link rel="icon" type="image/png" href="{{ asset('img/mentawai.png') }}">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #005f73, #0a9396);
        }

        .login-box {
            width: 380px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-box img {
            width: 90px;
            margin-bottom: 10px;
        }

        /* Judul utama baru */
        .main-title {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #023047;
            line-height: 1.3;
        }

        /* Subjudul Login */
        .sub-title {
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 17px;
            font-weight: bold;
            color: #023047;
        }

        input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            width: 95%;
            padding: 12px;
            background: #005f73;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background: #0a9396;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <img src="{{ asset('img/mentawai.png') }}" alt="Logo Mentawai">

        <h2 class="main-title">
            Sistem Informasi Pegawai<br>
            Kabupaten Kepulauan Mentawai
        </h2>

        <h3 class="sub-title"></h3>

        <form action="" method="POST">
            @csrf
            <input type="text" name="username" placeholder="Nomor Induk Pegawai">
            <input type="password" name="password" placeholder="Kata Sandi">
            <button type="submit">Masuk</button>
        </form>
    </div>

    <script>
        let loginError = false;
        if(loginError){
            Swal.fire({
                icon: "error",
                title: "Login Gagal!",
                text: "Username atau password salah!",
                confirmButtonColor: "#d00000"
            });
        }
    </script>
</body>
</html>
