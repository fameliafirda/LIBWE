{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Perpustakaan SDN Berat Wetan 1</title>
    
    {{-- Vue JS --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ============================================================
           1. COLOR PALETTE & RESET
           ============================================================ */
        :root {
            /* PALET WARNA CERIA & PASTEL ESTETIK */
            --color-bg: #fbf8cc;      /* Kuning krem lembut untuk background utama */
            --color-text: #2b2d42;    /* Navy gelap untuk teks utama agar ramah anak dan terbaca */

            --color-1: #ffcfd2;       /* Pink pastel yang lembut */
            --color-2: #ffb703;       /* KUNING SUNSHINE CERIA */
            --color-3: #ffafcc;       /* Hot Pink / Bubblegum Pink yang ceria */
            --color-4: #8ecae6;       /* BIRU LANGIT CERAH */
            --color-5: #b5179e;       /* UNGU UNIK / MAGENTA */

            /* EFEK KACA PUTIH (LIGHT GLASSMORPHISM) */
            --glass: rgba(255, 255, 255, 0.55);        
            --glass-border: rgba(255, 255, 255, 0.6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            overflow-x: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            scroll-behavior: smooth;
        }

        /* Y2K Grid Background - Disesuaikan untuk Light Mode */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: 
                linear-gradient(rgba(43, 45, 66, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(43, 45, 66, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -2;
            pointer-events: none;
        }

        body {
            opacity: 0;
            animation: fadeIn 1s ease-in-out forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        ::selection {
            background: var(--color-3);
            color: var(--color-text);
        }

        /* Floating Background Glow with Pulse Animation */
        .bg-glow {
            position: fixed;
            width: 50vw; height: 50vw;
            border-radius: 50%;
            filter: blur(150px);
            z-index: -1;
            opacity: 0.35; /* Dinaikkan sedikit agar warnanya lebih keluar di bg putih */
            pointer-events: none;
            animation: pulseGlow 12s infinite alternate ease-in-out;
        }

        /* Memberikan delay berbeda agar glow bergerak tidak bersamaan */
        .bg-glow:nth-child(1) { animation-delay: 0s; }
        .bg-glow:nth-child(2) { animation-delay: -4s; }
        .bg-glow:nth-child(3) { animation-delay: -8s; }

        @keyframes pulseGlow {
            0% { transform: scale(1) translate(0, 0); opacity: 0.25; }
            50% { transform: scale(1.15) translate(30px, -30px); opacity: 0.45; }
            100% { transform: scale(0.9) translate(-30px, 30px); opacity: 0.25; }
        }

        /* ============================================================
           2. NAVIGASI (FLOATING BAR)
           =========================================================== */
        .libwe-nav {
            position: fixed;
            top: 20px; 
            left: 50%; 
            transform: translateX(-50%);
            width: 90%;
            max-width: 1000px;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            z-index: 1000;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            animation: slideDownNav 1s ease-out forwards;
        }

        @keyframes slideDownNav {
            0% { transform: translate(-50%, -100px); opacity: 0; }
            100% { transform: translate(-50%, 0); opacity: 1; }
        }

        .libwe-nav.scrolled {
            top: 10px;
            background: rgba(255, 255, 255, 0.85);
            border-color: rgba(142, 202, 230, 0.5);
            box-shadow: 0 10px 25px rgba(142, 202, 230, 0.2);
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-container img {
            width: 30px;
            height: auto;
            filter: drop-shadow(0 0 5px rgba(43, 45, 66, 0.1));
            transition: 0.5s ease;
        }

        .brand-container:hover img {
            transform: rotate(15deg) scale(1.1);
        }

        .brand-libwe {
            font-family: 'Unbounded', sans-serif;
            font-size: 1.4rem;
            font-weight: 900;
            background: linear-gradient(to right, var(--color-4), var(--color-5));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .nav-links { 
            display: flex; 
            gap: 30px; 
            align-items: center;
        }

        .nav-links a { 
            text-decoration: none; 
            color: var(--color-text); 
            font-weight: 700; 
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .nav-links a:hover { 
            color: var(--color-3); 
            text-shadow: 0 0 10px rgba(255, 175, 204, 0.5); 
        }

        .btn-login {
            background: var(--color-4);
            color: var(--color-text) !important;
            padding: 8px 22px;
            border-radius: 30px;
            border: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800 !important;
            font-size: 0.8rem;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            box-shadow: 0 0 15px rgba(142, 202, 230, 0.4);
        }

        .btn-login:hover {
            background: var(--color-3);
            color: var(--color-text) !important;
            box-shadow: 0 0 20px rgba(255, 175, 204, 0.6);
            transform: scale(1.05);
        }

        .hamburger {
            display: none;
            width: 25px;
            height: 18px;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
            z-index: 1001;
        }

        .hamburger span {
            display: block;
            width: 100%;
            height: 2px;
            background-color: var(--color-4);
            transition: all 0.3s ease;
        }

        /* ============================================================
           3. HERO SECTION (FIT IN 1 SCREEN / 100vh)
           =========================================================== */
        .hero {
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding-top: 60px;
            z-index: 1;
        }

        /* Running Text Y2K Dipertebal & Futuristik */
        .marquee-container {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .marquee-content {
            display: flex;
            animation: scrollText 35s linear infinite;
        }

        .marquee-item {
            font-family: 'Syne', sans-serif;
            font-size: 15vw;
            font-weight: 900;
            font-style: italic;
            white-space: nowrap;
            color: transparent;
            -webkit-text-stroke: 2.5px rgba(142, 202, 230, 0.4); /* Menggunakan tone biru cerah */
            text-transform: uppercase;
            padding-right: 50px;
            letter-spacing: 3px;
        }

        @keyframes scrollText {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Gambar Buku */
        .hero-img-container {
            height: 35vh;
            max-height: 280px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2vh;
            z-index: 5;
            animation: floatHero 5s ease-in-out infinite;
        }

        .hero-img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
        }

        @keyframes floatHero {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Animasi Slide-Up Fade untuk Konten Hero */
        .hero-content {
            text-align: center;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 20px;
        }

        .hero-content h1 { 
            font-family: 'Unbounded', sans-serif; 
            font-size: clamp(1.8rem, 4vw, 3.5rem); 
            color: var(--color-text); 
            margin: 0; 
            line-height: 1.2;
            text-shadow: 0 5px 15px rgba(0,0,0,0.05); /* Shadow diperhalus untuk tema terang */
            opacity: 0;
            animation: slideUpFade 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.3s;
        }

        .hero-content h1 span {
            color: var(--color-5); /* Menggunakan magenta agar kontras dengan background */
            text-shadow: 0 0 20px rgba(181, 23, 158, 0.2);
        }

        .hero-content p {
            font-size: clamp(0.85rem, 1.5vw, 1.1rem);
            color: var(--color-text);
            font-weight: 500;
            margin: 2vh 0 4vh;
            max-width: 600px;
            opacity: 0;
            animation: slideUpFade 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.5s;
        }

        .hero-content .btn-wrapper {
            opacity: 0;
            animation: slideUpFade 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.7s;
        }

        @keyframes slideUpFade {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: none; }
        }

        /* Tombol Y2K Style */
        .btn-hero {
            display: inline-block;
            background: var(--color-2); /* Kuning Sunshine */
            color: var(--color-text);
            padding: 15px 40px;
            border-radius: 50px;
            font-family: 'Unbounded', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            text-decoration: none;
            text-transform: uppercase;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid var(--color-2);
            box-shadow: 0 5px 15px rgba(255, 183, 3, 0.3), inset 0 0 10px rgba(255,255,255,0.5);
            cursor: pointer;
        }

        .btn-hero:hover {
            background: transparent;
            color: var(--color-2);
            box-shadow: 0 0 30px rgba(255, 183, 3, 0.6);
            transform: scale(1.05);
        }

        /* ============================================================
           4. SECTIONS (Informasi, Visi Misi, dll)
           =========================================================== */
        section:not(.hero) {
            padding: 100px 20px;
            position: relative;
            z-index: 2;
            scroll-margin-top: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-subtitle {
            letter-spacing: 4px; 
            color: var(--color-5); 
            font-weight: 800; 
            font-size: 0.8rem; 
            text-transform: uppercase;
            background: rgba(181, 23, 158, 0.1);
            padding: 6px 15px;
            border-radius: 20px;
            border: 1px solid rgba(181, 23, 158, 0.2);
        }

        .section-title {
            font-family: 'Unbounded', sans-serif; 
            font-size: clamp(1.8rem, 3.5vw, 2.5rem); 
            margin-top: 20px;
            color: var(--color-text);
            text-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .glass-card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            padding: 50px;
            border-radius: 30px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            width: 100%;
            max-width: 900px;
            transition: 0.5s ease;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05); /* Shadow diperhalus */
            /* Animasi Ambient di Border Card */
            animation: borderGlow 8s infinite alternate;
        }

        @keyframes borderGlow {
            0% { border-color: rgba(255, 255, 255, 0.6); }
            50% { border-color: rgba(142, 202, 230, 0.5); }
            100% { border-color: rgba(255, 175, 204, 0.5); }
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.1);
            border-color: var(--color-3) !important;
            animation: none; /* Matikan animasi ambient saat di-hover */
        }

        .text-content p {
            line-height: 1.8;
            font-size: 1rem;
            color: var(--color-text);
            margin-bottom: 20px;
        }

        .text-content strong {
            color: var(--color-5); /* Magenta */
            font-family: 'Unbounded', sans-serif;
            font-size: 1.05rem;
        }

        ul, ol {
            padding-left: 20px;
            line-height: 1.8;
            color: var(--color-text);
            margin-bottom: 20px;
        }

        ul li { margin-bottom: 12px; }
        ol li { margin-bottom: 12px; font-weight: 500; }

        /* Pustakawan */
        .pustakawan-card {
            text-align: center;
            max-width: 400px;
        }

        .pustakawan-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 25px;
            border: 4px solid var(--color-3);
            box-shadow: 0 0 20px rgba(255, 175, 204, 0.4);
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .pustakawan-card img:hover {
            transform: scale(1.1) rotate(5deg);
            border-color: var(--color-4);
            box-shadow: 0 0 30px rgba(142, 202, 230, 0.6);
        }

        .pustakawan-card p {
            font-size: 1rem;
            margin-bottom: 10px;
            color: var(--color-text);
        }

        /* Denah & Lokasi */
        .maps-wrapper {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            margin-bottom: 30px;
        }

        iframe {
            width: 100%;
            height: 350px;
            /* Filter dark mode dihapus agar peta terlihat normal dan cerah */
            filter: none; 
            display: block;
        }

        .alamat-info {
            background: var(--glass);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 35px;
            border: 1px solid var(--glass-border);
        }

        .alamat-info p {
            margin-bottom: 10px;
            color: var(--color-text);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .alamat-info i {
            color: var(--color-5);
            margin-right: 8px;
        }

        .denah-img {
            width: 100%;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            opacity: 0.9;
            transition: 0.4s;
        }

        .denah-img:hover {
            opacity: 1;
            border-color: var(--color-1);
        }

        footer {
            text-align: center;
            padding: 30px;
            color: var(--color-text);
            font-size: 0.85rem;
            font-weight: 700;
            border-top: 1px solid var(--glass-border);
            background: transparent;
        }

        /* ============================================================
           5. RESPONSIVE MOBILE
           =========================================================== */
        @media (max-width: 992px) {
            .glass-card { padding: 40px 30px; }
        }

        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .btn-login { display: none; }
            
            .nav-links {
                position: absolute;
                top: 70px; left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.95); /* Diubah ke light mode */
                backdrop-filter: blur(20px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                border-radius: 20px;
                border: 1px solid var(--glass-border);
                padding: 25px 0;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
            }

            .nav-links.active { 
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }
            
            .nav-links .btn-login {
                display: block;
                margin-top: 15px;
            }

            .glass-card { border-radius: 20px; padding: 30px 20px; }
            .hero-img-container { height: 30vh; }
        }

        @media (max-width: 480px) {
            .btn-hero { padding: 12px 30px; font-size: 0.9rem; }
            .hero-content h1 { font-size: 1.8rem; }
            .hero-content p { margin: 2vh 0 3vh; }
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="bg-glow" style="top: -10%; right: -5%; background: var(--color-1);"></div>
        <div class="bg-glow" style="bottom: 10%; left: -5%; background: var(--color-4);"></div>
        <div class="bg-glow" style="top: 40%; left: 10%; background: var(--color-3);"></div>

        <nav class="libwe-nav" :class="{scrolled: isScrolled}">
            <div class="brand-container">
                <img src="{{ asset('web-perpus/img/Open_Book_Vector_Illustration_Flat_Logo_Stock_Vector_-_Illustration_of_flat__minimal__187678563__3_-removebg-preview - Copy.png') }}" alt="logo">
                <div class="brand-libwe">LIBWE</div>
            </div>
            
            <div class="hamburger" @click="toggleMenu">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="nav-links" :class="{active: isMenuActive}">
                <a href="#informasi" @click="closeMenu">Informasi</a>
                <a href="#visi" @click="closeMenu">Visi Misi</a>
                <a href="#pustakawan" @click="closeMenu">Pustakawan</a>
                <a href="#denah" @click="closeMenu">Lokasi</a>
                <button class="btn-login" onclick="window.location.href='{{ route('login') }}'">MASUK</button>
            </div>
        </nav>

        <section class="hero">
            <div class="marquee-container">
                <div class="marquee-content">
                    <div class="marquee-item">Perpustakaan Online SDN Berat Wetan 1 •</div>
                    <div class="marquee-item">Perpustakaan Online SDN Berat Wetan 1 •</div>
                </div>
            </div>

            <div class="hero-img-container">
                <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Main Book">
            </div>

            <div class="hero-content">
                <h1>Selamat datang di <br><span>Perpustakaan Online</span></h1>
                <p>SDN Berat Wetan 1 <br>Membangun generasi cerdas dan berbudaya literasi.</p>
                <div class="btn-wrapper">
                    <button class="btn-hero" onclick="window.location.href='{{ route('katalog') }}'">TELUSURI BUKU</button>
                </div>
            </div>
        </section>

        <section id="informasi">
            <div class="section-header">
                <span class="section-subtitle">Tentang Kami</span>
                <h2 class="section-title">INFORMASI</h2>
            </div>
            
            <div class="glass-card text-content">
                <p style="text-align: justify">Perpustakaan SDN Berat Wetan 1 didirikan sebagai bagian dari komitmen sekolah untuk meningkatkan minat baca dan literasi siswa sejak dini. Berdiri sejak awal tahun 2005, perpustakaan ini awalnya hanya memiliki koleksi buku bacaan dasar dan beberapa rak sederhana yang ditempatkan di salah satu sudut ruang kelas. Namun, seiring waktu dan dukungan dari pihak sekolah, guru, serta orang tua murid, perpustakaan terus mengalami perkembangan baik dari segi fasilitas maupun jumlah koleksi buku.</p>
                <p style="text-align: justify">Dalam beberapa tahun terakhir, perpustakaan SDN Berat Wetan 1 telah mengalami renovasi dan penataan ulang, menjadikannya ruang yang lebih nyaman, bersih, dan menyenangkan untuk belajar. Kini, perpustakaan tidak hanya menyediakan buku bacaan fiksi dan non-fiksi, tetapi juga buku referensi, ensiklopedia anak, kamus, dan bahkan koleksi bergambar yang mendukung proses pembelajaran tematik di sekolah dasar. Dengan tambahan sentuhan digital dan penataan yang lebih modern, perpustakaan menjadi pusat aktivitas literasi yang aktif di lingkungan sekolah.</p>
                
                <br>
                <p style="text-align: justify;"><strong>Tujuan:</strong> Tujuan utama dari perpustakaan SDN Berat Wetan 1 adalah mendukung proses pembelajaran di sekolah dengan menyediakan sumber informasi yang lengkap dan mudah diakses oleh seluruh siswa dan guru. Perpustakaan juga bertujuan untuk:</p>
                
                <ul style="text-align: justify;">
                    <li>Menumbuhkan minat baca siswa sejak usia dini melalui penyediaan bacaan yang menarik dan bervariasi.</li>
                    <li>Meningkatkan budaya literasi di lingkungan sekolah dengan mengadakan kegiatan-kegiatan seperti pojok baca, lomba membaca, dan mendongeng.</li>
                    <li>Mendukung pembelajaran aktif dan mandiri, di mana siswa dapat mencari informasi tambahan secara mandiri untuk menunjang tugas dan pelajaran.</li>
                    <li>Menjadi ruang edukatif yang menyenangkan, di mana siswa merasa nyaman untuk membaca, belajar, dan mengeksplorasi pengetahuan.</li>
                    <li>Dengan adanya perpustakaan ini, diharapkan SDN Berat Wetan 1 tidak hanya menjadi tempat belajar akademik, tetapi juga tempat untuk membangun karakter, imajinasi, dan kecintaan terhadap ilmu pengetahuan.</li>
                </ul>
                
                <hr style="border: 0; border-top: 1px dashed rgba(43,45,66,0.15); margin: 30px 0;">
                
                <p><i class="fas fa-map-marker-alt" style="color: var(--color-4); margin-right: 10px; width: 20px; text-align: center;"></i> <strong>Alamat:</strong> Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur</p>
                <p><i class="fas fa-envelope" style="color: var(--color-3); margin-right: 10px; width: 20px; text-align: center;"></i> <strong>Email:</strong> berat.wetan1@gmail.com</p>
                <p><i class="fas fa-id-card" style="color: var(--color-1); margin-right: 10px; width: 20px; text-align: center;"></i> <strong>NPSN:</strong> 20502893</p>
                <p><i class="fas fa-clock" style="color: var(--color-5); margin-right: 10px; width: 20px; text-align: center;"></i> <strong>Jam Operasional:</strong></p>
                <ul style="list-style: none; padding-left: 40px; margin-top: 5px;">
                    <li><span style="color: var(--color-4)">•</span> Senin - Kamis: 06:00 - 14:00 WIB</li>
                    <li><span style="color: var(--color-4)">•</span> Jumat - Sabtu: 06:00 - 13:00 WIB</li>
                </ul>
            </div>
        </section>

        <section id="visi">
            <div class="section-header">
                <span class="section-subtitle">Tujuan Kita</span>
                <h2 class="section-title">VISI DAN MISI</h2>
            </div>

            <div class="glass-card text-content">
                <p><strong>Visi:</strong> Menjadi sekolah dasar yang unggul dalam pendidikan, karakter, dan kreativitas, serta mencetak generasi yang berakhlak mulia dan berprestasi.</p>
                <br>
                <p><strong>Misi:</strong></p>
                <ol>
                    <li>Meningkatkan kualitas pendidikan.</li>
                    <li>Mengembangkan karakter siswa.</li>
                    <li>Menumbuhkan budaya literasi.</li>
                    <li>Menjalin kemitraan dengan orang tua dan masyarakat.</li>
                    <li>Mengembangkan kompetensi guru.</li>
                </ol>
            </div>
        </section>

        <section id="pustakawan">
            <div class="section-header">
                <span class="section-subtitle">Pengelola</span>
                <h2 class="section-title">PUSTAKAWAN</h2>
            </div>

            <div class="glass-card pustakawan-card">
                <img src="{{ asset('web-perpus/img/pustakawan.png') }}" alt="Foto Pustakawan">
                <p><strong style="color: var(--color-4); font-family: 'Unbounded', sans-serif;">Nama:</strong> Lilik Nurhayati, S.Pd</p>
                <p><strong style="color: var(--color-3); font-family: 'Unbounded', sans-serif;">Email:</strong> lilik.nur246@guruku.belajar.id</p>
                <p><strong style="color: var(--color-5); font-family: 'Unbounded', sans-serif;">Jabatan:</strong> Pustakawan</p>
            </div>
        </section>

        <section id="denah">
            <div class="section-header">
                <span class="section-subtitle">Kunjungi Kami</span>
                <h2 class="section-title">DENAH & LOKASI</h2>
            </div>

            <div class="glass-card">
                <div class="maps-wrapper">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.132515518058!2d112.40273567526795!3d-7.560518392481143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78552b0b1456f7%3A0x8b4ac87fc7a8450!2sSDN%20Berat%20Wetan%201!5e0!3m2!1sid!2sid!4v1714550000000!5m2!1sid!2sid" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                
                <div class="alamat-info">
                    <p><i class="fas fa-map-marker-alt"></i> <strong>Alamat Lengkap:</strong> Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur 61351</p>
                    <p><i class="fas fa-clock"></i> <strong>Jam Operasional:</strong> Senin-Sabtu, 06:00 - 14:00 WIB</p>
                    <p><i class="fas fa-phone"></i> <strong>Kontak:</strong> (0321) 123456</p>
                    <a href="https://maps.google.com/?q=SDN+Berat+Wetan+1" target="_blank" class="btn-login" style="display: inline-block; padding: 10px 25px; margin-top: 15px; border-radius: 50px;">
                        <i class="fas fa-directions" style="margin-right: 5px;"></i> Buka di Google Maps
                    </a>
                </div>
                
                <div style="text-align: center;">
                    <p style="margin-bottom: 20px; font-family: 'Unbounded'; color: var(--color-4);">Denah Ruangan Perpustakaan</p>
                    <img src="{{ asset('web-perpus/img/perpus.png') }}" alt="Denah Perpustakaan" class="denah-img">
                </div>
            </div>
        </section>

        <footer>
            SDN BERAT WETAN 1 © Famella Firda Levia
        </footer>
    </div>

    <script>
        const { createApp, ref, onMounted, onUnmounted } = Vue;
        
        createApp({
            setup() {
                const isMenuActive = ref(false);
                const isScrolled = ref(false);
                
                const toggleMenu = () => {
                    isMenuActive.value = !isMenuActive.value;
                };
                
                const closeMenu = () => {
                    isMenuActive.value = false;
                };
                
                const handleScroll = () => {
                    isScrolled.value = window.scrollY > 20;
                };
                
                onMounted(() => {
                    window.addEventListener('scroll', handleScroll);
                    handleScroll(); 
                });
                
                onUnmounted(() => {
                    window.removeEventListener('scroll', handleScroll);
                });
                
                return {
                    isMenuActive,
                    isScrolled,
                    toggleMenu,
                    closeMenu
                };
            }
        }).mount('#app');
    </script>
</body>
</html>