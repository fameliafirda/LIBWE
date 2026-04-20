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
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Syncopate:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">
  
  {{-- Spline Viewer --}}
  <script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>
  
  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --bg-dark: #050505;
      --card-bg: rgba(20, 20, 25, 0.8);
      --accent-neon: #ff007a; /* Cyber Pink */
      --accent-cyan: #00f3ff; /* Cyber Blue */
      --text-main: #ffffff;
      --text-dim: #b0b0b0;
      --glass-border: rgba(255, 255, 255, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: var(--bg-dark);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', sans-serif;
      overflow-x: hidden;
      line-height: 1.6;
    }

    /* Futuristik Background */
    .cyber-grid {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        linear-gradient(rgba(0, 243, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 243, 255, 0.03) 1px, transparent 1px);
      background-size: 50px 50px;
      z-index: -5;
    }

    .glow-sphere {
      position: fixed;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(255, 0, 122, 0.15) 0%, transparent 70%);
      top: -200px;
      right: -200px;
      z-index: -4;
    }

    /* Navbar Aesthetic */
    header {
      position: fixed;
      top: 0;
      width: 100%;
      padding: 20px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
      transition: 0.4s;
      border-bottom: 1px solid transparent;
    }

    header.scrolled {
      background: rgba(5, 5, 5, 0.85);
      backdrop-filter: blur(15px);
      padding: 15px 50px;
      border-bottom: 1px solid var(--glass-border);
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-container img {
      width: 40px;
      filter: drop-shadow(0 0 8px var(--accent-cyan));
    }

    .logo-container strong {
      font-family: 'Syncopate', sans-serif;
      font-size: 20px;
      letter-spacing: 2px;
      background: linear-gradient(to right, #fff, var(--accent-cyan));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    nav a {
      color: var(--text-main);
      text-decoration: none;
      margin: 0 15px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: 0.3s;
      font-weight: 600;
    }

    nav a:hover {
      color: var(--accent-cyan);
      text-shadow: 0 0 10px var(--accent-cyan);
    }

    .login-btn {
      background: transparent;
      color: var(--text-main);
      border: 1px solid var(--accent-cyan);
      padding: 8px 25px;
      border-radius: 4px;
      cursor: pointer;
      transition: 0.3s;
      font-family: 'Space Grotesk';
      font-weight: bold;
    }

    .login-btn:hover {
      background: var(--accent-cyan);
      color: #000;
      box-shadow: 0 0 20px var(--accent-cyan);
    }

    /* Hero Section */
    .hero {
      height: 100vh;
      display: flex;
      align-items: center;
      padding: 0 10%;
      position: relative;
    }

    .hero-content {
      max-width: 600px;
      z-index: 10;
    }

    .hero h1 {
      font-family: 'Syncopate', sans-serif;
      font-size: 3.5rem;
      line-height: 1.1;
      margin-bottom: 20px;
    }

    .highlight {
      color: var(--accent-neon);
      text-shadow: 3px 3px 0px var(--accent-cyan);
    }

    .hero p {
      color: var(--text-dim);
      font-size: 1.2rem;
      margin-bottom: 30px;
    }

    .hero button {
      background: var(--accent-neon);
      color: white;
      border: none;
      padding: 18px 40px;
      font-family: 'Syncopate';
      font-size: 12px;
      letter-spacing: 2px;
      cursor: pointer;
      clip-path: polygon(10% 0, 100% 0, 90% 100%, 0% 100%);
      transition: 0.3s;
    }

    .hero button:hover {
      background: var(--accent-cyan);
      transform: scale(1.05);
      color: #000;
    }

    .book-animation {
      position: absolute;
      right: 5%;
      width: 500px;
      height: 500px;
      background-image: url('{{ asset("web-perpus/img/bukubaru.png") }}');
      background-size: contain;
      background-repeat: no-repeat;
      filter: drop-shadow(0 0 30px rgba(0, 243, 255, 0.4));
      animation: float 5s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(2deg); }
    }

    /* Sections */
    section {
      padding: 100px 10%;
      position: relative;
    }

    .section-content {
      background: var(--card-bg);
      padding: 60px;
      border: 1px solid var(--glass-border);
      border-radius: 2px; /* Square/Boxy Cyberpunk Look */
      backdrop-filter: blur(10px);
      position: relative;
    }

    .section-content::before {
      content: '';
      position: absolute;
      top: -1px;
      left: -1px;
      width: 40px;
      height: 40px;
      border-top: 3px solid var(--accent-cyan);
      border-left: 3px solid var(--accent-cyan);
    }

    h2 {
      font-family: 'Syncopate', sans-serif;
      font-size: 2rem;
      margin-bottom: 40px;
      color: var(--accent-cyan);
      text-transform: uppercase;
    }

    .section-bg {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0; left: 0;
      opacity: 0.05;
      object-fit: cover;
      pointer-events: none;
    }

    /* Info Styles */
    .info p { color: #ccc; margin-bottom: 20px; }
    ul, ol { margin-left: 20px; color: var(--accent-cyan); }
    li { margin-bottom: 10px; color: #ccc; }

    /* Pustakawan */
    .pustakawan img {
      width: 200px;
      height: 200px;
      border: 4px solid var(--accent-neon);
      margin-bottom: 20px;
      filter: grayscale(0.5) contrast(1.2);
    }

    /* Maps & Denah */
    .maps-container {
      border: 1px solid var(--accent-cyan);
      margin: 30px 0;
    }
    
    .btn-direksi {
      background: var(--accent-cyan);
      color: #000;
      padding: 12px 25px;
      display: inline-block;
      text-decoration: none;
      font-weight: bold;
      margin-top: 20px;
    }

    .denah img {
      width: 100%;
      border: 1px solid var(--glass-border);
      margin-top: 20px;
    }

    footer {
      padding: 40px;
      text-align: center;
      background: #000;
      border-top: 1px solid var(--glass-border);
      font-family: 'Space Grotesk';
      letter-spacing: 2px;
      color: var(--accent-cyan);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .hero h1 { font-size: 2.2rem; }
      .book-animation { display: none; }
      header { padding: 20px; }
      nav { display: none; }
      .section-content { padding: 30px; }
    }
  </style>
</head>
<body>
  <div id="app">
    <div class="cyber-grid"></div>
    <div class="glow-sphere"></div>

    <header :class="{scrolled: isScrolled}">
      <div class="logo-container">
        <img src="{{ asset('web-perpus/img/Open_Book_Vector_Illustration_Flat_Logo_Stock_Vector_-_Illustration_of_flat__minimal__187678563__3_-removebg-preview - Copy.png') }}" alt="logo">
        <strong>LIBWE</strong>
      </div>
      
      <nav>
        <a href="#informasi">Informasi</a>
        <a href="#visi">Visi Misi</a>
        <a href="#pustakawan">Pustakawan</a>
        <a href="#denah">Lokasi</a>
      </nav>

      <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">ADMIN_LOGIN</button>
    </header>

    <div class="hero">
      <div class="hero-content">
        <h1>LIBRARY <br><span class="highlight">SDN BW 01</span></h1>
        <p>// ACCESSING KNOWLEDGE DATABASE _V.2026</p>
        <button onclick="window.location.href='{{ route('katalog') }}'">OPEN CATALOGUE</button>
      </div>
      <div class="book-animation"></div>
    </div>

    <section id="informasi">
      <img class="section-bg" src="{{ asset('web-perpus/img/2.png') }}" alt="Bg">
      <div class="section-content">
        <h2>_DATA_INFORMASI</h2>
        <p style="text-align: justify">Perpustakaan SDN Berat Wetan 1 didirikan sejak 2005 sebagai pusat literasi digital dan fisik. Mengintegrasikan kenyamanan ruang belajar modern dengan koleksi buku yang komprehensif.</p>
        
        <p><strong>GOALS:</strong></p>
        <ul>
          <li>Membangun peradaban literasi di era digital.</li>
          <li>Akses informasi tanpa batas bagi siswa.</li>
          <li>Ruang eksplorasi kreatif dan mandiri.</li>
        </ul>

        <div style="margin-top:30px; border-top: 1px solid var(--glass-border); padding-top: 20px;">
          <p><i class="fas fa-envelope" style="color:var(--accent-cyan)"></i> berat.wetan1@gmail.com</p>
          <p><i class="fas fa-fingerprint" style="color:var(--accent-cyan)"></i> NPSN: 20502893</p>
          <p><i class="fas fa-clock" style="color:var(--accent-cyan)"></i> SENIN-SABTU [06:00 - 14:00]</p>
        </div>
      </div>
    </section>

    <section id="visi">
      <img class="section-bg" src="{{ asset('web-perpus/img/3.png') }}" alt="Bg">
      <div class="section-content">
        <h2>_VISI_&_MISI</h2>
        <p><strong style="color:var(--accent-neon)">VISION:</strong> Menjadi sekolah dasar unggul dalam teknologi, karakter, dan kreativitas global.</p>
        <p><strong>MISSION:</strong></p>
        <ol>
          <li>Optimalisasi kualitas edukasi berbasis data.</li>
          <li>Integritas karakter siswa yang berakhlak mulia.</li>
          <li>Akselerasi budaya literasi sekolah.</li>
        </ol>
      </div>
    </section>

    <section id="pustakawan">
      <img class="section-bg" src="{{ asset('web-perpus/img/4.png') }}" alt="Bg">
      <div class="section-content" style="text-align: center;">
        <h2>_PUSTAKAWAN</h2>
        <img src="{{ asset('web-perpus/img/Screenshot-2025-05-01-180615.png') }}" alt="Pustakawan">
        <h3>Lilik Nurhayati, S.Pd</h3>
        <p style="color:var(--accent-cyan)">LIBRARIAN OFFICER</p>
      </div>
    </section>

    <section id="denah">
      <img class="section-bg" src="{{ asset('web-perpus/img/5.png') }}" alt="Bg">
      <div class="section-content">
        <h2>_LOKASI_&_DENAH</h2>
        <div class="maps-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.132515518058!2d112.40273567526795!3d-7.560518392481143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78552b0b1456f7%3A0x8b4ac87fc7a8450!2sSDN%20Berat%20Wetan%201!5e0!3m2!1sid!2sid!4v1714550000000!5m2!1sid!2sid" 
            height="400" style="border:0; width:100%; filter: invert(90%) hue-rotate(180deg);" 
            allowfullscreen="" loading="lazy">
          </iframe>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <div>
            <p><strong>KOORDINAT:</strong></p>
            <p>Jl. KH. Abdul Fattah No.04, Gedeg, Mojokerto.</p>
            <a href="https://maps.google.com/?q=SDN+Berat+Wetan+1" class="btn-direksi">GET_DIRECTIONS</a>
          </div>
          <div>
            <img src="{{ asset('web-perpus/img/meja-pustakawan.png') }}" alt="Denah" style="width: 100%;">
          </div>
        </div>
      </div>
    </section>

    <footer>
      SYSTEM_OPERATIONAL // SDN BERAT WETAN 1 © 2026
    </footer>
  </div>

  <script>
    const { createApp, ref, onMounted } = Vue;
    createApp({
      setup() {
        const isScrolled = ref(false);
        
        onMounted(() => {
          window.addEventListener('scroll', () => {
            isScrolled.value = window.scrollY > 50;
          });
        });

        return { isScrolled };
      }
    }).mount('#app');
  </script>
</body>
</html>