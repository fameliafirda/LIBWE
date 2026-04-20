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
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
  
  {{-- Spline Viewer --}}
  <script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>
  
  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --primary-color: #000000;
      --secondary-color: #ffffff;
      --accent-color: #ff5ea3; /* Pink */
      --accent-color2: #5eb0ff; /* Blue */
      --bg-color: #F4F0EC; /* Warna cream soft seperti di gambar referensi */
      --text-color: #2b2b2b;
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

    /* Background Pattern (Titik-titik halus ala scrapbook) */
    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background-image: radial-gradient(#d5d1cc 2px, transparent 2px);
      background-size: 30px 30px;
      z-index: -3;
      opacity: 0.5;
    }

    /* Spline Animation */
    .spline-container {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      z-index: -2;
      opacity: 0.15; /* Dibuat transparan agar tidak berat & menutupi konten */
      pointer-events: none;
    }

    /* Floating Books - Dibuat lebih ringan */
    .floating-books {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      pointer-events: none;
      z-index: -1;
      overflow: hidden;
    }

    .book {
      position: absolute;
      width: 80px;
      height: 110px;
      background-size: cover;
      background-position: center;
      opacity: 0.3;
      border-radius: 5px;
      box-shadow: 5px 5px 10px rgba(0,0,0,0.1);
      animation: float 15s infinite ease-in-out;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(5deg); }
    }

    /* Floating Navbar */
    header {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: auto;
      max-width: 95%;
      background-color: var(--secondary-color);
      border-radius: 50px;
      padding: 10px 25px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
      z-index: 1000;
      transition: all 0.3s ease;
      border: 3px solid var(--primary-color);
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
      width: 40px;
      height: auto;
      transition: transform 0.3s ease;
    }

    .logo-container:hover img {
      transform: rotate(-15deg);
    }

    .logo-container strong {
      font-size: 20px;
      font-family: 'Fredoka One', cursive;
      color: var(--primary-color);
      letter-spacing: 1px;
    }

    .nav-container {
      display: flex;
      align-items: center;
    }

    nav { display: flex; align-items: center; }

    nav a {
      text-decoration: none;
      color: var(--text-color);
      font-weight: 600;
      transition: all 0.3s ease;
      padding: 8px 15px;
      font-size: 15px;
      border-radius: 20px;
    }

    nav a:hover {
      background-color: var(--accent-color2);
      color: var(--secondary-color);
    }

    .login-btn {
      background-color: var(--accent-color);
      color: var(--secondary-color);
      border: 2px solid var(--primary-color);
      padding: 8px 25px;
      border-radius: 30px;
      font-weight: 700;
      transition: all 0.3s ease;
      margin-left: 15px;
      font-size: 15px;
      cursor: pointer;
      box-shadow: 3px 3px 0px var(--primary-color);
    }

    .login-btn:hover {
      transform: translate(-2px, -2px);
      box-shadow: 5px 5px 0px var(--primary-color);
    }

    /* Mobile Login Button */
    .mobile-login-btn {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: var(--accent-color);
      color: var(--secondary-color);
      border: 2px solid var(--primary-color);
      padding: 12px 24px;
      border-radius: 50px;
      font-weight: 700;
      cursor: pointer;
      z-index: 1000;
      box-shadow: 4px 4px 0px var(--primary-color);
      gap: 10px;
    }

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
      height: 3px;
      background-color: var(--primary-color);
      border-radius: 3px;
      transition: all 0.3s ease;
    }

    .nav-menu { display: flex; gap: 5px; }

    /* Layout Style Scrapbook */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 120px 20px 50px;
      position: relative;
      z-index: 2;
    }

    .scrap-card {
      background: var(--secondary-color);
      border-radius: 40px;
      padding: 40px;
      box-shadow: 8px 8px 0px rgba(0,0,0,0.05);
      position: relative;
      margin-bottom: 30px;
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease;
    }

    .scrap-card.in-view {
      opacity: 1;
      transform: translateY(0);
    }

    .card-pink { background-color: var(--accent-color); color: var(--secondary-color); }
    .card-blue { background-color: var(--accent-color2); color: var(--secondary-color); }
    
    .card-pink h2, .card-blue h2, 
    .card-pink strong, .card-blue strong {
      color: var(--secondary-color) !important;
    }

    /* Typograpy */
    h1, h2 {
      font-family: 'Fredoka One', cursive;
      letter-spacing: 1px;
    }

    h2 {
      font-size: clamp(24px, 4vw, 36px);
      margin-bottom: 25px;
      color: var(--primary-color);
      display: inline-block;
      position: relative;
    }

    /* Hero Section */
    .hero-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      align-items: center;
      margin-bottom: 50px;
    }

    .hero-text {
      background-color: var(--accent-color);
      color: white;
      border-radius: 40px 100px 40px 40px; /* Bentuk asimetris ala stiker */
      padding: 50px;
      box-shadow: 10px 10px 0px rgba(0,0,0,0.1);
    }

    .hero-text h1 {
      font-size: clamp(30px, 4vw, 50px);
      line-height: 1.2;
      margin-bottom: 15px;
    }

    .hero-text p {
      font-size: 18px;
      margin-bottom: 30px;
    }

    .hero-text button {
      background-color: var(--secondary-color);
      color: var(--primary-color);
      border: 2px solid var(--primary-color);
      padding: 15px 30px;
      border-radius: 30px;
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      box-shadow: 4px 4px 0px var(--primary-color);
      transition: transform 0.2s;
    }

    .hero-text button:hover {
      transform: translateY(-3px);
    }

    .hero-image {
      position: relative;
      height: 100%;
      min-height: 400px;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: var(--secondary-color);
      border-radius: 100px 40px 100px 40px;
      box-shadow: 8px 8px 0px rgba(0,0,0,0.05);
    }

    .book-animation {
      width: 80%;
      height: 80%;
      background-image: url('{{ asset("web-perpus/img/bukubaru.png") }}');
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      animation: float 6s ease-in-out infinite;
    }

    /* Grid Khusus Konten */
    .bento-grid {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 30px;
    }

    .col-12 { grid-column: span 12; }
    .col-8 { grid-column: span 8; }
    .col-4 { grid-column: span 4; }
    .col-6 { grid-column: span 6; }

    /* Teks dalam Card */
    .scrap-card p, .scrap-card ul, .scrap-card ol {
      font-size: 16px;
      line-height: 1.8;
      margin-bottom: 15px;
      text-align: justify;
    }

    .scrap-card ul, .scrap-card ol {
      padding-left: 20px;
    }

    /* Pustakawan Card (Stiker Look) */
    .pustakawan-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      border-radius: 50px;
    }

    .pustakawan-img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 6px solid var(--secondary-color);
      box-shadow: 5px 5px 0px rgba(0,0,0,0.2);
      margin-bottom: 20px;
      transform: rotate(-3deg);
    }

    .badge {
      background-color: var(--secondary-color);
      color: var(--accent-color2);
      padding: 5px 15px;
      border-radius: 20px;
      font-weight: 700;
      margin-bottom: 10px;
      display: inline-block;
    }

    /* Maps & Lokasi */
    .maps-container iframe {
      width: 100%;
      height: 350px;
      border-radius: 30px;
      border: 3px solid var(--secondary-color);
      box-shadow: 5px 5px 0px rgba(0,0,0,0.1);
    }

    .denah-img {
      width: 100%;
      border-radius: 30px;
      margin-top: 20px;
      border: 3px solid var(--secondary-color);
    }

    .btn-direksi {
      display: inline-block;
      margin-top: 15px;
      padding: 12px 25px;
      background-color: var(--accent-color);
      color: white;
      text-decoration: none;
      border-radius: 30px;
      font-weight: 700;
      border: 2px solid var(--primary-color);
      box-shadow: 3px 3px 0px var(--primary-color);
      transition: transform 0.2s;
    }

    .btn-direksi:hover {
      transform: translateY(-2px);
    }

    footer {
      text-align: center;
      padding: 30px;
      color: var(--text-color);
      font-weight: 600;
      margin-top: 50px;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .hero-grid { grid-template-columns: 1fr; }
      .hero-text { border-radius: 40px; }
      .hero-image { border-radius: 40px; min-height: 300px; padding: 30px;}
      .col-8, .col-4, .col-6 { grid-column: span 12; }
    }

    @media (max-width: 768px) {
      .login-btn { display: none; }
      .mobile-login-btn { display: flex; }
      .hamburger { display: flex; }
      
      .nav-menu {
        position: fixed;
        top: 0; left: -100%; width: 100%; height: 100vh;
        background-color: var(--bg-color);
        flex-direction: column; align-items: center; justify-content: center;
        gap: 20px; transition: left 0.3s ease;
      }
      
      .nav-menu.active { left: 0; }
      nav a { font-size: 20px; background: white; width: 80%; text-align: center; padding: 15px; border-radius: 20px; box-shadow: 2px 2px 0px rgba(0,0,0,0.1);}
      
      .scrap-card { padding: 25px; border-radius: 30px; }
      h2 { font-size: 24px; }
    }
  </style>
