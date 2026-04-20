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
  
  {{-- GSAP for animations --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  
  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  {{-- Spline Viewer --}}
  <script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>
  
  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* Reset & Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-color: #000000;
      --secondary-color: #ffffff;
      --accent-color: #ff5ea3;
      --accent-color2: #5eb0ff;
      --accent-glow: rgba(94, 176, 255, 0.4);
      --text-color: #000000;
      --bg-color: #0a0a0a;
      --section-bg: rgba(255, 255, 255, 0.92);
      --neon-shadow: 0 0 20px rgba(94, 176, 255, 0.5);
      --y2k-gradient: linear-gradient(135deg, #ff5ea3 0%, #5eb0ff 50%, #ff5ea3 100%);
    }

    html, body {
      width: 100%;
      overflow-x: hidden;
      font-family: 'Space Grotesk', sans-serif;
      background-color: var(--bg-color);
      color: var(--text-color);
      scroll-behavior: smooth;
    }

    body {
      opacity: 0;
      animation: fadeIn 1s ease-in-out forwards;
    }

    @keyframes fadeIn {
      to { opacity: 1; }
    }

    /* Y2K Futuristic Elements */
    .y2k-grid {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        repeating-linear-gradient(transparent, transparent 49px, rgba(94, 176, 255, 0.05) 50px),
        repeating-linear-gradient(90deg, transparent, transparent 49px, rgba(94, 176, 255, 0.05) 50px);
      pointer-events: none;
      z-index: 0;
    }

    .glitch-text {
      position: relative;
      animation: glitch 3s infinite;
    }

    @keyframes glitch {
      0%, 100% { text-shadow: 2px 0 var(--accent-color), -2px 0 var(--accent-color2); }
      33% { text-shadow: 3px 0 var(--accent-color), -3px 0 var(--accent-color2); }
      66% { text-shadow: -2px 0 var(--accent-color), 2px 0 var(--accent-color2); }
    }

    /* Floating Books Animation */
    .floating-books {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
      overflow: hidden;
    }

    .book {
      position: absolute;
      width: 100px;
      height: 140px;
      background-size: cover;
      background-position: center;
      opacity: 0.6;
      filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.5));
      animation: float 15s infinite ease-in-out;
      transition: all 0.3s ease;
    }

    .book:hover {
      opacity: 0.9;
      filter: drop-shadow(0 0 20px var(--accent-color2));
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
      25% { transform: translateY(-30px) rotate(5deg) scale(1.05); }
      50% { transform: translateY(-15px) rotate(-3deg) scale(0.95); }
      75% { transform: translateY(-40px) rotate(8deg) scale(1.02); }
    }

    /* Gradient Background with Animation */
    .gradient-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, 
        #0a0a2a 0%, 
        #1a1a4a 25%,
        #2d2b6b 50%, 
        #1a1a4a 75%,
        #0a0a2a 100%);
      z-index: -3;
      animation: gradientShift 10s ease infinite;
    }

    @keyframes gradientShift {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    .gradient-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at var(--mouse-x) var(--mouse-y), 
        rgba(255, 94, 163, 0.2) 0%, 
        rgba(94, 176, 255, 0.15) 50%, 
        transparent 80%);
      z-index: -2;
      pointer-events: none;
    }

    /* Floating Navbar Styles */
    header {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: auto;
      max-width: 90%;
      background: rgba(10, 10, 10, 0.8);
      border-radius: 50px;
      padding: 8px 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 94, 163, 0.2);
      backdrop-filter: blur(15px);
      z-index: 1000;
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    header.scrolled {
      top: 10px;
      background: rgba(10, 10, 10, 0.95);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4), 0 0 0 2px var(--accent-color2);
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
      transition: all 0.3s ease;
      filter: drop-shadow(0 0 5px var(--accent-color2));
    }

    .logo-container:hover img {
      transform: rotate(360deg) scale(1.1);
      filter: drop-shadow(0 0 15px var(--accent-color));
    }

    .logo-container strong {
      font-size: 20px;
      background: var(--y2k-gradient);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      font-weight: 700;
      letter-spacing: 2px;
      font-family: 'Orbitron', monospace;
    }

    .nav-container {
      display: flex;
      align-items: center;
    }

    nav {
      display: flex;
      align-items: center;
    }

    nav a {
      text-decoration: none;
      color: var(--secondary-color);
      font-weight: 500;
      transition: all 0.3s ease;
      white-space: nowrap;
      padding: 8px 15px;
      font-size: 14px;
      position: relative;
      cursor: pointer;
      font-family: 'Orbitron', monospace;
      letter-spacing: 1px;
    }

    nav a::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: var(--y2k-gradient);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    nav a:hover::before {
      width: 80%;
    }

    nav a:hover {
      text-shadow: 0 0 10px var(--accent-color2);
    }

    .login-btn {
      background: linear-gradient(45deg, var(--accent-color), var(--accent-color2));
      color: var(--secondary-color);
      border: none;
      padding: 8px 20px;
      border-radius: 30px;
      font-weight: 600;
      transition: all 0.3s ease;
      margin-left: 15px;
      font-size: 14px;
      cursor: pointer;
      font-family: 'Orbitron', monospace;
      letter-spacing: 1px;
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(94, 176, 255, 0.5);
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
      font-size: 14px;
      cursor: pointer;
      z-index: 1000;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
      font-family: 'Orbitron', monospace;
      gap: 10px;
    }

    .mobile-login-btn i {
      font-size: 16px;
    }

    .mobile-login-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(94, 176, 255, 0.4);
    }

    /* Hamburger Menu */
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
      background: linear-gradient(90deg, var(--accent-color), var(--accent-color2));
      transition: all 0.3s ease;
      transform-origin: left center;
    }

    .hamburger.active span:nth-child(1) {
      transform: rotate(45deg) translate(3px, -2px);
    }

    .hamburger.active span:nth-child(2) {
      opacity: 0;
      transform: scaleX(0);
    }

    .hamburger.active span:nth-child(3) {
      transform: rotate(-45deg) translate(3px, 2px);
    }

    .nav-menu {
      display: flex;
      gap: 5px;
    }

    /* Desktop styles */
    @media (min-width: 769px) {
      .login-btn { display: block; }
      .mobile-login-btn { display: none !important; }
    }

    /* Mobile styles */
    @media (max-width: 768px) {
      header { width: 90%; padding: 8px 15px; }
      .login-btn { display: none !important; }
      .mobile-login-btn { display: flex; animation: slideUp 0.5s ease-out; }
      .hamburger { display: flex; }

      .nav-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100vh;
        background: rgba(10, 10, 10, 0.98);
        backdrop-filter: blur(20px);
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 30px;
        transition: left 0.5s cubic-bezier(0.77, 0.2, 0.05, 1);
        z-index: 999;
      }

      .nav-menu.active { left: 0; }
      nav a { font-size: 22px; padding: 10px 0; }
    }

    @keyframes slideUp {
      from { transform: translateY(100px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    /* Hero Section - Restructured */
    .hero {
      position: relative;
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      min-height: 100vh;
      padding: 0 5%;
      color: var(--secondary-color);
      overflow: hidden;
      gap: 50px;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(ellipse at 70% 50%, transparent 0%, rgba(0,0,0,0.7) 100%);
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      font-size: clamp(32px, 5vw, 56px);
      margin-bottom: 20px;
      line-height: 1.2;
      font-weight: 700;
      font-family: 'Orbitron', monospace;
    }

    .hero .highlight {
      background: var(--y2k-gradient);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; text-shadow: 0 0 10px rgba(94, 176, 255, 0.5); }
      50% { opacity: 0.9; text-shadow: 0 0 20px rgba(94, 176, 255, 0.8); }
    }

    .hero p {
      font-size: clamp(16px, 1.5vw, 18px);
      line-height: 1.6;
      margin-bottom: 30px;
    }

    .hero button {
      background: linear-gradient(45deg, var(--accent-color), var(--accent-color2));
      color: var(--secondary-color);
      border: none;
      padding: 14px 35px;
      border-radius: 50px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      font-family: 'Orbitron', monospace;
      letter-spacing: 1px;
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      overflow: hidden;
    }

    .hero button:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 10px 30px rgba(94, 176, 255, 0.4);
    }

    .hero button::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .hero button:hover::before {
      width: 300px;
      height: 300px;
    }

    .book-animation {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 500px;
      height: 500px;
      background-image: url('{{ asset("web-perpus/img/bukubaru.png") }}');
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      filter: drop-shadow(0 0 30px rgba(94, 176, 255, 0.6));
      animation: floatBook 4s ease-in-out infinite, rotate3D 8s ease-in-out infinite;
      justify-self: end;
    }

    @keyframes floatBook {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }

    @keyframes rotate3D {
      0%, 100% { transform: rotateY(0deg); }
      25% { transform: rotateY(10deg); }
      75% { transform: rotateY(-10deg); }
    }

    @media (max-width: 768px) {
      .hero {
        grid-template-columns: 1fr;
        text-align: center;
        padding: 120px 20px 80px;
      }
      .book-animation {
        justify-self: center;
        max-width: 300px;
        height: 300px;
      }
    }

    /* Sections - Restructured for better readability */
    section {
      padding: 80px 5%;
      scroll-margin-top: 80px;
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .section-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
      opacity: 0.08;
    }

    .section-content {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      padding: 50px;
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(10px);
      border-radius: 30px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(94, 176, 255, 0.2);
      position: relative;
      z-index: 1;
      transition: all 0.5s ease;
    }

    .section-content:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3), 0 0 0 2px rgba(94, 176, 255, 0.3);
    }

    .section-content h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: clamp(28px, 4vw, 40px);
      font-family: 'Orbitron', monospace;
      background: linear-gradient(135deg, var(--accent-color), var(--accent-color2));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      position: relative;
    }

    .section-content h2::after {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: var(--y2k-gradient);
      border-radius: 3px;
    }

    /* Info Section - Better text structure */
    .info p, .info ul {
      line-height: 1.8;
      font-size: 16px;
      margin-bottom: 20px;
    }

    .info p strong {
      color: var(--accent-color);
      font-size: 18px;
    }

    .info ul {
      list-style: none;
      padding-left: 0;
    }

    .info ul li {
      padding-left: 25px;
      position: relative;
      margin-bottom: 15px;
    }

    .info ul li::before {
      content: '📖';
      position: absolute;
      left: 0;
      color: var(--accent-color2);
    }

    /* Visi Misi Section */
    .visi-misi p {
      font-size: 16px;
      line-height: 1.8;
      margin-bottom: 20px;
    }

    .visi-misi ol {
      padding-left: 30px;
    }

    .visi-misi ol li {
      margin-bottom: 12px;
      line-height: 1.6;
    }

    /* Pustakawan Section */
    .pustakawan {
      text-align: center;
    }

    .pustakawan img {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      object-fit: cover;
      margin: 20px auto;
      border: 4px solid transparent;
      background: linear-gradient(45deg, var(--accent-color), var(--accent-color2)) border-box;
      -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
      mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      transition: all 0.5s ease;
    }

    .pustakawan img:hover {
      transform: scale(1.05) rotate(5deg);
      filter: drop-shadow(0 0 20px rgba(94, 176, 255, 0.5));
    }

    .pustakawan p {
      margin: 10px 0;
      font-size: 16px;
    }

    .pustakawan p strong {
      color: var(--accent-color);
    }

    /* Denah & Maps */
    .maps-container {
      width: 100%;
      margin: 30px 0;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      transition: all 0.5s ease;
    }

    .maps-container:hover {
      transform: scale(1.01);
      box-shadow: 0 15px 40px rgba(94, 176, 255, 0.3);
    }

    .maps-container iframe {
      width: 100%;
      height: 400px;
      border: 0;
      display: block;
    }

    .alamat-detail {
      background: linear-gradient(135deg, rgba(255, 94, 163, 0.1), rgba(94, 176, 255, 0.1));
      padding: 25px;
      border-radius: 20px;
      margin: 20px 0;
      text-align: center;
    }

    .alamat-detail p {
      margin: 10px 0;
    }

    .alamat-detail i {
      margin-right: 10px;
      color: var(--accent-color);
    }

    .btn-direksi {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      margin-top: 15px;
      padding: 12px 30px;
      background: linear-gradient(45deg, var(--accent-color), var(--accent-color2));
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-direksi:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 20px rgba(94, 176, 255, 0.4);
    }

    .denah img {
      width: 100%;
      max-width: 800px;
      margin: 30px auto;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      display: block;
      transition: all 0.5s ease;
    }

    .denah img:hover {
      transform: scale(1.02);
      box-shadow: 0 0 30px rgba(94, 176, 255, 0.3);
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 30px;
      background: linear-gradient(135deg, #0a0a0a, #1a1a2a);
      color: var(--secondary-color);
      font-size: 14px;
      position: relative;
      z-index: 2;
      border-top: 1px solid rgba(94, 176, 255, 0.2);
    }

    /* Spline Container */
    .spline-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -3;
      opacity: 0.25;
      pointer-events: none;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .section-content {
        padding: 30px 20px;
      }
      
      .maps-container iframe {
        height: 300px;
      }
      
      .info p, .info ul, .visi-misi p, .visi-misi ol {
        font-size: 14px;
      }
    }

    @media (max-width: 480px) {
      .section-content {
        padding: 25px 15px;
      }
      
      .pustakawan img {
        width: 140px;
        height: 140px;
      }
      
      .maps-container iframe {
        height: 250px;
      }
      
      .alamat-detail {
        padding: 15px;
      }
    }
  </style>
