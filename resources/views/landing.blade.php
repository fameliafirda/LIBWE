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
    
    {{-- Google Fonts sesuai desain Katalog --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Unbounded:wght@500;700;900&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ============================================================
           1. COLOR PALETTE & RESET (SAMA PERSIS DENGAN KATALOG)
           ============================================================ */
        :root {
            --color-black: #0a0a0a;
            --color-1: #cdb4db;
            --color-2: #ffc8dd;
            --color-3: #ffafcc;
            --color-4: #bde0fe;
            --color-5: #a2d2ff;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
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
            background-color: var(--color-black);
            color: #ffffff;
            scroll-behavior: smooth;
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
            color: var(--color-black);
        }

        /* Floating Background Glow */
        .bg-glow {
            position: fixed;
            width: 50vw; height: 50vw;
            border-radius: 50%;
            filter: blur(150px);
            z-index: 0;
            opacity: 0.15;
            pointer-events: none;
        }

        /* ============================================================
           2. NAVIGASI (STICKY GLASSMORPHISM)
           =========================================================== */
        .libwe-nav {
            position: fixed;
            top: 0; left: 0; width: 100%;
            padding: 25px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(10, 10, 10, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            z-index: 1000;
            border-bottom: 1px solid var(--glass-border);
            transition: all 0.4s ease;
        }

        .libwe-nav.scrolled {
            padding: 15px 60px;
            background: rgba(10, 10, 10, 0.9);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-container img {
            width: 40px;
            height: auto;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.2));
        }

        .brand-libwe {
            font-family: 'Unbounded', sans-serif;
            font-size: 1.8rem;
            font-weight: 900;
            background: linear-gradient(to right, var(--color-4), var(--color-3));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        .nav-links { 
            display: flex; 
            gap: 40px; 
            align-items: center;
        }

        .nav-links a { 
            text-decoration: none; 
            color: rgba(255,255,255,0.7); 
            font-weight: 600; 
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .nav-links a:hover { 
            color: var(--color-2); 
            text-shadow: 0 0 10px var(--color-2); 
        }

        .btn-login {
            background: var(--color-5);
            color: var(--color-black) !important;
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: 800 !important;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-login:hover {
            transform: scale(1.05);
            background: var(--color-4);
            box-shadow: 0 0 15px var(--color-4);
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            width: 30px;
            height: 20px;
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
           3. HERO SECTION & RUNNING TEXT
           =========================================================== */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex-direction: column;
        }

        .marquee-container {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .marquee-content {
            display: flex;
            animation: scrollText 40s linear infinite;
        }

        .marquee-item {
            font-family: 'Syne', sans-serif;
            font-size: 13vw;
            font-weight: 800;
            white-space: nowrap;
            color: transparent;
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.08);
            text-transform: uppercase;
            padding-right: 50px;
        }

        @keyframes scrollText {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .hero-img-container {
            position: relative;
            z-index: 5;
            width: 380px;
            margin-bottom: 20px;
            transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            animation: floatImg 6s ease-in-out infinite;
        }

        .hero-img-container img {
            width: 100%;
            filter: drop-shadow(0 0 50px rgba(189, 224, 254, 0.3));
        }

        @keyframes floatImg {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-25px); }
        }

        .hero-content {
            position: relative;
            text-align: center;
            z-index: 10;
            margin-top: 20px;
            max-width: 800px;
            padding: 0 20px;
        }

        .hero-content h1 { 
            font-family: 'Unbounded', sans-serif; 
            font-size: clamp(2rem, 4vw, 3.5rem); 
            color: #fff; 
            margin: 0; 
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.7);
            margin: 15px 0 30px;
        }

        .btn-hero {
            display: inline-block;
            background: linear-gradient(45deg, var(--color-4), var(--color-3));
            color: var(--color-black);
            padding: 16px 40px;
            border-radius: 30px;
            font-family: 'Unbounded', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            text-decoration: none;
            transition: 0.4s;
            box-shadow: 0 10px 20px rgba(255, 175, 204, 0.2);
            border: none;
            cursor: pointer;
        }

        .btn-hero:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 175, 204, 0.4);
            color: var(--color-black);
        }

        /* ============================================================
           4. SECTIONS & GLASSMORPHISM LAYOUT
           =========================================================== */
        section {
            padding: 120px 60px;
            position: relative;
            z-index: 2;
            scroll-margin-top: 80px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-subtitle {
            letter-spacing: 5px; 
            color: var(--color-3); 
            font-weight: 800; 
            font-size: 0.8rem; 
            text-transform: uppercase;
        }

        .section-title {
            font-family: 'Unbounded', sans-serif; 
            font-size: clamp(2rem, 3vw, 2.8rem); 
            margin-top: 10px;
        }

        .glass-card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            padding: 50px;
            border-radius: 40px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            max-width: 1000px;
            margin: 0 auto;
            transition: transform 0.4s ease, border-color 0.4s ease;
        }

        .glass-card:hover {
            border-color: rgba(255, 175, 204, 0.3);
            transform: translateY(-5px);
        }

        .text-content p {
            line-height: 1.8;
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 20px;
        }

        .text-content strong {
            color: var(--color-4);
            font-family: 'Unbounded', sans-serif;
            font-size: 1.1rem;
        }

        ul, ol {
            padding-left: 20px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 20px;
        }

        ul li {
            margin-bottom: 15px;
        }
        
        ol li {
            margin-bottom: 10px;
            font-weight: 500;
        }

        /* Style Khusus Pustakawan */
        .pustakawan-card {
            text-align: center;
        }

        .pustakawan-card img {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 30px;
            border: 4px solid var(--color-3);
            box-shadow: 0 0 30px rgba(255, 175, 204, 0.4);
            transition: 0.5s ease;
        }

        .pustakawan-card img:hover {
            transform: scale(1.05);
            border-color: var(--color-4);
            box-shadow: 0 0 40px rgba(189, 224, 254, 0.5);
        }

        .pustakawan-card p {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Style Khusus Denah & Peta */
        .maps-wrapper {
            border-radius: 25px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            margin-bottom: 30px;
        }

        iframe {
            width: 100%;
            height: 400px;
            filter: invert(90%) hue-rotate(180deg) contrast(85%); /* Efek dark mode map */
            display: block;
        }

        .alamat-info {
            background: rgba(0, 0, 0, 0.3);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 40px;
            border: 1px solid var(--glass-border);
        }

        .alamat-info p {
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.8);
        }

        .alamat-info i {
            color: var(--color-3);
            margin-right: 10px;
        }

        .denah-img {
            width: 100%;
            border-radius: 25px;
            border: 1px solid var(--glass-border);
            opacity: 0.9;
            transition: 0.4s;
        }

        .denah-img:hover {
            opacity: 1;
            border-color: var(--color-4);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 40px;
            border-top: 1px solid var(--glass-border);
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
            font-weight: 600;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 10;
        }

        /* ============================================================
           5. RESPONSIVE DESIGN (MOBILE ADAPTATION)
           =========================================================== */
        @media (max-width: 992px) {
            .libwe-nav { padding: 20px 30px; }
            .libwe-nav.scrolled { padding: 15px 30px; }
            section { padding: 80px 30px; }
            .hero-img-container { width: 300px; }
        }

        @media (max-width: 768px) {
            .hamburger { display: flex; }
            
            .nav-links {
                position: fixed;
                top: 0; left: -100%;
                width: 100%; height: 100vh;
                background: rgba(10, 10, 10, 0.95);
                backdrop-filter: blur(20px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                transition: 0.5s ease;
            }

            .nav-links.active { left: 0; }
            .nav-links a { font-size: 1.5rem; }
            
            .glass-card { padding: 30px; border-radius: 30px; }
            .hero-img-container { width: 250px; }
            .text-content p, ul, ol { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="bg-glow" style="top: -10%; right: -5%; background: var(--color-1);"></div>
        <div class="bg-glow" style="bottom: 10%; left: -5%; background: var(--color-4);"></div>
        <div class="bg-glow" style="top: 40%; left: -10%; background: var(--color-3);"></div>

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
                <a href="#denah" @click="closeMenu">Denah & Lokasi</a>
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
                <h1>Selamat datang di <br><span style="color: var(--color-3)">Perpustakaan Online</span></h1>
                <p>SDN Berat Wetan 1 — Membangun generasi cerdas dan berbudaya literasi.</p>
                <button class="btn-hero" onclick="window.location.href='{{ route('katalog') }}'">TELUSURI BUKU</button>
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
                
                <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 30px 0;">
                
                <p><i class="fas fa-map-marker-alt" style="color: var(--color-4); margin-right: 10px;"></i> <strong>Alamat:</strong> Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur</p>
                <p><i class="fas fa-envelope" style="color: var(--color-3); margin-right: 10px;"></i> <strong>Email:</strong> berat.wetan1@gmail.com</p>
                <p><i class="fas fa-id-card" style="color: var(--color-1); margin-right: 10px;"></i> <strong>NPSN:</strong> 20502893</p>
                <p><i class="fas fa-clock" style="color: var(--color-5); margin-right: 10px;"></i> <strong>Jam Operasional:</strong></p>
                <ul style="list-style: none; padding-left: 35px; margin-top: 0;">
                    <li>Senin - Kamis: 06:00 - 14:00 WIB</li>
                    <li>Jumat - Sabtu: 06:00 - 13:00 WIB</li>
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
                <p><strong style="color: var(--color-1); font-family: 'Unbounded', sans-serif;">Jabatan:</strong> Pustakawan</p>
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
                    <a href="https://maps.google.com/?q=SDN+Berat+Wetan+1" target="_blank" class="btn-hero" style="padding: 10px 25px; font-size: 0.8rem; margin-top: 15px;">
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
                    document.body.style.overflow = isMenuActive.value ? 'hidden' : '';
                };
                
                const closeMenu = () => {
                    isMenuActive.value = false;
                    document.body.style.overflow = '';
                };
                
                const handleScroll = () => {
                    isScrolled.value = window.scrollY > 50;
                };
                
                onMounted(() => {
                    window.addEventListener('scroll', handleScroll);
                    handleScroll(); // Trigger check on load
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