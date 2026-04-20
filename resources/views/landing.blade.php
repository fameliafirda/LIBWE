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
  
  {{-- Spline Viewer --}}
  <script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>
  
  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --primary: #000000;
      --secondary: #ffffff;
      --accent-pink: #ff00ff;
      --accent-blue: #00f2ff;
      --accent-purple: #7000ff;
      --glass: rgba(255, 255, 255, 0.03);
      --glass-border: rgba(255, 255, 255, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: var(--primary);
      color: var(--secondary);
      font-family: 'Space Grotesk', sans-serif;
      overflow-x: hidden;
      scroll-behavior: smooth;
    }

    /* Background futuristic overlay */
    .bg-grid {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        linear-gradient(rgba(255, 0, 255, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 242, 255, 0.05) 1px, transparent 1px);
      background-size: 50px 50px;
      z-index: -2;
    }

    .bg-glow {
      position: fixed;
      top: 50%;
      left: 50%;
      width: 100vw;
      height: 100vh;
      background: radial-gradient(circle at center, var(--accent-purple) 0%, transparent 70%);
      transform: translate(-50%, -50%);
      opacity: 0.15;
      filter: blur(100px);
      z-index: -1;
    }

    /* Floating Navbar */
    header {
      position: fixed;
      top: 25px;
      left: 50%;
      transform: translateX(-50%);
      width: 90%;
      max-width: 1200px;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(15px);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      padding: 15px 30px;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: 0.4s;
    }

    header.scrolled {
      top: 10px;
      width: 95%;
      border-color: var(--accent-blue);
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-container img {
      width: 32px;
      filter: drop-shadow(0 0 8px var(--accent-blue));
    }

    .logo-container strong {
      font-size: 20px;
      letter-spacing: 2px;
      font-weight: 700;
      background: linear-gradient(to right, var(--secondary), var(--accent-blue));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    nav {
      display: flex;
      gap: 25px;
      align-items: center;
    }

    nav a {
      text-decoration: none;
      color: var(--secondary);
      font-size: 14px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: 0.3s;
      opacity: 0.7;
    }

    nav a:hover {
      opacity: 1;
      color: var(--accent-pink);
      text-shadow: 0 0 10px var(--accent-pink);
    }

    .login-btn {
      background: var(--secondary);
      color: var(--primary);
      border: none;
      padding: 10px 24px;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }

    .login-btn:hover {
      background: var(--accent-blue);
      box-shadow: 0 0 20px var(--accent-blue);
      transform: translateY(-2px);
    }

    /* Hero Section */
    .hero {
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 0 20px;
      position: relative;
    }

    .hero h1 {
      font-size: clamp(40px, 8vw, 90px);
      line-height: 0.9;
      font-weight: 800;
      margin-bottom: 20px;
      text-transform: uppercase;
    }

    .hero .highlight {
      display: block;
      color: transparent;
      -webkit-text-stroke: 1px var(--secondary);
      transition: 0.5s;
    }

    .hero h1:hover .highlight {
      color: var(--accent-pink);
      -webkit-text-stroke: 0px;
      text-shadow: 0 0 30px var(--accent-pink);
    }

    .hero p {
      font-size: 18px;
      opacity: 0.6;
      max-width: 600px;
      margin-bottom: 40px;
    }

    .hero-btn {
      background: transparent;
      color: var(--secondary);
      border: 1px solid var(--secondary);
      padding: 18px 40px;
      font-size: 16px;
      font-weight: 700;
      letter-spacing: 2px;
      border-radius: 100px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: 0.3s;
    }

    .hero-btn:hover {
      border-color: var(--accent-blue);
      color: var(--primary);
      background: var(--accent-blue);
      box-shadow: 0 0 40px var(--accent-blue);
    }

    /* Section Cards */
    section {
      padding: 100px 5%;
      display: flex;
      justify-content: center;
    }

    .section-content {
      width: 100%;
      max-width: 1100px;
      background: var(--glass);
      border: 1px solid var(--glass-border);
      backdrop-filter: blur(10px);
      padding: 60px;
      border-radius: 40px;
      position: relative;
      transition: 0.5s ease;
    }

    .section-content:hover {
      border-color: var(--accent-purple);
      box-shadow: 0 0 50px rgba(112, 0, 255, 0.1);
    }

    h2 {
      font-size: 48px;
      margin-bottom: 40px;
      text-transform: uppercase;
      background: linear-gradient(to right, #fff, #666);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .section-content p {
      line-height: 1.8;
      font-size: 18px;
      opacity: 0.8;
      color: #ccc;
    }

    /* List styling Y2K accent */
    ul, ol {
      margin: 30px 0;
      list-style: none;
    }

    ul li, ol li {
      margin-bottom: 15px;
      padding-left: 30px;
      position: relative;
    }

    ul li::before {
      content: '→';
      position: absolute;
      left: 0;
      color: var(--accent-blue);
    }

    /* Images */
    .pustakawan img {
      width: 200px;
      height: 200px;
      border-radius: 30px;
      object-fit: cover;
      border: 1px solid var(--accent-pink);
      padding: 10px;
      margin-bottom: 20px;
    }

    .denah img {
      width: 100%;
      border-radius: 20px;
      filter: grayscale(1) invert(1);
      opacity: 0.7;
      transition: 0.5s;
    }

    .denah img:hover {
      filter: grayscale(0) invert(0);
      opacity: 1;
    }

    .maps-container {
      border-radius: 24px;
      overflow: hidden;
      border: 1px solid var(--glass-border);
      margin: 40px 0;
    }

    /* Footer */
    footer {
      padding: 60px;
      text-align: center;
      border-top: 1px solid var(--glass-border);
      font-size: 12px;
      letter-spacing: 2px;
      opacity: 0.5;
    }

    /* Mobile */
    @media (max-width: 768px) {
      header { padding: 10px 20px; }
      nav { display: none; }
      .hero h1 { font-size: 50px; }
      .section-content { padding: 30px; border-radius: 20px; }
    }

    /* Remove cursor follower styles as requested */
    .gradient-overlay { display: none; }
  </style>
</head>
<body>
  <div id="app">
    <div class="bg-grid"></div>
    <div class="bg-glow"></div>

    <header :class="{scrolled: isScrolled}">
      <div class="logo-container">
        <img src="{{ asset('web-perpus/img/Open_Book_Vector_Illustration_Flat_Logo_Stock_Vector_-_Illustration_of_flat__minimal__187678563__3_-removebg-preview - Copy.png') }}" alt="logo">
        <strong>LIBWE</strong>
      </div>
      
      <nav>
        <a href="#informasi">Info</a>
        <a href="#visi">Visi Misi</a>
        <a href="#pustakawan">Pustakawan</a>
        <a href="#denah">Lokasi</a>
        <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">MASUK</button>
      </nav>
    </header>

    <div class="hero">
      <div class="hero-content">
        <h1>Digital <span class="highlight">Library</span> SDN BW 1</h1>
        <p>Membangun masa depan melalui akses literasi tanpa batas di era digital.</p>
        <button class="hero-btn" onclick="window.location.href='{{ route('katalog') }}'">EXPLORE CATALOG</button>
      </div>
    </div>

    <section id="informasi">
      <div class="section-content">
        <h2>Informasi</h2>
        <p>Perpustakaan SDN Berat Wetan 1 didirikan sejak awal tahun 2005 sebagai pusat literasi modern yang mendukung ekosistem digital sekolah.</p>
        <div style="margin-top: 40px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
          <div>
            <span style="color: var(--accent-blue)">Jam Operasional</span><br>
            <small>Senin - Kamis: 06:00 - 14:00 WIB</small>
          </div>
          <div>
            <span style="color: var(--accent-pink)">Kontak</span><br>
            <small>berat.wetan1@gmail.com</small>
          </div>
          <div>
            <span style="color: var(--accent-purple)">NPSN</span><br>
            <small>20502893</small>
          </div>
        </div>
      </div>
    </section>

    <section id="visi">
      <div class="section-content">
        <h2>Visi & Misi</h2>
        <p><strong>Visi:</strong> Menjadi sekolah dasar yang unggul dalam pendidikan, karakter, dan kreativitas di era digital.</p>
        <ol>
          <li>Meningkatkan kualitas pendidikan berbasis teknologi.</li>
          <li>Mengembangkan karakter siswa yang berbudaya.</li>
          <li>Transformasi budaya literasi digital.</li>
        </ol>
      </div>
    </section>

    <section id="pustakawan">
      <div class="section-content" style="text-align: center;">
        <h2>Pustakawan</h2>
        <img src="{{ asset('web-perpus/img/pustakawan.png') }}" alt="Pustakawan">
        <h3>Lilik Nurhayati, S.Pd</h3>
        <p style="color: var(--accent-blue)">Expert Librarian</p>
      </div>
    </section>

    <section id="denah">
      <div class="section-content">
        <h2>Lokasi & Denah</h2>
        <div class="maps-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.081!2d112.3!3d-7.4!" 
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
          </iframe>
        </div>
        <img src="{{ asset('web-perpus/img/perpus.png') }}" alt="Denah">
      </div>
    </section>

    <footer>
      SDN BERAT WETAN 1 — DESIGNED FOR FUTURE LITERACY © 2026
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