</head>
<body>
  <div id="app">
    <div class="spline-container">
      <spline-viewer url="https://prod.spline.design/PBQQBw8bfXDhBo7w/scene.splinecode" events-target="global"></spline-viewer>
    </div>
    
    <div class="floating-books" id="floatingBooks"></div>

    <header>
      <div class="logo-nav">
        <div class="logo-container">
          <img src="{{ asset('web-perpus/img/Open_Book_Vector_Illustration_Flat_Logo_Stock_Vector_-_Illustration_of_flat__minimal__187678563__3_-removebg-preview - Copy.png') }}" alt="logo Perpustakaan">
          <strong>LIBWE</strong>
        </div>
        
        <div class="nav-container">
          <nav>
            <div class="hamburger" :class="{active: isMenuActive}" @click="toggleMenu">
              <span></span><span></span><span></span>
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

    <div class="container">
      <div class="hero-grid">
        <div class="hero-text">
          <h1>Selamat datang di Perpustakaan Online <br>SDN Berat Wetan 1</h1>
          <p>Membangun generasi cerdas dan berbudaya literasi.</p>
          <button onclick="window.location.href='{{ route('katalog') }}'">TELUSURI BUKU</button>
        </div>
        <div class="hero-image">
          <div class="book-animation"></div>
        </div>
      </div>

      <div class="bento-grid">
        
        <section id="informasi" class="col-8 scrap-card" :class="{ 'in-view': isInfoInView }" ref="informasiRef">
          <h2>Informasi</h2>
          <p>Perpustakaan SDN Berat Wetan 1 didirikan sebagai bagian dari komitmen sekolah untuk meningkatkan minat baca dan literasi siswa sejak dini. Berdiri sejak awal tahun 2005, perpustakaan ini awalnya hanya memiliki koleksi buku bacaan dasar dan beberapa rak sederhana yang ditempatkan di salah satu sudut ruang kelas. Namun, seiring waktu dan dukungan dari pihak sekolah, guru, serta orang tua murid, perpustakaan terus mengalami perkembangan baik dari segi fasilitas maupun jumlah koleksi buku.</p>
          <p>Dalam beberapa tahun terakhir, perpustakaan SDN Berat Wetan 1 telah mengalami renovasi dan penataan ulang, menjadikannya ruang yang lebih nyaman, bersih, dan menyenangkan untuk belajar. Kini, perpustakaan tidak hanya menyediakan buku bacaan fiksi dan non-fiksi, tetapi juga buku referensi, ensiklopedia anak, kamus, dan bahkan koleksi bergambar yang mendukung proses pembelajaran tematik di sekolah dasar. Dengan tambahan sentuhan digital dan penataan yang lebih modern, perpustakaan menjadi pusat aktivitas literasi yang aktif di lingkungan sekolah.</p>
        </section>

        <div class="col-4 scrap-card card-blue" :class="{ 'in-view': isInfoInView }" style="transition-delay: 0.2s;">
          <h2>Kontak</h2>
          <ul style="list-style: none; padding: 0;">
            <li><strong>Alamat:</strong> Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur</li>
            <li><strong>Email:</strong> berat.wetan1@gmail.com</li>
            <li><strong>NPSN:</strong> 20502893</li>
          </ul>
          <h2 style="margin-top: 20px; font-size: 24px;">Jam Buka</h2>
          <ul style="list-style: none; padding: 0;">
            <li>Senin - Kamis:<br>06:00 - 14:00 WIB</li>
            <li>Jumat - Sabtu:<br>06:00 - 13:00 WIB</li>
          </ul>
        </div>

        <div class="col-12 scrap-card" :class="{ 'in-view': isInfoInView }">
          <h2>Tujuan</h2>
          <p>Tujuan utama dari perpustakaan SDN Berat Wetan 1 adalah mendukung proses pembelajaran di sekolah dengan menyediakan sumber informasi yang lengkap dan mudah diakses oleh seluruh siswa dan guru. Perpustakaan juga bertujuan untuk:</p>
          <ul>
            <li>Menumbuhkan minat baca siswa sejak usia dini melalui penyediaan bacaan yang menarik dan bervariasi.</li>
            <li>Meningkatkan budaya literasi di lingkungan sekolah dengan mengadakan kegiatan-kegiatan seperti pojok baca, lomba membaca, dan mendongeng.</li>
            <li>Mendukung pembelajaran aktif dan mandiri, di mana siswa dapat mencari informasi tambahan secara mandiri untuk menunjang tugas dan pelajaran.</li>
            <li>Menjadi ruang edukatif yang menyenangkan, di mana siswa merasa nyaman untuk membaca, belajar, dan mengeksplorasi pengetahuan.</li>
            <li>Dengan adanya perpustakaan ini, diharapkan SDN Berat Wetan 1 tidak hanya menjadi tempat belajar akademik, tetapi juga tempat untuk membangun karakter, imajinasi, dan kecintaan terhadap ilmu pengetahuan.</li>
          </ul>
        </div>

        <section id="visi" class="col-8 scrap-card card-pink" :class="{ 'in-view': isVisiInView }" ref="visiRef">
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
        </section>

        <section id="pustakawan" class="col-4 scrap-card card-blue pustakawan-card" :class="{ 'in-view': isPustakawanInView }" ref="pustakawanRef" style="transition-delay: 0.2s;">
          <h2>Pustakawan</h2>
          <img class="pustakawan-img" src="{{ asset('web-perpus/img/Screenshot-2025-05-01-180615.png') }}" alt="Foto Pustakawan">
          <div class="badge">Pustakawan</div>
          <h3 style="font-family: 'Fredoka One', cursive; margin-bottom: 10px;">Lilik Nurhayati, S.Pd</h3>
          <p style="font-size: 14px; margin-bottom: 0;">lilik.nur246@guruku.belajar.id</p>
        </section>

        <section id="denah" class="col-12 scrap-card" :class="{ 'in-view': isDenahInView }" ref="denahRef">
          <h2>Denah & Lokasi Perpustakaan</h2>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div>
              <p><strong>Alamat Lengkap:</strong><br>Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur 61351</p>
              <p><strong>Kontak:</strong> (0321) 123456</p>
              <div class="maps-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.132515518058!2d112.40273567526795!3d-7.560518392481143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78552b0b1456f7%3A0x8b4ac87fc7a8450!2sSDN%20Berat%20Wetan%201!5e0!3m2!1sid!2sid!4v1714550000000!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
              <a href="https://maps.google.com/?q=SDN+Berat+Wetan+1" target="_blank" class="btn-direksi">
                Buka di Google Maps
              </a>
            </div>
            <div>
              <p><strong>Layout Denah:</strong></p>
              <img src="{{ asset('web-perpus/img/meja-pustakawan.png') }}" alt="Denah Perpustakaan" class="denah-img">
            </div>
          </div>
        </section>

      </div>
    </div>

    <footer>
      SDN BERAT WETAN 1 © Famella Firda Levia
    </footer>
  </div>

  <script>
    const { createApp, ref, onMounted, onUnmounted } = Vue;
    
    createApp({
      setup() {
        const isMenuActive = ref(false);
        const isInfoInView = ref(false);
        const isVisiInView = ref(false);
        const isPustakawanInView = ref(false);
        const isDenahInView = ref(false);
        
        const toggleMenu = () => {
          isMenuActive.value = !isMenuActive.value;
          document.body.style.overflow = isMenuActive.value ? 'hidden' : '';
        };
        
        const closeMenu = () => {
          isMenuActive.value = false;
          document.body.style.overflow = '';
        };
        
        const handleScroll = () => {
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
          // Dikurangi jadi 6 buku agar lebih ringan di browser
          for (let i = 0; i < 6; i++) {
            const book = document.createElement('div');
            book.className = 'book';
            book.style.backgroundImage = `url(${bookImages[Math.floor(Math.random() * bookImages.length)]})`;
            book.style.left = `${Math.random() * 90}%`;
            book.style.top = `${Math.random() * 90}%`;
            book.style.animationDuration = `${15 + Math.random() * 10}s`;
            container.appendChild(book);
          }
        };
        
        onMounted(() => {
          window.addEventListener('scroll', handleScroll);
          window.addEventListener('scroll', preventHorizontalScroll);
          createFloatingBooks();
          setTimeout(handleScroll, 100); // Trigger initial check
        });
        
        onUnmounted(() => {
          window.removeEventListener('scroll', handleScroll);
          window.removeEventListener('scroll', preventHorizontalScroll);
        });
        
        return {
          isMenuActive, isInfoInView, isVisiInView, isPustakawanInView, isDenahInView,
          toggleMenu, closeMenu
        };
      }
    }).mount('#app');
  </script>
</body>
</html>