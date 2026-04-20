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
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
  
  {{-- Spline Viewer --}}
  <script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>
  
  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --primary-color: #000000;
      --secondary-color: #ffffff;
      --accent-color: #ff5ea3; /* Pink Neon */
      --accent-color2: #5eb0ff; /* Blue Neon */
      --text-color: #000000;
      --bg-color: #0a0a0a;
      --section-bg: rgba(15, 15, 20, 0.7); /* Diubah jadi dark glass agar neon Y2K terlihat */
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      width: 100%;
      overflow-x: hidden;
      font-family: 'Space Grotesk', sans-serif;
      background-color: var(--bg-color);
      color: var(--secondary-color); /* Teks putih untuk dark theme Y2K */
      scroll-behavior: smooth;
    }

    body {
      opacity: 0;
      animation: fadeIn 1s ease-in-out forwards;
    }

    @keyframes fadeIn {
      to { opacity: 1; }
    }

    /* Y2K Tech Grid Background */
    .tech-grid {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background-image: 
        linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
      background-size: 40px 40px;
      z-index: -2;
      pointer-events: none;
    }

    /* Gradient Background Animation */
    .gradient-bg {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: radial-gradient(circle at center, #1a1a4a 0%, var(--bg-color) 100%);
      z-index: -3;
    }

    .gradient-overlay {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: radial-gradient(600px circle at var(--mouse-x) var(--mouse-y), 
        rgba(255, 94, 163, 0.15) 0%, 
        rgba(94, 176, 255, 0.1) 40%, 
        transparent 80%);
      z-index: -1;
      pointer-events: none;
      transition: background 0.1s ease;
    }

    /* Floating Books Animation */
    .floating-books {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      pointer-events: none;
      z-index: 0;
      overflow: hidden;
    }

    .book {
      position: absolute;
      width: 100px;
      height: 140px;
      background-size: cover;
      background-position: center;
      opacity: 0.4;
      filter: drop-shadow(0 0 10px var(--accent-color2));
      animation: float 15s infinite ease-in-out;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-30px) rotate(5deg); }
    }

    /* Floating Navbar Styles */
    header {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: auto;
      max-width: 90%;
      background-color: rgba(10, 10, 10, 0.6);
      border-radius: 50px;
      padding: 10px 25px;
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
      backdrop-filter: blur(15px);
      z-index: 1000;
      transition: all 0.5s ease;
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-bottom: 1px solid rgba(94, 176, 255, 0.3); /* Y2K Glow Accent */
    }

    header.scrolled {
      top: 15px;
      background-color: rgba(10, 10, 10, 0.9);
      box-shadow: 0 10px 30px rgba(255, 94, 163, 0.1);
      border-color: rgba(255, 94, 163, 0.3);
    }

    .logo-nav {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-container img {
      width: 35px;
      height: auto;
      transition: transform 0.3s ease;
      filter: drop-shadow(0 0 5px var(--accent-color2));
    }

    .logo-container:hover img {
      transform: rotate(15deg) scale(1.1);
    }

    .logo-container strong {
      font-size: 18px;
      color: var(--secondary-color);
      font-weight: 700;
      letter-spacing: 1px;
    }

    .nav-container {
      display: flex;
      align-items: center;
    }

    nav { display: flex; align-items: center; }

    nav a {
      text-decoration: none;
      color: var(--secondary-color);
      font-weight: 500;
      transition: all 0.3s ease;
      padding: 8px 15px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
    }

    nav a:hover {
      color: var(--accent-color2);
      text-shadow: 0 0 10px var(--accent-color2);
    }

    .login-btn {
      background: linear-gradient(45deg, rgba(255,94,163,0.1), rgba(94,176,255,0.1));
      color: var(--secondary-color);
      border: 1px solid var(--accent-color);
      padding: 8px 25px;
      border-radius: 30px;
      font-weight: 600;
      transition: all 0.3s ease;
      margin-left: 15px;
      font-size: 14px;
      cursor: pointer;
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 0 10px rgba(255, 94, 163, 0.2);
    }

    .login-btn:hover {
      background: var(--accent-color);
      color: white;
      box-shadow: 0 0 20px var(--accent-color);
      transform: translateY(-2px);
    }

    /* Mobile Login Button */
    .mobile-login-btn {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: linear-gradient(45deg, var(--accent-color), var(--accent-color2));
      color: var(--secondary-color);
      border: none;
      padding: 12px 24px;
      border-radius: 50px;
      font-weight: 600;
      cursor: pointer;
      z-index: 1000;
      box-shadow: 0 0 20px rgba(255, 94, 163, 0.5);
      gap: 10px;
    }

    /* Hamburger */
    .hamburger {
      display: none;
      width: 25px;
      height: 18px;
      flex-direction: column;
      justify-content: space-between;
      margin-left: 15px;
      z-index: 1001;
      cursor: pointer;
    }
    .hamburger span {
      display: block;
      width: 100%;
      height: 2px;
      background-color: var(--secondary-color);
      transition: all 0.3s ease;
    }
    .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 6px); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(5px, -6px); }

    .nav-menu { display: flex; gap: 5px; }

    /* Hero Section */
    .hero {
      position: relative;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 180px 80px 100px;
      min-height: 100vh;
      width: 100%;
      z-index: 1;
    }

    .hero-content {
      max-width: 55%;
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      font-size: clamp(35px, 6vw, 65px);
      margin-bottom: 20px;
      line-height: 1.1;
      font-weight: 700;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp 1s ease forwards 0.3s;
    }

    .hero .highlight {
      background: linear-gradient(to right, var(--accent-color), var(--accent-color2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      filter: drop-shadow(0 0 10px rgba(255, 94, 163, 0.3));
    }

    .hero p {
      font-size: clamp(16px, 2vw, 22px);
      line-height: 1.6;
      color: #a0a0a0;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp 1s ease forwards 0.5s;
    }

    .hero button {
      background: transparent;
      color: var(--secondary-color);
      border: 2px solid var(--accent-color2);
      padding: 16px 40px;
      border-radius: 5px; /* Y2K Cyberpunk style */
      margin-top: 40px;
      font-size: 16px;
      font-weight: 700;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp 1s ease forwards 0.7s;
      position: relative;
      overflow: hidden;
      font-family: 'Space Grotesk', sans-serif;
      letter-spacing: 2px;
      text-transform: uppercase;
      cursor: pointer;
      box-shadow: inset 0 0 15px rgba(94, 176, 255, 0.2), 0 0 15px rgba(94, 176, 255, 0.2);
      transition: all 0.3s ease;
    }

    .hero button:hover {
      background: var(--accent-color2);
      color: var(--primary-color);
      box-shadow: 0 0 30px var(--accent-color2);
      transform: scale(1.05);
    }

    .book-animation {
      position: absolute;
      right: 5%;
      top: 50%;
      transform: translateY(-50%);
      width: 500px;
      height: 600px;
      background-image: url('{{ asset("web-perpus/img/bukubaru.png") }}');
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      opacity: 0;
      z-index: 2;
      filter: drop-shadow(0 0 30px rgba(94, 176, 255, 0.5));
      animation: floatBook 6s ease-in-out infinite, fadeInRight 1s ease forwards 0.7s;
    }

    @keyframes floatBook {
      0%, 100% { transform: translateY(-50%) rotate(0deg); }
      50% { transform: translateY(-55%) rotate(-3deg); }
    }

    /* BENTO GRID LAYOUT SYSTEM (Y2K Futuristik) */
    section {
      padding: 100px 5%;
      position: relative;
      z-index: 2;
    }

    .section-header {
      text-align: center;
      margin-bottom: 50px;
    }

    .section-header h2 {
      font-size: clamp(28px, 4vw, 45px);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      position: relative;
      display: inline-block;
      text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    }

    .section-header h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent-color), var(--accent-color2));
      border-radius: 10px;
      box-shadow: 0 0 10px var(--accent-color);
    }

    /* Bento Container */
    .bento-container {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 25px;
      opacity: 0;
      transform: translateY(50px);
      transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .bento-container.in-view {
      opacity: 1;
      transform: translateY(0);
    }

    .bento-box {
      background: var(--section-bg);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 24px;
      padding: 35px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      position: relative;
      overflow: hidden;
      transition: all 0.4s ease;
    }

    .bento-box::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 2px;
      background: linear-gradient(90deg, transparent, var(--accent-color2), transparent);
      opacity: 0;
      transition: opacity 0.4s ease;
    }

    .bento-box:hover {
      transform: translateY(-5px);
      border-color: rgba(255, 94, 163, 0.3);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 20px rgba(255, 94, 163, 0.1);
    }

    .bento-box:hover::before { opacity: 1; }

    /* Bento Grid Spans */
    .col-span-12 { grid-column: span 12; }
    .col-span-8 { grid-column: span 8; }
    .col-span-7 { grid-column: span 7; }
    .col-span-6 { grid-column: span 6; }
    .col-span-5 { grid-column: span 5; }
    .col-span-4 { grid-column: span 4; }

    /* Content Typography inside Bento */
    .bento-box h3 {
      color: var(--accent-color2);
      font-size: 22px;
      margin-bottom: 20px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .bento-box p {
      font-size: 16px;
      line-height: 1.8;
      color: #cccccc;
      margin-bottom: 15px;
      text-align: justify;
    }
    
    .bento-box strong { color: var(--secondary-color); }

    .bento-box ul, .bento-box ol {
      padding-left: 20px;
      color: #cccccc;
      line-height: 1.8;
      font-size: 16px;
    }

    .bento-box li { margin-bottom: 12px; }
    .bento-box ul li::marker { color: var(--accent-color); }

    /* Pustakawan ID Card Style */
    .id-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      background: linear-gradient(180deg, rgba(20,20,25,0.8) 0%, rgba(10,10,15,0.9) 100%);
      border: 1px solid var(--accent-color);
      box-shadow: 0 0 30px rgba(255, 94, 163, 0.15);
    }

    .pustakawan-img-wrapper {
      position: relative;
      width: 160px;
      height: 160px;
      border-radius: 50%;
      padding: 5px;
      background: linear-gradient(45deg, var(--accent-color), var(--accent-color2));
      margin-bottom: 25px;
      animation: spinBorder 10s linear infinite;
    }

    .pustakawan-img-wrapper img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid var(--bg-color);
    }

    @keyframes spinBorder {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .id-card h3 { color: var(--secondary-color); font-size: 24px; margin-bottom: 5px; justify-content: center;}
    .id-card .jabatan { color: var(--accent-color); font-size: 16px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; }
    .id-card .email { display: inline-block; padding: 8px 20px; background: rgba(255,255,255,0.05); border-radius: 20px; font-family: monospace; color: var(--accent-color2); border: 1px solid rgba(94, 176, 255, 0.2); }

    /* Maps & Denah */
    .maps-container iframe {
      width: 100%;
      height: 100%;
      min-height: 350px;
      border: 0;
      border-radius: 15px;
      filter: invert(90%) hue-rotate(180deg); /* Membuat map jadi Dark Mode/Tech look */
    }

    .btn-direksi {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      margin-top: 20px;
      padding: 12px 25px;
      background: rgba(94, 176, 255, 0.1);
      color: var(--accent-color2);
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      border: 1px solid var(--accent-color2);
      transition: all 0.3s ease;
      width: 100%;
      justify-content: center;
    }

    .btn-direksi:hover {
      background: var(--accent-color2);
      color: var(--primary-color);
      box-shadow: 0 0 20px rgba(94, 176, 255, 0.4);
    }

    .denah-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 15px;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 30px;
      background-color: #050505;
      color: #666;
      border-top: 1px solid rgba(255,255,255,0.05);
      font-family: monospace;
      letter-spacing: 1px;
      position: relative;
      z-index: 10;
    }

    /* Animations */
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInRight { to { opacity: 1; transform: translateX(0); } }

    /* Spline View */
    .spline-container {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      z-index: -2;
      opacity: 0.4;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
      .bento-container { display: flex; flex-direction: column; }
      .hero { flex-direction: column; text-align: center; padding: 150px 40px 80px; }
      .hero-content { max-width: 100%; }
      .book-animation { position: relative; right: auto; top: auto; transform: none; margin: 40px auto; width: 300px; height: 400px; }
      .col-span-7, .col-span-5, .col-span-8, .col-span-4, .col-span-6 { grid-column: span 12; }
    }

    @media (min-width: 769px) {
      .nav-container { justify-content: flex-end; }
      .login-btn { display: block; }
      .mobile-login-btn { display: none !important; }
    }

    @media (max-width: 768px) {
      .login-btn { display: none !important; }
      .mobile-login-btn { display: flex; }
      .hamburger { display: flex; }
      
      .nav-menu {
        position: fixed; top: 0; left: -100%; width: 100%; height: 100vh;
        background-color: rgba(10, 10, 10, 0.95);
        backdrop-filter: blur(20px);
        flex-direction: column; align-items: center; justify-content: center;
        gap: 30px; transition: left 0.5s ease; z-index: 999;
      }
      .nav-menu.active { left: 0; }
      nav a { font-size: 20px; }
      .bento-box { padding: 20px; }
    }
  </style>
