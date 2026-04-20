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
      --bg-color: #000000; /* Background hitam mutlak */
      --primary-color: #0a0a0a;
      --secondary-color: #ffffff; /* Teks utama putih */
      --text-color: #e0e0e0; /* Teks paragraf abu-abu terang */
      
      /* Palet Pastel Baru */
      --accent-pink: #ffb6c1;
      --accent-blue: #aec6cf;
      --accent-purple: #c3b1e1;
      
      --section-bg: rgba(20, 20, 25, 0.65); /* Glassmorphism gelap */
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
      opacity: 0.6; /* Disesuaikan untuk background gelap */
      filter: drop-shadow(0 5px 15px rgba(255, 182, 193, 0.2));
      animation: float 15s infinite ease-in-out;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-30px) rotate(5deg); }
    }

    /* Gradient Background Animation */
    .gradient-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at center, #111111 0%, #000000 100%);
      z-index: -2;
    }

    .gradient-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at var(--mouse-x) var(--mouse-y), 
        rgba(255, 182, 193, 0.15) 0%, /* Pink glow */
        rgba(195, 177, 225, 0.1) 30%,  /* Purple glow */
        rgba(174, 198, 207, 0.05) 50%, /* Blue glow */
        transparent 70%);
      z-index: -1;
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
      background-color: rgba(15, 15, 15, 0.7);
      border-radius: 50px;
      padding: 10px 25px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      z-index: 1000;
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: 1px solid rgba(255, 255, 255, 0.05);
    }

    header.scrolled {
      top: 10px;
      background-color: rgba(10, 10, 10, 0.95);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.8);
      border: 1px solid rgba(255, 182, 193, 0.2);
    }

    header.scrolled .logo-container strong,
    header.scrolled nav a,
    header.scrolled .hamburger span {
      color: var(--secondary-color);
    }

    header.scrolled .hamburger span {
      background-color: var(--secondary-color);
    }

    .logo-nav {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0;
    }

    .logo-container img {
      width: 35px;
      height: auto;
      transition: transform 0.3s ease;
    }

    .logo-container:hover img {
      transform: rotate(15deg);
    }

    .logo-container strong {
      font-size: 18px;
      color: var(--secondary-color);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      font-weight: 600;
      transition: color 0.3s ease;
      background: linear-gradient(90deg, var(--accent-pink), var(--accent-purple));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
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
      color: var(--text-color);
      font-weight: 500;
      transition: all 0.3s ease;
      white-space: nowrap;
      padding: 8px 15px;
      font-size: 15px;
      position: relative;
      cursor: pointer;
    }

    nav a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--accent-pink), var(--accent-blue));
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    nav a:hover {
      color: var(--secondary-color);
    }

    nav a:hover::after {
      width: 100%;
    }

    .login-btn {
      background-color: transparent;
      color: var(--secondary-color);
      border: 2px solid var(--accent-purple);
      padding: 8px 20px;
      border-radius: 30px;
      font-weight: 500;
      transition: all 0.3s ease;
      margin-left: 15px;
      font-size: 15px;
      cursor: pointer;
    }

    .login-btn:hover {
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple));
      border-color: transparent;
      color: #000;
      box-shadow: 0 0 15px rgba(195, 177, 225, 0.4);
    }

    /* Mobile Login Button */
    .mobile-login-btn {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple), var(--accent-blue));
      background-size: 200% auto;
      color: #000;
      border: none;
      padding: 12px 24px;
      border-radius: 50px;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      z-index: 1000;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
      transition: all 0.3s ease;
      font-family: 'Space Grotesk', sans-serif;
      align-items: center;
      gap: 10px;
    }

    .mobile-login-btn:hover {
      background-position: right center;
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(195, 177, 225, 0.5);
    }

    /* Hamburger Menu Styles */
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
      transform-origin: left center;
    }

    .hamburger.active span:nth-child(1) { transform: rotate(45deg); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: rotate(-45deg); }

    .nav-menu {
      display: flex;
      gap: 5px;
    }

    /* Desktop-specific styles */
    @media (min-width: 769px) {
      .nav-container {
        display: flex;
        width: auto;
        justify-content: flex-end;
      }
      .login-btn { order: 2; display: block; }
      .nav-menu { order: 1; }
      .mobile-login-btn { display: none !important; }
    }

    /* Mobile styles */
    @media (max-width: 768px) {
      header { width: 90%; padding: 10px 15px; }
      .login-btn { display: none !important; }
      .mobile-login-btn { display: flex; animation: slideUp 0.5s ease-out; }
      .hamburger { display: flex; }

      .nav-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100vh;
        background-color: rgba(10, 10, 10, 0.98);
        backdrop-filter: blur(10px);
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 30px;
        transition: left 0.5s cubic-bezier(0.77, 0.2, 0.05, 1);
        z-index: 999;
      }

      .nav-menu.active { left: 0; }
      nav a { color: var(--secondary-color); font-size: 22px; padding: 10px 0; }
      nav a::after { background: var(--accent-pink); }
      .section-content { margin: 0 10px; padding: 25px 15px !important; }
      nav a, .mobile-login-btn, .hero button { cursor: pointer; -webkit-tap-highlight-color: transparent; }
      .info p, .visi-misi p, ol, ul { font-size: 14px; line-height: 1.6; }
      ul { padding-left: 20px !important; }
      ul li { margin-bottom: 12px; }
      .maps-container iframe { height: 250px; }
      .denah img { width: 100%; height: auto; }
    }
    
    @keyframes slideUp {
      from { transform: translateY(100px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    /* Hero Section */
    .hero {
      position: relative;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 150px 80px 100px;
      min-height: 100vh;
      width: 100%;
      color: var(--secondary-color);
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 30% 50%, rgba(255,182,193,0.08) 0%, rgba(0,0,0,0.8) 60%, #000000 100%);
      z-index: 1;
    }

    .hero-content,
    .hero img {
      position: relative;
      z-index: 2;
    }

    .hero-content {
      max-width: 50%;
      text-align: left;
      padding: 20px;
    }

    .hero h1 {
      font-size: clamp(28px, 5vw, 50px);
      color: var(--secondary-color);
      margin-bottom: 20px;
      line-height: 1.2;
      font-weight: 700;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp 1s ease forwards 0.3s;
      text-shadow: 0 5px 15px rgba(0, 0, 0, 0.8);
    }

    .hero .highlight {
      background: linear-gradient(90deg, var(--accent-pink), var(--accent-purple), var(--accent-blue));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: none;
      filter: drop-shadow(0 0 10px rgba(195, 177, 225, 0.3));
    }

    /* Book Animation Styles */
    .book-animation {
      position: absolute;
      right: 20px;
      top: -5%;
      transform: translateY(-50%);
      width: 600px;
      height: 700px;
      background-image: url('{{ asset("web-perpus/img/bukubaru.png") }}');
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      opacity: 0;
      z-index: 2;
      filter: drop-shadow(0 0 25px rgba(174, 198, 207, 0.4));
      animation: floatBook 6s ease-in-out infinite, fadeInRight 1s ease forwards 0.7s;
    }

    @keyframes floatBook {
      0%, 100% { transform: translateY(-50%) rotate(0deg); }
      25% { transform: translateY(-55%) rotate(2deg); }
      50% { transform: translateY(-45%) rotate(-2deg); }
      75% { transform: translateY(-55%) rotate(2deg); }
    }

    .hero p {
      margin-top: 20px;
      font-size: clamp(16px, 2vw, 20px);
      line-height: 1.6;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp 1s ease forwards 0.5s;
      color: var(--text-color);
    }

    /* Unique Telusuri Buku Button */
    .hero button {
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple), var(--accent-blue));
      background-size: 200% auto;
      color: #000000;
      border: none;
      padding: 16px 35px;
      border-radius: 50px;
      margin-top: 30px;
      font-size: 18px;
      font-weight: 700;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp 1s ease forwards 0.7s;
      position: relative;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(195, 177, 225, 0.2);
      font-family: 'Fredoka One', cursive;
      letter-spacing: 1px;
      text-transform: uppercase;
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      cursor: pointer;
      z-index: 3;
    }

    .hero button:hover {
      background-position: right center;
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 10px 25px rgba(255, 182, 193, 0.4);
    }

    .hero button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: all 0.7s ease;
    }

    .hero button:hover::before { left: 100%; }
    .hero button::after {
      content: '📚';
      position: absolute;
      right: -20px;
      top: 50%;
      transform: translateY(-50%);
      opacity: 0;
      transition: all 0.3s ease;
    }

    .hero button:hover::after { right: 15px; opacity: 1; }

    .hero img {
      max-width: 40%;
      height: auto;
      margin-left: 50px;
      opacity: 0;
      transform: translateX(50px);
      animation: fadeInRight 1s ease forwards 0.5s;
      filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.8));
    }

    section {
      padding: 100px 40px;
      scroll-margin-top: 80px;
      color: var(--text-color);
      width: 100%;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      position: relative;
      overflow: hidden;
    }

    .section-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
      opacity: 0.05; /* Dibuat sangat redup agar menyatu dengan background hitam */
    }

    .section-content {
      max-width: 1000px;
      width: 100%;
      padding: 40px;
      background-color: var(--section-bg);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8), inset 0 0 0 1px rgba(255,255,255,0.05);
      position: relative;
      z-index: 1;
      opacity: 0;
      transform: translateY(50px);
      transition: all 0.8s ease;
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
    }

    /* scroll animations for sections */
    .section-content.in-view { opacity: 1; transform: translateY(0); }
    #informasi .section-content.in-view { animation: slideUpFadeIn 0.8s ease-out forwards; }
    #visi .section-content.in-view { animation: slideRightFadeIn 0.8s ease-out forwards; }
    #pustakawan .section-content.in-view { animation: zoomInFadeIn 0.8s ease-out forwards; }
    #denah .section-content.in-view { animation: slideLeftFadeIn 0.8s ease-out forwards; }

    @keyframes slideUpFadeIn {
      0% { opacity: 0; transform: translateY(50px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideRightFadeIn {
      0% { opacity: 0; transform: translateX(-50px); }
      100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideLeftFadeIn {
      0% { opacity: 0; transform: translateX(50px); }
      100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes zoomInFadeIn {
      0% { opacity: 0; transform: scale(0.9); }
      100% { opacity: 1; transform: scale(1); }
    }

    .info p, .visi-misi p {
      text-align: left;
      padding: 10px 0;
      line-height: 1.8;
      font-size: 18px;
    }

    .info h2, .visi-misi h2, .pustakawan h2, .denah h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: clamp(24px, 4vw, 36px);
      color: var(--secondary-color);
      font-weight: 700;
      position: relative;
      display: inline-block;
      width: 100%;
    }

    .info h2::after,
    .visi-misi h2::after,
    .pustakawan h2::after,
    .denah h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      background: linear-gradient(90deg, var(--accent-pink), var(--accent-blue), var(--accent-purple));
      border-radius: 2px;
    }

    .pustakawan img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin: 20px auto;
      border: 4px solid transparent;
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-blue)) border-box;
      -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      padding: 4px;
      display: block;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
      transition: all 0.5s ease;
    }

    .pustakawan img:hover {
      transform: scale(1.05);
      box-shadow: 0 0 20px rgba(195, 177, 225, 0.4);
    }

    /* Google Maps Container */
    .maps-container {
      width: 100%;
      margin: 30px auto;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      transition: all 0.5s ease;
      border: 1px solid rgba(255,255,255,0.05);
    }

    .maps-container:hover {
      transform: scale(1.02);
      box-shadow: 0 0 30px rgba(174, 198, 207, 0.2);
    }

    .maps-container iframe { width: 100%; height: 450px; border: 0; display: block; filter: invert(90%) hue-rotate(180deg); /* Supaya map cocok dgn dark mode */ }

    .alamat-detail {
      margin-top: 20px;
      padding: 20px;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 10px;
      text-align: center;
      border: 1px solid rgba(255,255,255,0.05);
    }

    .alamat-detail p { margin: 5px 0; font-size: 16px; }
    .alamat-detail i { margin-right: 10px; color: var(--accent-pink); }

    .btn-direksi {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 25px;
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple));
      color: #000;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-direksi:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(255, 182, 193, 0.3);
    }

    .denah img {
      width: 100%;
      max-width: 800px;
      margin: 30px auto;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      display: block;
      transition: all 0.5s ease;
      opacity: 0.9;
    }

    .denah img:hover {
      transform: scale(1.02);
      box-shadow: 0 0 30px rgba(195, 177, 225, 0.2);
      opacity: 1;
    }

    footer {
      text-align: center;
      padding: 30px;
      background-color: #050505;
      color: var(--text-color);
      font-size: 14px;
      width: 100%;
      position: relative;
      z-index: 2;
      border-top: 1px solid rgba(255,255,255,0.05);
    }

    ol, ul {
      text-align: left;
      padding-left: 20px;
      line-height: 1.8;
      font-size: 18px;
    }

    ol li, ul li {
      margin-bottom: 15px;
      position: relative;
    }
    
    ol li { padding-left: 10px; }
    ul li { padding-left: 10px; }

    ol li::marker {
      color: var(--accent-pink);
      font-weight: bold;
    }

    ul li::before {
      content: '✦';
      position: absolute;
      left: -20px;
      top: 0;
      color: var(--accent-blue);
    }

    /* Animations */
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInRight { to { opacity: 1; transform: translateX(0); } }

    /* Spline Viewer Styles */
    .spline-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -3;
      opacity: 0.2; /* Diredamkan agar tidak menutupi teks */
    }

    /* Desktop-specific improvements */
    @media (min-width: 1025px) {
      .hero { flex-direction: row; justify-content: space-between; padding: 150px 80px 100px; }
      .hero-content { max-width: 50%; }
      .hero img { max-width: 40%; animation: float 6s ease-in-out infinite; }
      @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-20px); } 100% { transform: translateY(0px); } }
    }

    @media (max-width: 1024px) {
      .hero { flex-direction: column; text-align: center; padding: 180px 40px 100px; }
      .hero-content { max-width: 100%; text-align: center; }
      .book-animation { position: relative; right: auto; top: auto; transform: none; margin: 30px auto; width: 250px; height: 350px; }
      .hero img { max-width: 80%; margin: 40px auto 0; }
    }

    @media (max-width: 768px) {
      header { width: 90%; padding: 10px 15px; }
      .logo-container img { width: 30px; }
      .logo-container strong { font-size: 16px; }
      .section-content { padding: 30px 20px; }
      .info p, .visi-misi p, ol, ul { font-size: 16px; }
      .pustakawan img { width: 120px; height: 120px; }
      .book-animation { width: 200px; height: 300px; }
      .hero button { padding: 14px 30px; font-size: 16px; }
      .maps-container iframe { height: 300px; }
    }

    @media (max-width: 480px) {
      .hero { padding: 150px 20px 80px; }
      .hero h1 { font-size: 28px; }
      .hero p { font-size: 16px; }
      .hero button { padding: 12px 24px; font-size: 15px; }
      section { padding: 80px 20px; }
      .section-content { padding: 20px 15px; }
      footer { padding: 20px; font-size: 12px; }
      .book-animation { width: 180px; height: 270px; }
      .maps-container iframe { height: 250px; }
    }
  </style>
