{{-- <form action="{{ route('password.email') }}" method="POST">
    @csrf
    <input type="email" name="email" required placeholder="Masukkan Email">
    <button type="submit">Kirim Link Reset</button>
</form>

@if ($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (session('status'))
    <p>{{ session('status') }}</p>
@endif --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/favicon(2)-32x32.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #a6c0fe, #f68084);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-body {
            padding: 2rem;
        }
        .btn-primary {
            background-color: #1976d2; /* Warna biru sedang */
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1565c0; /* Warna biru lebih gelap saat hover */
        }
        .form-control {
            border-radius: 25px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #1976d2;
            box-shadow: 0 0 5px rgba(25, 118, 210, 0.5);
        }
        .text-danger {
            color: #d32f2f !important; /* Warna merah untuk pesan error */
        }
        a {
            color: #1976d2; /* Warna biru untuk link */
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
    <title>Reset Password</title>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="mb-4" style="color: #1976d2;">RESET PASSWORD </h2>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Menampilkan pesan sukses -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                    <p class="mb-4" style="color: #555;">Silahkan masukkan email untuk reset password</p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text"name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>
                    </form>
                    <p class="mt-3">
                        Kembali ke
                        <a href="{{ route('login') }}">Login &raquo;</a>
                    </p>
                    @if ($errors->any())
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (session('status'))
                        <p>{{ session('status') }}</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle (popper.js included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.alert').forEach(function (alert) {
   alert.style.cursor = 'pointer';
   alert.addEventListener('click', function () {
       alert.style.display = 'none'; 
   });
});
</script>
</body>
</html>
