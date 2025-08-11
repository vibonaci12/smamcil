<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Sekolah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .slider-container {
            position: relative;
            width: 100%;
            max-width: 700px;
            height: 380px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: #fff;
        }
        .slider-image {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .slider-image.active {
            opacity: 1;
        }
        @media (max-width: 768px) {
            .slider-container {
                height: 220px;
                max-width: 95%;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-green-50">

    {{-- Navbar --}}
    <nav class="flex justify-between items-center px-6 py-4 bg-white shadow-md">
        <h1 class="text-lg md:text-2xl font-bold text-green-700 tracking-wide">
            Portal Sekolah
        </h1>
        <div class="flex gap-3">
            <a href="{{ route('login') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">Masuk</a>
            {{-- <a href="{{ route('register') }}" class="px-4 py-2 border border-green-600 text-green-700 rounded-lg hover:bg-green-600 hover:text-white transition">Daftar</a> --}}
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="flex flex-col-reverse md:flex-row items-center justify-between 
                    flex-grow w-full px-6 md:px-36 xl:px-48 py-12 bg-white">
        
        {{-- Teks --}}
        <div class="max-w-md text-center md:text-left">
            <h2 class="text-3xl md:text-4xl font-extrabold text-green-800 mb-4 animate__animated animate__fadeInLeft">
                Selamat Datang di Portal Sekolah
            </h2>
            <p class="text-green-700 text-lg md:text-xl leading-relaxed animate__animated animate__fadeInLeft animate__delay-1s">
                Sistem terpadu untuk guru dan siswa dengan pengelolaan jadwal, materi, dan pengumuman yang modern dan mudah diakses.
            </p>
        </div>

        {{-- Slider Gambar --}}
        <div class="slider-container mt-6 md:mt-0">
            <img src="https://images.unsplash.com/photo-1600880292089-90a7e086ee0c?auto=format&fit=crop&w=800&q=80" class="slider-image active" alt="Sekolah 1">
            <img src="https://images.unsplash.com/photo-1596495578065-5eebc0f0f0ff?auto=format&fit=crop&w=800&q=80" class="slider-image" alt="Sekolah 2">
            <img src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=800&q=80" class="slider-image" alt="Sekolah 3">
        </div>
    </section>

    {{-- Footer --}}
    <footer class="text-center py-4 text-sm bg-green-700 text-green-100">
        &copy; {{ date('Y') }} Portal Sekolah
    </footer>

    {{-- Slider Script --}}
    <script>
        const slides = document.querySelectorAll('.slider-image');
        let currentIndex = 0;

        setInterval(() => {
            slides[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % slides.length;
            slides[currentIndex].classList.add('active');
        }, 4000);
    </script>
</body>
</html>