</head>
<body>
  <div id="app">
    <div class="floating-books" id="floatingBooks"></div>

    <div class="gradient-bg"></div>
    <div class="gradient-overlay" :style="{ '--mouse-x': mouseX + 'px', '--mouse-y': mouseY + 'px' }"></div>

    <div class="spline-container">
      <spline-viewer url="https://prod.spline.design/PBQQBw8bfXDhBo7w/scene.splinecode" events-target="global"></spline-viewer>
    </div>

    <header :class="{scrolled: isScrolled}">
      <div class="logo-nav">
        <div class="logo-container">
          <img 
            src="{{ asset('web-perpus/img/Open_Book_Vector_Illustration_Flat_Logo_Stock_Vector_-_Illustration_of_flat__minimal__187678563__3_-removebg-preview - Copy.png') }}" 
            alt="logo Perpustakaan">
          <strong>LIBWE</strong>
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

    <button class="mobile-login-btn" onclick="window.location.href='{{ route('login') }}'">
      <i class="fas fa-sign-in-alt"></i> Masuk sebagai Pustakawan
    </button>

    <div class="hero">
      <div class="hero-content">
        <h1>Selamat datang di Perpustakaan Online <br><span class="highlight">SDN Berat Wetan 1</span></h1>
        <p>Membangun generasi cerdas dan berbudaya literasi.</p>
        <button onclick="window.location.href='{{ route('katalog') }}'">TELUSURI BUKU</button>
      </div>
      
      <div class="book-animation"></div>
    </div>

    <section id="informasi" class="info" ref="informasi">
      <img class="section-bg" src="{{ asset('web-perpus/img/2.png') }}" alt="Background">
      <div class="section-content" :class="{ 'in-view': isInfoInView }">
        <h2>Informasi</h2>
        <p style="text-align: justify">Perpustakaan SDN Berat Wetan 1 didirikan sebagai bagian dari komitmen sekolah untuk meningkatkan minat baca dan literasi siswa sejak dini. Berdiri sejak awal tahun 2005, perpustakaan ini awalnya hanya memiliki koleksi buku bacaan dasar dan beberapa rak sederhana yang ditempatkan di salah satu sudut ruang kelas. Namun, seiring waktu dan dukungan dari pihak sekolah, guru, serta orang tua murid, perpustakaan terus mengalami perkembangan baik dari segi fasilitas maupun jumlah koleksi buku.<br><br>

        Dalam beberapa tahun terakhir, perpustakaan SDN Berat Wetan 1 telah mengalami renovasi dan penataan ulang, menjadikannya ruang yang lebih nyaman, bersih, dan menyenangkan untuk belajar. Kini, perpustakaan tidak hanya menyediakan buku bacaan fiksi dan non-fiksi, tetapi juga buku referensi, ensiklopedia anak, kamus, dan bahkan koleksi bergambar yang mendukung proses pembelajaran tematik di sekolah dasar. Dengan tambahan sentuhan digital dan penataan yang lebih modern, perpustakaan menjadi pusat aktivitas literasi yang aktif di lingkungan sekolah.</p><br>
          <p style="text-align: justify;">
  <strong>Tujuan:</strong> Tujuan utama dari perpustakaan SDN Berat Wetan 1 adalah mendukung proses pembelajaran di sekolah dengan menyediakan sumber informasi yang lengkap dan mudah diakses oleh seluruh siswa dan guru. Perpustakaan juga bertujuan untuk:
