<header class="navbar navbar-expand-md navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid">
        {{-- Brand / Judul Aplikasi --}}
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            {{ config('app.name', 'Portal Sekolah') }}
        </a>

        <div class="d-flex align-items-center ms-auto gap-3">
            {{-- Dark Mode Toggle --}}
            <button id="theme-toggle" class="btn btn-link p-0" title="Toggle Dark Mode">
                <i id="theme-icon" class="bi bi-moon-stars fs-5"></i>
            </button>

            {{-- Profil User --}}
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                    <span class="fw-semibold me-2">{{ Auth::user()->name }}</span>
                    <img src="https://randomuser.me/api/portraits/men/32.jpg"
                         alt="Profil" class="rounded-circle border" style="height:36px; width:36px;">
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="bi bi-person me-2"></i>Profil</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
