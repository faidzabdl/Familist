<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="icon" href="{{ asset('images/favicon(2)-32x32.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .leaderboard-container {
            max-width: 500px;
            margin: auto;
        }
        .leaderboard-card {
            display: flex;
            align-items: center;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            transition: 0.3s;
        }
        .leaderboard-card:hover {
            transform: scale(1.02);
        }
        .rank {
            font-size: 20px;
            font-weight: bold;
            width: 40px;
            text-align: center;
            color: #fff;
            background: #007bff;
            border-radius: 50%;
            height: 40px;
            line-height: 40px;
        }
        .profile-pic {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 15px;
        }
        .leaderboard-info {
            flex: 1;
        }
        .leaderboard-info h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        .leaderboard-info p {
            margin: 0;
            color: gray;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="container py-5 leaderboard-container">

        @if (Auth::check())
        <x-navbar>{{ Auth::user()->name }}</x-navbar>
        @endif

        <h2 class="text-center mb-4 mt-4">🏆 Leaderboard</h2>

            @foreach($user as $index => $user)
            <div class="leaderboard-card">
                <span class="rank">{{ $index + 1 }}</span>
                <img src="{{ asset('storage/' . $user->profile_pic) }}" alt="Profile" class="profile-pic">
                <div class="leaderboard-info">
                    <h5>{{ $user->name }}</h5>
                    <p>Score: {{ $user->skor }}</p>
                    <p>Tittle: {{ $user->tittle }}</p>
                </div>
            </div>
            @endforeach


       

        <!-- Tambahkan lebih banyak item jika diperlukan -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