</head>
<body>
  <div id="app">
    <div class="tech-grid"></div>
    <div class="gradient-bg"></div>
    <div class="gradient-overlay" :style="{ '--mouse-x': mouseX + 'px', '--mouse-y': mouseY + 'px' }"></div>
    <div class="floating-books" id="floatingBooks"></div>

    <div class="spline-container">
      <spline-viewer url="https://prod.spline.design/PBQQBw8bfXDhBo7w/scene.splinecode" events-target="global"></spline-viewer>
    </div>

    <header :class="{scrolled: isScrolled}">
      <div class="logo-nav">
        <div class="logo-container">
          <img src="{{ asset('web-perpus/img/Open_Book_Vector_Illustration_Flat_Logo_Stock_Vector_-_Illustration_of_flat__minimal__187678563__3_-removebg-preview - Copy.png') }}" alt="logo">
          <strong>LIBWE_SYS</strong>
        </div>
        <div class="nav-container">
          <nav>
            <div class="hamburger" :class="{active: isMenuActive}" @click="toggleMenu">
              <span></span><span></span><span></span>
            </div>
            <div class="nav-menu" :class="{active: isMenuActive}">
              <a href="#informasi" @click="closeMenu">Data_01: Informasi</a>
              <a href="#visi" @click="closeMenu">Data_02: Visi_Misi</a>
              <a href="#pustakawan" @click="closeMenu">Data_03: Admin</a>
              <a href="#denah" @click="closeMenu">Data_04: Lokasi</a>
            </div>
          </nav>
          <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">SYS.LOGIN</button>
        </div>
      </div>
    </header>

    <button class="mobile-login-btn" onclick="window.location.href='{{ route('login') }}'">
      <i class="fas fa-sign-in-alt"></i> SYSTEM LOGIN
    </button>

    <div class="hero">
      <div class="hero-content">
        <h1>Selamat datang di<br>Perpustakaan Online <br><span class="highlight">SDN Berat Wetan 1</span></h1>
        <p>Membangun generasi cerdas dan berbudaya literasi melalui integrasi sistem digital masa depan.</p>
        <button onclick="window.location.href='{{ route('katalog') }}'">[ TELUSURI DATABASE ]</button>
      </div>
      <div class="book-animation"></div>
    </div>

    <section id="informasi">
      <div class="section-header"><h2>Informasi Utama</h2></div>
      
      <div class="bento-container" ref="informasiRef" :class="{ 'in-view': isInfoInView }">
        
        <div class="bento-box col-span-7">
          <h3><i class="fas fa-history"></i> Sejarah & Perkembangan</h3>
          <p>Perpustakaan SDN Berat Wetan 1 didirikan sebagai bagian dari komitmen sekolah untuk meningkatkan minat baca dan literasi siswa sejak dini. Berdiri sejak awal tahun 2005, perpustakaan ini awalnya hanya memiliki koleksi buku bacaan dasar dan beberapa rak sederhana yang ditempatkan di salah satu sudut ruang kelas. Namun, seiring waktu dan dukungan dari pihak sekolah, guru, serta orang tua murid, perpustakaan terus mengalami perkembangan baik dari segi fasilitas maupun jumlah koleksi buku.</p>
          <p>Dalam beberapa tahun terakhir, perpustakaan SDN Berat Wetan 1 telah mengalami renovasi dan penataan ulang, menjadikannya ruang yang lebih nyaman, bersih, dan menyenangkan untuk belajar. Kini, perpustakaan tidak hanya menyediakan buku bacaan fiksi dan non-fiksi, tetapi juga buku referensi, ensiklopedia anak, kamus, dan bahkan koleksi bergambar yang mendukung proses pembelajaran tematik di sekolah dasar. Dengan tambahan sentuhan digital dan penataan yang lebih modern, perpustakaan menjadi pusat aktivitas literasi yang aktif di lingkungan sekolah.</p>
        </div>

        <div class="bento-box col-span-5">
          <h3><i class="fas fa-server"></i> Database Sekolah</h3>
          <ul style="list-style: none; padding-left: 0;">
            <li><strong>NPSN:</strong> 20502893</li>
            <li><strong>Email:</strong> berat.wetan1@gmail.com</li>
            <li><strong>Alamat:</strong> Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur</li>
          </ul>
          
          <h3 style="margin-top: 30px;"><i class="fas fa-clock"></i> Jam Operasional</h3>
          <ul style="list-style: none; padding-left: 0;">
            <li><span style="color: var(--accent-color2);">Senin - Kamis:</span> 06:00 - 14:00 WIB</li>
            <li><span style="color: var(--accent-color2);">Jumat - Sabtu:</span> 06:00 - 13:00 WIB</li>
          </ul>
        </div>

        <div class="bento-box col-span-12">
          <h3><i class="fas fa-bullseye"></i> Tujuan Sistem</h3>
          <p><strong>Tujuan utama</strong> dari perpustakaan SDN Berat Wetan 1 adalah mendukung proses pembelajaran di sekolah dengan menyediakan sumber informasi yang lengkap dan mudah diakses oleh seluruh siswa dan guru. Perpustakaan juga bertujuan untuk:</p>
          <ul>
            <li>Menumbuhkan minat baca siswa sejak usia dini melalui penyediaan bacaan yang menarik dan bervariasi.</li>
            <li>Meningkatkan budaya literasi di lingkungan sekolah dengan mengadakan kegiatan-kegiatan seperti pojok baca, lomba membaca, dan mendongeng.</li>
            <li>Mendukung pembelajaran aktif dan mandiri, di mana siswa dapat mencari informasi tambahan secara mandiri untuk menunjang tugas dan pelajaran.</li>
            <li>Menjadi ruang edukatif yang menyenangkan, di mana siswa merasa nyaman untuk membaca, belajar, dan mengeksplorasi pengetahuan.</li>
            <li>Dengan adanya perpustakaan ini, diharapkan SDN Berat Wetan 1 tidak hanya menjadi tempat belajar akademik, tetapi juga tempat untuk membangun karakter, imajinasi, dan kecintaan terhadap ilmu pengetahuan.</li>
          </ul>
        </div>
      </div>
    </section>

    <section id="visi">
      <div class="section-header"><h2>Visi & Misi</h2></div>
      
      <div class="bento-container" ref="visiRef" :class="{ 'in-view': isVisiInView }">
        <div class="bento-box col-span-12" style="text-align: center; border-color: var(--accent-color);">
          <h3 style="justify-content: center; font-size: 28px;"><i class="fas fa-eye"></i> Visi</h3>
          <p style="text-align: center; font-size: 20px; color: var(--secondary-color);">"Menjadi sekolah dasar yang unggul dalam pendidikan, karakter, dan kreativitas, serta mencetak generasi yang berakhlak mulia dan berprestasi."</p>
        </div>

        <div class="bento-box col-span-12">
          <h3><i class="fas fa-tasks"></i> Misi Sistem</h3>
          <ol>
            <li>Meningkatkan kualitas pendidikan.</li>
            <li>Mengembangkan karakter siswa.</li>
            <li>Menumbuhkan budaya literasi.</li>
            <li>Menjalin kemitraan dengan orang tua dan masyarakat.</li>
            <li>Mengembangkan kompetensi guru.</li>
          </ol>
        </div>
      </div>
    </section>

    <section id="pustakawan">
      <div class="section-header"><h2>Data Pustakawan</h2></div>
      
      <div class="bento-container" ref="pustakawanRef" :class="{ 'in-view': isPustakawanInView }" style="justify-content: center; display: flex;">
        <div class="bento-box id-card col-span-6" style="width: 100%; max-width: 500px;">
          <div class="pustakawan-img-wrapper">
            <img src="{{ asset('web-perpus/img/Screenshot-2025-05-01-180615.png') }}" alt="Foto Pustakawan">
          </div>
          <h3>Lilik Nurhayati, S.Pd</h3>
          <p class="jabatan">Pustakawan Utama</p>
          <span class="email"><i class="fas fa-envelope"></i> lilik.nur246@guruku.belajar.id</span>
        </div>
      </div>
    </section>

    <section id="denah">
      <div class="section-header"><h2>Lokasi & Denah</h2></div>
      
      <div class="bento-container" ref="denahRef" :class="{ 'in-view': isDenahInView }">
        <div class="bento-box col-span-6">
          <h3><i class="fas fa-map-marker-alt"></i> Koordinat Lokasi</h3>
          <p>Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur 61351</p>
          <p><i class="fas fa-phone"></i> <strong>Kontak:</strong> (0321) 123456</p>
          
          <div class="maps-container" style="height: 300px; margin-top: 20px;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.132515518058!2d112.40273567526795!3d-7.560518392481143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78552b0b1456f7%3A0x8b4ac87fc7a8450!2sSDN%20Berat%20Wetan%201!5e0!3m2!1sid!2sid!4v1714550000000!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
          <a href="https://maps.google.com/?q=SDN+Berat+Wetan+1" target="_blank" class="btn-direksi">
            <i class="fas fa-directions"></i> BUKA TRACKING MAPS
          </a>
        </div>

        <div class="bento-box col-span-6" style="padding: 15px;">
          <h3 style="padding-left: 15px; padding-top: 10px;"><i class="fas fa-sitemap"></i> Layout Denah Ruangan</h3>
          <img src="{{ asset('web-perpus/img/meja-pustakawan.png') }}" alt="Denah Perpustakaan" class="denah-img">
        </div>
      </div>
    </section>

    <footer>
      SYS.CORP // SDN BERAT WETAN 1 © Famella Firda Levia // V.2.0
    </footer>
  </div>

  <script>
    const { createApp, ref, onMounted, onUnmounted } = Vue;
    
    createApp({
      setup() {
        const isMenuActive = ref(false);
        const isScrolled = ref(false);
        const isInfoInView = ref(false);
        const isVisiInView = ref(false);
        const isPustakawanInView = ref(false);
        const isDenahInView = ref(false);
        const mouseX = ref(window.innerWidth / 2);
        const mouseY = ref(window.innerHeight / 2);
        
        const toggleMenu = () => {
          isMenuActive.value = !isMenuActive.value;
          document.body.style.overflow = isMenuActive.value ? 'hidden' : '';
        };
        
        const closeMenu = () => {
          isMenuActive.value = false;
          document.body.style.overflow = '';
        };
        
        const handleScroll = () => {
          isScrolled.value = window.scrollY > 50;
          
          const scrollPosition = window.scrollY + window.innerHeight - 100;
          
          const informasiSection = document.getElementById('informasi');
          if (informasiSection && scrollPosition > informasiSection.offsetTop) isInfoInView.value = true;
          
          const visiSection = document.getElementById('visi');
          if (visiSection && scrollPosition > visiSection.offsetTop) isVisiInView.value = true;
          
          const pustakawanSection = document.getElementById('pustakawan');
          if (pustakawanSection && scrollPosition > pustakawanSection.offsetTop) isPustakawanInView.value = true;
          
          const denahSection = document.getElementById('denah');
          if (denahSection && scrollPosition > denahSection.offsetTop) isDenahInView.value = true;
        };
        
        const preventHorizontalScroll = () => {
          if (window.scrollX !== 0) window.scrollTo(0, window.scrollY);
        };
        
        const handleMouseMove = (e) => {
          mouseX.value = e.clientX;
          mouseY.value = e.clientY;
          document.documentElement.style.setProperty('--mouse-x', e.clientX + 'px');
          document.documentElement.style.setProperty('--mouse-y', e.clientY + 'px');
        };
        
        const createFloatingBooks = () => {
          const container = document.getElementById('floatingBooks');
          if (!container) return;
          
          const bookImages = [
            '{{ asset("web-perpus/img/book1.png") }}',
            '{{ asset("web-perpus/img/book2.png") }}',
            '{{ asset("web-perpus/img/book3.png") }}',
            '{{ asset("web-perpus/img/book4.png") }}',
            '{{ asset("web-perpus/img/book5.png") }}'
          ];
          
          container.innerHTML = '';
          for (let i = 0; i < 8; i++) {
            const book = document.createElement('div');
            book.className = 'book';
            book.style.backgroundImage = `url(${bookImages[Math.floor(Math.random() * bookImages.length)]})`;
            book.style.left = `${Math.random() * 100}%`;
            book.style.top = `${Math.random() * 100}%`;
            book.style.animationDuration = `${15 + Math.random() * 15}s`;
            book.style.animationDelay = `${Math.random() * 5}s`;
            book.style.transform = `scale(${0.6 + Math.random() * 0.5})`;
            container.appendChild(book);
          }
        };
        
        onMounted(() => {
          window.addEventListener('scroll', handleScroll);
          window.addEventListener('scroll', preventHorizontalScroll);
          window.addEventListener('mousemove', handleMouseMove);
          createFloatingBooks();
          setTimeout(handleScroll, 100);
        });
        
        onUnmounted(() => {
          window.removeEventListener('scroll', handleScroll);
          window.removeEventListener('scroll', preventHorizontalScroll);
          window.removeEventListener('mousemove', handleMouseMove);
        });
        
        return {
          isMenuActive, isScrolled, isInfoInView, isVisiInView, isPustakawanInView, isDenahInView,
          mouseX, mouseY, toggleMenu, closeMenu
        };
      }
    }).mount('#app');
  </script>
</body>
</html>