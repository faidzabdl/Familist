<nav class="navbar navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand hover-text-dark text-light" href="{{ route('tasks.index') }}">
            FamiList
        </a>

        @if (request()->routeIs('tasks.index'))
        <form 
            action="{{ route('tasks.index') }}" 
            method="GET" 
            class="d-none d-lg-flex ms-auto pe-2" 
            role="search"
        >
            <input 
                class="form-control me-2 rounded-pill search-input" 
                type="search" 
                name="search" 
                placeholder="Search" 
                aria-label="Search" 
                value="{{ request('search') }}"
            >
            <button class="btn btn-primary rounded-pill px-3" type="submit">Search</button>
        </form>
        @endif

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php $user = Auth::user(); ?>
        <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
                <a href="{{ route('profile.index') }}" class="offcanvas-title text-decoration-none text-light hover-text-primary d-flex align-items-center" id="offcanvasDarkNavbarLabel">
                    <img 
                        src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff' }}" 
                        alt="Profile Picture" 
                        class="rounded-circle me-2" 
                        style="width: 35px; height: 35px; object-fit: cover;">
                    {{ $slot }}
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                @if (request()->routeIs('tasks.index'))
                <form 
                    action="{{ route('tasks.index') }}" 
                    method="GET" 
                    class="d-flex d-lg-none mb-3" 
                    role="search"
                >
                    <input 
                        class="form-control me-2 rounded-pill search-input" 
                        type="search" 
                        name="search" 
                        placeholder="Search" 
                        aria-label="Search" 
                        value="{{ request('search') }}"
                    >
                    <button class="btn btn-primary rounded-pill px-3" type="submit">Search</button>
                </form>
                @endif
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('account.setting') }}">
                            <i class="fa-solid fa-gear"></i> Setting Account
                        </a>
                        <a class="nav-link" href="{{ route('profile.leaderboard') }}">
                            <i class="fa-solid fa-crown"></i> Leaderboard
                        </a>
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    .navbar {
    transition: background-color 0.3s ease, padding 0.3s ease;
    font-family: 'Roboto', sans-serif;
    background-color: #003366;
}

.navbar-brand {
    font-size: 1.5rem;
    font-weight: 700;
    transition: color 0.3s ease;
}

.navbar-brand:hover {
    color: #ff7f50;
}

.search-input {
    border-radius: 50px;
    padding-left: 15px;
}

.btn-primary {
    border-radius: 50px;
    padding: 8px 15px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.offcanvas-header {
    background-color: #0056b3;
    border-bottom: 1px solid #033b77;
}

.offcanvas-title {
    font-size: 1.25rem;
    font-weight: 600;
}

.offcanvas-body {
    padding-top: 20px;
}

.navbar-nav .nav-link {
    color: #d1d1d1;
    transition: color 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: #ff7f50;
}

.text-light {
    color: #ddd !important;
}

.hover-text-dark:hover {
    color: #f0f0f0;
}

.hover-text-primary:hover {
    color: #ff7f50;
}

</style>