</head>
<body>
  <div id="app">
    <!-- Y2K Grid Overlay -->
    <div class="y2k-grid"></div>
    
    <!-- Floating Books Background -->
    <div class="floating-books" id="floatingBooks"></div>

    <!-- Gradient Background -->
    <div class="gradient-bg"></div>
    <div class="gradient-overlay" :style="{ '--mouse-x': mouseX + 'px', '--mouse-y': mouseY + 'px' }"></div>

    <!-- Spline Animation -->
    <div class="spline-container">
      <spline-viewer url="https://prod.spline.design/PBQQBw8bfXDhBo7w/scene.splinecode" events-target="global"></spline-viewer>
    </div>

    <!-- Header -->
    <header :class="{scrolled: isScrolled}">
      <div class="logo-nav">
        <div class="logo-container">
          <img 
            src="{{ asset('web-perpus/img/Open_Book_Vector_Illustration_Flat_Logo_Stock_Vector_-_Illustration_of_flat__minimal__187678563__3_-removebg-preview - Copy.png') }}" 
            alt="logo Perpustakaan">
          <strong>LIB<span style="color: #ff5ea3;">WE</span></strong>
        </div>
        
        <div class="nav-container">
          <nav>
            <div class="hamburger" :class="{active: isMenuActive}" @click="toggleMenu">
              <span></span>
              <span></span>
              <span></span>
            </div>
            <div class="nav-menu" :class="{active: isMenuActive}">
              <a href="#informasi" @click="closeMenu">Informasi</a>
              <a href="#visi" @click="closeMenu">Visi Misi</a>
              <a href="#pustakawan" @click="closeMenu">Pustakawan</a>
              <a href="#denah" @click="closeMenu">Denah & Lokasi</a>
            </div>
          </nav>
          <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">Masuk</button>
        </div>
      </div>
    </header>

    <!-- Mobile Login Button -->
    <button class="mobile-login-btn" onclick="window.location.href='{{ route('login') }}'">
      <i class="fas fa-sign-in-alt"></i> Masuk sebagai Pustakawan
    </button>

    <!-- Hero Section -->
    <div class="hero">
      <div class="hero-content">
        <h1>Selamat datang di<br><span class="highlight glitch-text">Perpustakaan Online</span><br>SDN Berat Wetan 1</h1>
        <p>Membangun generasi cerdas dan berbudaya literasi dengan teknologi masa depan.</p>
        <button onclick="window.location.href='{{ route('katalog') }}'">
          <i class="fas fa-book-open"></i> TELUSURI BUKU
        </button>
      </div>
      
      <div class="book-animation"></div>
    </div>

    <!-- Informasi Section -->
    <section id="informasi" class="info" ref="informasi">
      <img class="section-bg" src="{{ asset('web-perpus/img/2.png') }}" alt="Background">
      <div class="section-content" :class="{ 'in-view': isInfoInView }">
        <h2><i class="fas fa-info-circle"></i> Informasi</h2>
        <p style="text-align: justify">Perpustakaan SDN Berat Wetan 1 didirikan sebagai bagian dari komitmen sekolah untuk meningkatkan minat baca dan literasi siswa sejak dini. Berdiri sejak awal tahun 2005, perpustakaan ini awalnya hanya memiliki koleksi buku bacaan dasar dan beberapa rak sederhana yang ditempatkan di salah satu sudut ruang kelas. Namun, seiring waktu dan dukungan dari pihak sekolah, guru, serta orang tua murid, perpustakaan terus mengalami perkembangan baik dari segi fasilitas maupun jumlah koleksi buku.</p>
        
        <p style="text-align: justify">Dalam beberapa tahun terakhir, perpustakaan SDN Berat Wetan 1 telah mengalami renovasi dan penataan ulang, menjadikannya ruang yang lebih nyaman, bersih, dan menyenangkan untuk belajar. Kini, perpustakaan tidak hanya menyediakan buku bacaan fiksi dan non-fiksi, tetapi juga buku referensi, ensiklopedia anak, kamus, dan bahkan koleksi bergambar yang mendukung proses pembelajaran tematik di sekolah dasar. Dengan tambahan sentuhan digital dan penataan yang lebih modern, perpustakaan menjadi pusat aktivitas literasi yang aktif di lingkungan sekolah.</p>
        
        <p><strong>Tujuan:</strong> Tujuan utama dari perpustakaan SDN Berat Wetan 1 adalah mendukung proses pembelajaran di sekolah dengan menyediakan sumber informasi yang lengkap dan mudah diakses oleh seluruh siswa dan guru. Perpustakaan juga bertujuan untuk:</p>
        
        <ul>
          <li>Menumbuhkan minat baca siswa sejak usia dini melalui penyediaan bacaan yang menarik dan bervariasi.</li>
          <li>Meningkatkan budaya literasi di lingkungan sekolah dengan mengadakan kegiatan-kegiatan seperti pojok baca, lomba membaca, dan mendongeng.</li>
          <li>Mendukung pembelajaran aktif dan mandiri, di mana siswa dapat mencari informasi tambahan secara mandiri untuk menunjang tugas dan pelajaran.</li>
          <li>Menjadi ruang edukatif yang menyenangkan, di mana siswa merasa nyaman untuk membaca, belajar, dan mengeksplorasi pengetahuan.</li>
        </ul>
        
        <p>Dengan adanya perpustakaan ini, diharapkan SDN Berat Wetan 1 tidak hanya menjadi tempat belajar akademik, tetapi juga tempat untuk membangun karakter, imajinasi, dan kecintaan terhadap ilmu pengetahuan.</p>
        
        <hr style="margin: 20px 0; border-color: rgba(94, 176, 255, 0.2);">
        
        <p><strong><i class="fas fa-map-marker-alt"></i> Alamat:</strong> Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur</p>
        <p><strong><i class="fas fa-envelope"></i> Email:</strong> berat.wetan1@gmail.com</p>
        <p><strong><i class="fas fa-id-card"></i> NPSN:</strong> 20502893</p>
        <p><strong><i class="fas fa-clock"></i> Jam Operasional:</strong><br>
          Senin - Kamis: 06:00 - 14:00 WIB<br>
          Jumat - Sabtu: 06:00 - 13:00 WIB</p>
      </div>
    </section>

    <!-- Visi Misi Section -->
    <section id="visi" class="visi-misi" ref="visi">
      <img class="section-bg" src="{{ asset('web-perpus/img/3.png') }}" alt="Background">
      <div class="section-content" :class="{ 'in-view': isVisiInView }">
        <h2><i class="fas fa-eye"></i> Visi dan Misi</h2>
        <p><strong>Visi:</strong> Menjadi sekolah dasar yang unggul dalam pendidikan, karakter, dan kreativitas, serta mencetak generasi yang berakhlak mulia dan berprestasi.</p>
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

    <!-- Pustakawan Section -->
    <section id="pustakawan" class="pustakawan" ref="pustakawan">
      <img class="section-bg" src="{{ asset('web-perpus/img/4.png') }}" alt="Background">
      <div class="section-content" :class="{ 'in-view': isPustakawanInView }">
        <h2><i class="fas fa-user-graduate"></i> Pustakawan</h2>
        <img src="{{ asset('web-perpus/img/Screenshot-2025-05-01-180615.png') }}" alt="Foto Pustakawan">
        <p><strong>Nama:</strong> Lilik Nurhayati, S.Pd</p>
        <p><strong>Email:</strong> lilik.nur246@guruku.belajar.id</p>
        <p><strong>Jabatan:</strong> Pustakawan</p>
      </div>
    </section>

    <!-- Denah & Lokasi Section -->
    <section id="denah" class="denah" ref="denah">
      <img class="section-bg" src="{{ asset('web-perpus/img/5.png') }}" alt="Background">
      <div class="section-content" :class="{ 'in-view': isDenahInView }">
        <h2><i class="fas fa-map"></i> Denah & Lokasi Perpustakaan</h2>
        
        <div class="maps-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.132515518058!2d112.40273567526795!3d-7.560518392481143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78552b0b1456f7%3A0x8b4ac87fc7a8450!2sSDN%20Berat%20Wetan%201!5e0!3m2!1sid!2sid!4v1714550000000!5m2!1sid!2sid" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
        
        <div class="alamat-detail">
          <p><i class="fas fa-map-marker-alt"></i> <strong>Alamat Lengkap:</strong></p>
          <p>Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur 61351</p>
          <p><i class="fas fa-clock"></i> <strong>Jam Operasional:</strong> Senin-Sabtu, 06:00 - 14:00 WIB</p>
          <p><i class="fas fa-phone"></i> <strong>Kontak:</strong> (0321) 123456</p>
          <a href="https://maps.google.com/?q=SDN+Berat+Wetan+1" target="_blank" class="btn-direksi">
            <i class="fas fa-directions"></i> Buka di Google Maps
          </a>
        </div>
        
        <img src="{{ asset('web-perpus/img/meja-pustakawan.png') }}" alt="Denah Perpustakaan">
      </div>
    </section>

    <footer>
      <p>SDN BERAT WETAN 1 &copy; Famella Firda Levia</p>
      <p style="margin-top: 10px; font-size: 12px; opacity: 0.7;">
        <i class="fas fa-book"></i> Membangun Generasi Literasi Digital
      </p>
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
        const mouseX = ref(0);
        const mouseY = ref(0);
        
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
          
          const scrollPosition = window.scrollY + window.innerHeight;
          const offset = 200;
          
          const informasiSection = document.getElementById('informasi');
          if (informasiSection && scrollPosition > informasiSection.offsetTop + offset) {
            isInfoInView.value = true;
          }
          
          const visiSection = document.getElementById('visi');
          if (visiSection && scrollPosition > visiSection.offsetTop + offset) {
            isVisiInView.value = true;
          }
          
          const pustakawanSection = document.getElementById('pustakawan');
          if (pustakawanSection && scrollPosition > pustakawanSection.offsetTop + offset) {
            isPustakawanInView.value = true;
          }
          
          const denahSection = document.getElementById('denah');
          if (denahSection && scrollPosition > denahSection.offsetTop + offset) {
            isDenahInView.value = true;
          }
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
          
          for (let i = 0; i < 12; i++) {
            const book = document.createElement('div');
            book.className = 'book';
            
            const randomImage = bookImages[Math.floor(Math.random() * bookImages.length)];
            book.style.backgroundImage = `url(${randomImage})`;
            
            const left = Math.random() * 100;
            const top = Math.random() * 100;
            book.style.left = `${left}%`;
            book.style.top = `${top}%`;
            
            const duration = 12 + Math.random() * 18;
            const delay = Math.random() * 8;
            book.style.animationDuration = `${duration}s`;
            book.style.animationDelay = `${delay}s`;
            
            const size = 0.5 + Math.random() * 0.6;
            book.style.transform = `scale(${size})`;
            
            container.appendChild(book);
          }
        };
        
        const initGSAPAnimations = () => {
          if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);
            
            gsap.utils.toArray('.section-content').forEach((section, i) => {
              gsap.from(section, {
                scrollTrigger: {
                  trigger: section,
                  start: 'top 80%',
                  end: 'bottom 20%',
                  toggleActions: 'play none none reverse'
                },
                opacity: 0,
                y: 50,
                duration: 1,
                delay: i * 0.1,
                ease: 'power3.out'
              });
            });
            
            gsap.from('.hero-content', {
              scrollTrigger: {
                trigger: '.hero',
                start: 'top center',
                toggleActions: 'play none none reverse'
              },
              opacity: 0,
              x: -50,
              duration: 1,
              ease: 'power3.out'
            });
            
            gsap.from('.book-animation', {
              scrollTrigger: {
                trigger: '.hero',
                start: 'top center',
                toggleActions: 'play none none reverse'
              },
              opacity: 0,
              x: 50,
              duration: 1,
              ease: 'power3.out'
            });
          }
        };
        
        onMounted(() => {
          window.addEventListener('scroll', handleScroll);
          window.addEventListener('mousemove', handleMouseMove);
          
          createFloatingBooks();
          handleScroll();
          
          setTimeout(() => {
            initGSAPAnimations();
          }, 100);
        });
        
        onUnmounted(() => {
          window.removeEventListener('scroll', handleScroll);
          window.removeEventListener('mousemove', handleMouseMove);
        });
        
        return {
          isMenuActive,
          isScrolled,
          isInfoInView,
          isVisiInView,
          isPustakawanInView,
          isDenahInView,
          mouseX,
          mouseY,
          toggleMenu,
          closeMenu
        };
      }
    }).mount('#app');
  </script>
</body>
</html>