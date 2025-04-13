<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrasi FamiList</title>
    <link rel="icon" href="{{ asset('images/favicon(2)-32x32.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            background-color: #1976d2;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1565c0;
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
            color: #d32f2f !important;
        }
        a {
            color: #1976d2;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .password-input-group {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            z-index: 5;
        }
        .password-toggle:hover {
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="mb-4" style="color: #1976d2;">Registrasi</h2>
                        <p class="mb-4" style="color: #555;">Silahkan isi formulir berikut untuk registrasi aplikasi</p>
                        
                        

                        <!-- Menampilkan pesan sukses -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('registerasi.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3 password-input-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                <i id="togglePassword" class="fa-regular fa-eye password-toggle" onclick="togglePassword('password', 'togglePassword')"></i>
                            </div>
                            <div class="mb-3 password-input-group">
                                <input type="password" name="password_confirmation" id="passwordConfirmation" class="form-control" placeholder="Konfirmasi Password" required>
                                <i id="togglePasswordConfirmation" class="fa-regular fa-eye password-toggle" onclick="togglePassword('passwordConfirmation', 'togglePasswordConfirmation')"></i>
                            </div>
                            <button class="btn btn-primary w-100">REGISTER</button>
                        </form>
                        <!-- Menampilkan error validasi -->
                        @if ($errors->any())
                             <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger err mt-3">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <p class="mt-3">
                            Sudah punya akun?
                            <a href="{{ route('login') }}">Login di sini &raquo;</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.err').forEach(error => {
        error.style.cursor = 'pointer'; 
        error.addEventListener('click', () => {
            error.remove();   
        });
    });
});


// Toggle password visibility
// const togglePassword = document.querySelector('#togglePassword');
//         const password = document.querySelector('#password');
        
//         togglePassword.addEventListener('click', function (e) {
//             // toggle the type attribute
//             const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
//             password.setAttribute('type', type);
            
//             // toggle the eye icon
//             this.classList.toggle('fa-eye');
//             this.classList.toggle('fa-eye-slash');
//         });


function togglePassword(inputId, toggleIcon) {
  const passwordInput = document.getElementById(inputId);
  const icon = document.getElementById(toggleIcon);

  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}
</script>

</body>
</html>
