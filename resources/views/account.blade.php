<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting Account</title>
    <link rel="icon" href="{{ asset('images/favicon(2)-32x32.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f8fc;
        }

        .profile-pic {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #007bff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-pic:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15);
        }

        .card {
            border-radius: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-body {
            padding: 30px;
            color: #555;
        }

        .alert {
            cursor: pointer;
            border-radius: 10px;
            transition: opacity 0.3s ease;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert:hover {
            opacity: 0.8;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-primary:focus {
            box-shadow: none;
        }

        .heading {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
        }

        .user-info p {
            font-size: 1.2rem;
            color: #555;
        }

        .user-info p.title {
            font-weight: 700;
            color: #007bff;
        }

    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <x-navbar>{{ $user->name }}</x-navbar>
        <div class="card shadow-lg mt-5">
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success cursor-pointer" id="success-alert">
                    {{ session('success') }}
                </div>
                @endif
                
                @if (session('error'))
                <div class="alert alert-danger cursor-pointer" id="error-alert">
                    {{ session('error') }}
                </div>
                @endif

                <div class="text-center mb-4">
                    <img src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://via.placeholder.com/150' }}" alt="Profile Picture" class="profile-pic" id="profilePreview">
                    <h3 class="mt-3">{{ $user->name }}</h3>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>

                <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="profilePicture" class="form-label">Ganti Foto Profile</label>
                        <input type="file" class="form-control" id="profilePicture" name="profile_pic" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="changeUsername" class="form-label">Ganti Nama</label>
                        <input type="text" class="form-control" id="changeUsername" placeholder="Masukkan nama baru" name="name">
                    </div>

                    <div class="mb-3">
                        <label for="changeEmail" class="form-label">Ganti Email</label>
                        <input type="email" class="form-control" id="changeEmail" placeholder="Masukkan email baru" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="changePassword" class="form-label">Ganti Password</label>
                        <input type="password" class="form-control" id="changePassword" placeholder="Masukkan paswword baru" name="password">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Simpan perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.alert').forEach(function (alert) {
            alert.style.cursor = 'pointer';
            alert.addEventListener('click', function () {
                alert.style.display = 'none';
            });
        });
    </script>

    <script>
        document.getElementById('profilePicture').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>

</body>
</html>
