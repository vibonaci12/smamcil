<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            background: #f8fafc; 
            font-family: 'Inter', sans-serif;
        }
        .sidebar { 
            min-height: 100vh; 
            transition: width 0.2s; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link.active { 
            background: rgba(255,255,255,0.2); 
            color: white !important; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link { 
            color: rgba(255,255,255,0.8) !important; 
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover { 
            background: rgba(255,255,255,0.1); 
            color: white !important; 
            transform: translateX(5px);
        }
        .sidebar-collapsed { 
            width: 70px !important; 
        }
        .sidebar-collapsed .nav-link-title { 
            display: none; 
        }
        .sidebar-collapsed .nav-link { 
            justify-content: center; 
        }
        .nav-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .nav-link-title {
            font-weight: 500;
        }
        @media (min-width: 768px) {
            .sidebar { 
                width: 240px; 
                position: fixed; 
                top: 0; 
                left: 0; 
                z-index: 1030; 
            }
            .main-content { 
                margin-left: 240px; 
                transition: margin-left 0.2s; 
                padding-top: 70px; 
            }
            .sidebar-collapsed ~ .main-content { 
                margin-left: 70px !important; 
            }
        }
        @media (max-width: 767.98px) {
            .main-content { 
                padding-top: 70px; 
                margin-left: 0 !important; 
                overflow-x: auto; 
            }
        }
        .topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 240px;
            height: 60px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            z-index: 999;
            transition: left 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .sidebar-collapsed ~ .topbar {
            left: 70px;
        }
        @media (max-width: 768px) {
            .topbar {
                left: 0;
            }
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            background-color: #f8f9fa;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        .alert {
            border-radius: 0.5rem;
            border: none;
        }
        .form-control, .form-select {
            border-radius: 0.375rem;
        }
        .btn {
            border-radius: 0.375rem;
        }
    </style>
    </head>
<body>
<!-- Topbar -->
<nav class="navbar navbar-expand bg-white border-bottom shadow-sm sticky-top">
    <div class="container-fluid justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <button id="sidebarCollapseBtn" class="btn btn-outline-primary d-none d-md-inline me-2" type="button"><i class="bi bi-list"></i></button>
            <button class="btn btn-outline-primary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"><i class="bi bi-list"></i></button>
            <span class="navbar-brand mb-0 h1">SchoolPortal</span>
        </div>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profil" class="rounded-circle me-2" style="height:36px;">
                <span class="fw-semibold d-none d-md-inline">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Desktop -->
        <nav id="sidebar" class="sidebar col-md-3 col-lg-2 d-none d-md-block bg-white border-end shadow-sm p-0">
            <div class="position-sticky pt-3">
                <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom border-light">
                    <h5 class="text-white mb-0">
                        <i class="bi bi-mortarboard me-2"></i>
                        <span class="sidebar-title">School Portal</span>
                    </h5>
                    </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>
                    
                    @if(Auth::user()->role === 'admin')
                        <!-- Menu untuk Admin -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('siswa*') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
                                <i class="nav-icon bi bi-people"></i>
                                <span class="nav-link-title">Siswa</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('guru*') ? 'active' : '' }}" href="{{ route('guru.index') }}">
                                <i class="nav-icon bi bi-person-badge"></i>
                                <span class="nav-link-title">Guru</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('kelas*') ? 'active' : '' }}" href="{{ route('kelas.index') }}">
                                <i class="nav-icon bi bi-easel2"></i>
                                <span class="nav-link-title">Kelas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('jadwal*') ? 'active' : '' }}" href="{{ route('jadwal.index') }}">
                                <i class="nav-icon bi bi-calendar-event"></i>
                                <span class="nav-link-title">Jadwal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('pengumuman*') ? 'active' : '' }}" href="{{ route('pengumuman.index') }}">
                                <i class="nav-icon bi bi-megaphone"></i>
                                <span class="nav-link-title">Pengumuman</span>
                            </a>
                        </li>
                    @elseif(Auth::user()->role === 'guru')
                        <!-- Menu untuk Guru -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('jadwal-mengajar') ? 'active' : '' }}" href="{{ route('guru.jadwal') }}">
                                <i class="nav-icon bi bi-calendar-event"></i>
                                <span class="nav-link-title">Jadwal Mengajar</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('absensi*') ? 'active' : '' }}" href="{{ route('absensi.index') }}">
                                <i class="nav-icon bi bi-clipboard-check"></i>
                                <span class="nav-link-title">Absensi Terintegrasi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('nilai*') ? 'active' : '' }}" href="{{ route('nilai.index') }}">
                                <i class="nav-icon bi bi-star"></i>
                                <span class="nav-link-title">Nilai</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('materi*') ? 'active' : '' }}" href="{{ route('materi.index') }}">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <span class="nav-link-title">Materi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('pengumuman-kelas*') ? 'active' : '' }}" href="{{ route('pengumuman-kelas.index') }}">
                                <i class="nav-icon bi bi-megaphone"></i>
                                <span class="nav-link-title">Pengumuman Kelas</span>
                            </a>
                        </li>
                    @elseif(Auth::user()->role === 'siswa')
                        <!-- Menu untuk Siswa -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('jadwal-siswa') ? 'active' : '' }}" href="{{ route('siswa.jadwal') }}">
                                <i class="nav-icon bi bi-calendar-event"></i>
                                <span class="nav-link-title">Jadwal Pelajaran</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('nilai-siswa') ? 'active' : '' }}" href="{{ route('siswa.nilai') }}">
                                <i class="nav-icon bi bi-star"></i>
                                <span class="nav-link-title">Nilai</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('absensi-siswa') ? 'active' : '' }}" href="{{ route('siswa.absensi') }}">
                                <i class="nav-icon bi bi-clipboard-check"></i>
                                <span class="nav-link-title">Absensi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('materi-siswa') ? 'active' : '' }}" href="{{ route('siswa.materi') }}">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <span class="nav-link-title">Materi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('pengumuman-siswa') ? 'active' : '' }}" href="{{ route('siswa.pengumuman') }}">
                                <i class="nav-icon bi bi-megaphone"></i>
                                <span class="nav-link-title">Pengumuman</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        <!-- Sidebar Mobile (Offcanvas) -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-0">
                <ul class="nav flex-column p-3">
                    <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item"><a href="{{ route('siswa.index') }}" class="nav-link"><i class="bi bi-people me-2"></i>Siswa</a></li>
                        <li class="nav-item"><a href="{{ route('guru.index') }}" class="nav-link"><i class="bi bi-person-badge me-2"></i>Guru</a></li>
                        <li class="nav-item"><a href="{{ route('kelas.index') }}" class="nav-link"><i class="bi bi-easel2 me-2"></i>Kelas</a></li>
                        <li class="nav-item"><a href="{{ route('jadwal.index') }}" class="nav-link"><i class="bi bi-calendar-event me-2"></i>Jadwal</a></li>
                        <li class="nav-item"><a href="{{ route('pengumuman.index') }}" class="nav-link"><i class="bi bi-megaphone me-2"></i>Pengumuman</a></li>
                    @elseif(Auth::user()->role === 'guru')
                        <li class="nav-item"><a href="{{ route('guru.jadwal') }}" class="nav-link"><i class="bi bi-calendar-event me-2"></i>Jadwal Mengajar</a></li>
                        <li class="nav-item"><a href="{{ route('absensi.index') }}" class="nav-link"><i class="bi bi-clipboard-check me-2"></i>Absensi</a></li>
                        <li class="nav-item"><a href="{{ route('nilai.index') }}" class="nav-link"><i class="bi bi-star me-2"></i>Nilai</a></li>
                        <li class="nav-item"><a href="{{ route('materi.index') }}" class="nav-link"><i class="bi bi-file-earmark-text me-2"></i>Materi</a></li>
                        <li class="nav-item"><a href="{{ route('pengumuman-kelas.index') }}" class="nav-link"><i class="bi bi-megaphone me-2"></i>Pengumuman Kelas</a></li>
                    @elseif(Auth::user()->role === 'siswa')
                        <li class="nav-item"><a href="{{ route('siswa.jadwal') }}" class="nav-link"><i class="bi bi-calendar-event me-2"></i>Jadwal Pelajaran</a></li>
                        <li class="nav-item"><a href="{{ route('siswa.nilai') }}" class="nav-link"><i class="bi bi-star me-2"></i>Nilai</a></li>
                        <li class="nav-item"><a href="{{ route('siswa.absensi') }}" class="nav-link"><i class="bi bi-clipboard-check me-2"></i>Absensi</a></li>
                        <li class="nav-item"><a href="{{ route('siswa.materi') }}" class="nav-link"><i class="bi bi-file-earmark-text me-2"></i>Materi</a></li>
                        <li class="nav-item"><a href="{{ route('siswa.pengumuman') }}" class="nav-link"><i class="bi bi-megaphone me-2"></i>Pengumuman</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- Main Content -->
        <main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            @yield('content')
        </main>
    </div>
</div>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar collapse/expand (desktop)
const sidebar = document.getElementById('sidebar');
const collapseBtn = document.getElementById('sidebarCollapseBtn');

function setSidebarState(collapsed) {
    if (collapsed) {
        sidebar.classList.add('sidebar-collapsed');
        document.querySelector('.main-content').style.marginLeft = '70px';
    } else {
        sidebar.classList.remove('sidebar-collapsed');
        document.querySelector('.main-content').style.marginLeft = '240px';
    }
    localStorage.setItem('sidebar-collapsed', collapsed ? '1' : '0');
}

if (sidebar && collapseBtn) {
    // Restore state
    setSidebarState(localStorage.getItem('sidebar-collapsed') === '1');
    
    collapseBtn.onclick = function() {
        setSidebarState(!sidebar.classList.contains('sidebar-collapsed'));
    };
}
</script>
    </body>
</html>