</p>

<ul style="text-align: justify; padding-left: 75px;">
  <li>Menumbuhkan minat baca siswa sejak usia dini melalui penyediaan bacaan yang menarik dan bervariasi.</li><br> 
  <li>Meningkatkan budaya literasi di lingkungan sekolah dengan mengadakan kegiatan-kegiatan seperti pojok baca, lomba membaca, dan mendongeng.</li><br>
  <li>Mendukung pembelajaran aktif dan mandiri, di mana siswa dapat mencari informasi tambahan secara mandiri untuk menunjang tugas dan pelajaran.</li><br>
  <li>Menjadi ruang edukatif yang menyenangkan, di mana siswa merasa nyaman untuk membaca, belajar, dan mengeksplorasi pengetahuan.</li><br>
  <li>Dengan adanya perpustakaan ini, diharapkan SDN Berat Wetan 1 tidak hanya menjadi tempat belajar akademik, tetapi juga tempat untuk membangun karakter, imajinasi, dan kecintaan terhadap ilmu pengetahuan.</li>
</ul><br>

        <p><strong>Alamat:</strong> Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur</p>
        <p><strong>Email:</strong> berat.wetan1@gmail.com</p>
        <p><strong>NPSN:</strong> 20502893</p>
        <p><strong>Jam Operasional:</strong><br>
          Senin - Kamis: 06:00 - 14:00 WIB<br>
          Jumat - Sabtu: 06:00 - 13:00 WIB</p>
      </div>
    </section>

    <section id="visi" class="visi-misi" ref="visi">
      <img class="section-bg" src="{{ asset('web-perpus/img/3.png') }}" alt="Background">
      <div class="section-content" :class="{ 'in-view': isVisiInView }">
        <h2>Visi dan Misi</h2>
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

    <section id="pustakawan" class="pustakawan" ref="pustakawan">
      <img class="section-bg" src="{{ asset('web-perpus/img/4.png') }}" alt="Background">
      <div class="section-content" :class="{ 'in-view': isPustakawanInView }">
        <h2>Pustakawan</h2>
        <img src="{{ asset('web-perpus/img/Screenshot-2025-05-01-180615.png') }}" alt="Foto Pustakawan">
        <p><strong>Nama:</strong> Lilik Nurhayati, S.Pd</p>
        <p><strong>Email:</strong> lilik.nur246@guruku.belajar.id</p>
        <p><strong>Jabatan:</strong> Pustakawan</p>
      </div>
    </section>

    <section id="denah" class="denah" ref="denah">
      <img class="section-bg" src="{{ asset('web-perpus/img/5.png') }}" alt="Background">
      <div class="section-content" :class="{ 'in-view': isDenahInView }">
        <h2>Denah & Lokasi Perpustakaan</h2>
        
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
      SDN BERAT WETAN 1 © Famella Firda Levia
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
          // Header scroll effect
          isScrolled.value = window.scrollY > 50;
          
          // Section in-view detection
          const scrollPosition = window.scrollY + window.innerHeight;
          
          const informasiSection = document.getElementById('informasi');
          if (informasiSection && scrollPosition > informasiSection.offsetTop + 100) {
            isInfoInView.value = true;
          }
          
          const visiSection = document.getElementById('visi');
          if (visiSection && scrollPosition > visiSection.offsetTop + 100) {
            isVisiInView.value = true;
          }
          
          const pustakawanSection = document.getElementById('pustakawan');
          if (pustakawanSection && scrollPosition > pustakawanSection.offsetTop + 100) {
            isPustakawanInView.value = true;
          }
          
          const denahSection = document.getElementById('denah');
          if (denahSection && scrollPosition > denahSection.offsetTop + 100) {
            isDenahInView.value = true;
          }
        };
        
        const preventHorizontalScroll = () => {
          if (window.scrollX !== 0) {
            window.scrollTo(0, window.scrollY);
          }
        };
        
        const handleMouseMove = (e) => {
          mouseX.value = e.clientX;
          mouseY.value = e.clientY;
          
          // Update gradient overlay position
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
          
          // Clear existing books
          container.innerHTML = '';
          
          // Create 10 floating books
          for (let i = 0; i < 10; i++) {
            const book = document.createElement('div');
            book.className = 'book';
            
            // Random book image
            const randomImage = bookImages[Math.floor(Math.random() * bookImages.length)];
            book.style.backgroundImage = `url(${randomImage})`;
            
            // Random position
            const left = Math.random() * 100;
            const top = Math.random() * 100;
            book.style.left = `${left}%`;
            book.style.top = `${top}%`;
            
            // Random animation duration and delay
            const duration = 15 + Math.random() * 15;
            const delay = Math.random() * 5;
            book.style.animationDuration = `${duration}s`;
            book.style.animationDelay = `${delay}s`;
            
            // Random size
            const size = 0.8 + Math.random() * 0.7;
            book.style.transform = `scale(${size})`;
            
            container.appendChild(book);
          }
        };
        
        onMounted(() => {
          window.addEventListener('scroll', handleScroll);
          window.addEventListener('scroll', preventHorizontalScroll);
          window.addEventListener('mousemove', handleMouseMove);
          
          // Create floating books
          createFloatingBooks();
          
          // Trigger initial check
          handleScroll();
        });
        
        onUnmounted(() => {
          window.removeEventListener('scroll', handleScroll);
          window.removeEventListener('scroll', preventHorizontalScroll);
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