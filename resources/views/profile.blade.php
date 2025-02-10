<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" href="{{ asset('images/favicon(2)-32x32.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
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

        .user-info p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .user-info p.title {
            font-weight: 600;
        }

        .user-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }

        /* .btn-edit {
            background-color: #007bff;
            color: white;
            border-radius: 30px;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-edit:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .btn-edit:active {
            transform: translateY(1px);
        } */

        .container {
            max-width: 800px;
        }

        .text-muted {
            font-size: 1.1rem;
        }

        .navbar {
            background-color: #343a40; /* Dark color for navbar */
        }

        .navbar .navbar-brand {
            color: #fff;
        }

        .navbar .navbar-brand:hover {
            color: #ddd;
        }

        .navbar-nav a {
            color: #fff;
        }

        .navbar-nav a:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <x-navbar>{{ $user->name }}</x-navbar>
        <div class="card shadow-lg mt-5">
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success" id="success-alert">
                    {{ session('success') }}
                </div>
                @endif
                
                @if (session('error'))
                <div class="alert alert-danger" id="error-alert">
                    {{ session('error') }}
                </div>
                @endif

                <div class="text-center mb-4">
                  
                    <img src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://via.placeholder.com/150' }}" alt="Profile Picture" class="profile-pic" id="profilePreview">
                    <h3 class="mt-3">{{ $user->name }}</h3>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
                
                <div class="user-info">
                    <p>Skor: <strong>{{ $user->skor }}</strong></p>
                    <p>Title: <strong>{{ $user->tittle ?? 'Anda belum mendapatkan title' }}</strong></p>
                </div>
                
                {{-- <div class="text-center mt-4">
                    <a href="{{ route('account.setting') }}" class="btn-edit">Edit Profile</a>
                </div> --}}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.alert').forEach(function (alert) {
            alert.addEventListener('click', function () {
                alert.style.display = 'none'; 
            });
        });
    </script>
</body>
</html>
