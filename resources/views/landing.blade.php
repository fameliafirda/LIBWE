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
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    
    {{-- Spline Viewer --}}
    <script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-black: #1a1a1a;
            --bg-pastel-pink: #ffe5ec;
            --bg-pastel-blue: #e0f2fe;
            --bg-pastel-purple: #f3e8ff;
            --bg-pastel-yellow: #fef9c3;
            --accent-soft-pink: #fb7185;
            --accent-soft-blue: #60a5fa;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--white);
            color: var(--primary-black);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* --- Header Styles --- */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: all 0.4s ease;
        }

        header.scrolled {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 15px 5%;
            border-bottom: 2px solid var(--primary-black);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-container img {
            width: 40px;
            filter: drop-shadow(2px 2px 0px var(--primary-black));
        }

        .logo-container strong {
            font-family: 'Fredoka', sans-serif;
            font-size: 24px;
            letter-spacing: 1px;
        }

        nav {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        nav a {
            text-decoration: none;
            color: var(--primary-black);
            font-weight: 600;
            font-size: 15px;
            text-transform: uppercase;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--accent-soft-pink);
        }

        .login-btn {
            background: var(--primary-black);
            color: var(--white);
            padding: 10px 25px;
            border-radius: 50px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .login-btn:hover {
            transform: scale(1.05) rotate(-2deg);
        }

        /* --- Hero Section --- */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 0 8%;
            background: var(--bg-pastel-blue);
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            max-width: 600px;
            z-index: 2;
        }

        .hero h1 {
            font-family: 'Fredoka', sans-serif;
            font-size: clamp(40px, 6vw, 70px);
            line-height: 1;
            margin-bottom: 20px;
        }

        .highlight {
            color: var(--white);
            background: var(--primary-black);
            padding: 0 10px;
            display: inline-block;
            transform: rotate(-1deg);
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #444;
        }

        .hero button {
            background: var(--accent-soft-pink);
            color: var(--white);
            padding: 18px 40px;
            border-radius: 50px;
            border: 3px solid var(--primary-black);
            font-weight: 800;
            font-family: 'Fredoka', sans-serif;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 6px 6px 0px var(--primary-black);
            transition: all 0.2s;
        }

        .hero button:hover {
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0px var(--primary-black);
        }

        .book-animation {
            position: absolute;
            right: 5%;
            width: 45%;
            height: 70%;
            background-image: url('{{ asset("web-perpus/img/bukubaru.png") }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            animation: floatHero 5s ease-in-out infinite;
        }

        @keyframes floatHero {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        /* --- Section Styling (Like Image Cards) --- */
        section {
            padding: 100px 8%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .section-content {
            background: var(--white);
            border: 3px solid var(--primary-black);
            border-radius: 30px;
            padding: 60px;
            width: 100%;
            max-width: 1100px;
            box-shadow: 15px 15px 0px var(--primary-black);
            position: relative;
            transition: all 0.5s ease;
        }

        #informasi { background-color: var(--bg-pastel-pink); }
        #visi { background-color: var(--bg-pastel-purple); }
        #pustakawan { background-color: var(--bg-pastel-yellow); }
        #denah { background-color: var(--bg-pastel-blue); }

        h2 {
            font-family: 'Fredoka', sans-serif;
            font-size: 40px;
            margin-bottom: 40px;
            text-align: center;
            text-decoration: underline;
        }

        .section-bg {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            opacity: 0.2;
        }

        /* --- Content Detail --- */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 30px;
        }

        .pustakawan-card {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .pustakawan-card img {
            width: 180px;
            height: 180px;
            border-radius: 20px;
            border: 4px solid var(--primary-black);
            object-fit: cover;
        }

        /* --- Maps & Footer --- */
        .maps-container {
            width: 100%;
            height: 400px;
            border: 3px solid var(--primary-black);
            border-radius: 20px;
            overflow: hidden;
            margin: 30px 0;
        }

        footer {
            background: var(--primary-black);
            color: var(--white);
            padding: 40px;
            text-align: center;
            font-weight: 600;
        }

        /* --- Animations on Scroll --- */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.in-view {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Mobile Responsive --- */
        @media (max-width: 768px) {
            nav { display: none; }
            .hero { text-align: center; padding-top: 100px; }
            .hero-content { max-width: 100%; }
            .book-animation { display: none; }
            .section-content { padding: 30px; }
            .info-grid { grid-template-columns: 1fr; }
            .pustakawan-card { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>
<div id="app">
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
            <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">MASUK</button>
        </nav>
    </header>

    <div class="hero">
        <div class="hero-content">
            <h1>Halo! Selamat Datang di <span class="highlight">SDN Berat Wetan 1</span></h1>
            <p>Tempat petualangan seru dimulai dari setiap halaman buku yang kamu baca.</p>
            <button onclick="window.location.href='{{ route('katalog') }}'">CARI BUKU SERU 📚</button>
        </div>
        <div class="book-animation"></div>
    </div>

    <section id="informasi">
        <div class="section-content reveal" :class="{ 'in-view': isInfoInView }">
            <img class="section-bg" src="{{ asset('web-perpus/img/2.png') }}" alt="decor">
            <h2>Tentang Kami</h2>
            <p style="text-align: justify; font-size: 18px; line-height: 1.6;">
                Perpustakaan SDN Berat Wetan 1 adalah tempat paling asyik buat kamu yang suka membaca! Berdiri sejak 2005, kami punya banyak koleksi buku cerita, ensiklopedia keren, dan ruang baca yang nyaman banget.
            </p>
            
            <div class="info-grid">
                <div>
                    <h4 style="margin-bottom: 10px;">📍 Alamat</h4>
                    <p>Jl. KH. Abdul Fattah No.04, Kec. Gedeg, Kab. Mojokerto</p>
                </div>
                <div>
                    <h4 style="margin-bottom: 10px;">⏰ Jam Buka</h4>
                    <p>Senin - Kamis: 06:00 - 14:00 WIB<br>Jumat - Sabtu: 06:00 - 13:00 WIB</p>
                </div>
            </div>
        </div>
    </section>

    <section id="visi">
        <div class="section-content reveal" :class="{ 'in-view': isVisiInView }">
            <img class="section-bg" src="{{ asset('web-perpus/img/3.png') }}" alt="decor">
            <h2>Visi & Misi</h2>
            <div style="background: var(--white); border: 2px solid #000; padding: 20px; border-radius: 15px;">
                <p><strong>Visi:</strong> Menjadi sekolah yang unggul, kreatif, dan mencetak generasi yang pintar serta berakhlak mulia.</p>
            </div>
            <br>
            <ul style="padding-left: 20px; line-height: 2;">
                <li>Meningkatkan kualitas belajar mengajar.</li>
                <li>Menumbuhkan budaya rajin membaca (Literasi).</li>
                <li>Menciptakan lingkungan sekolah yang menyenangkan.</li>
            </ul>
        </div>
    </section>

    <section id="pustakawan">
        <div class="section-content reveal" :class="{ 'in-view': isPustakawanInView }">
            <h2>Pustakawan Kami</h2>
            <div class="pustakawan-card">
                <img src="{{ asset('web-perpus/img/Screenshot-2025-05-01-180615.png') }}" alt="Foto Pustakawan">
                <div class="pustakawan-info">
                    <h3 style="font-size: 28px;">Lilik Nurhayati, S.Pd</h3>
                    <p style="color: #666;">Pustakawan Utama</p>
                    <p style="margin-top: 10px;"><i class="fa fa-envelope"></i> lilik.nur246@guruku.belajar.id</p>
                </div>
            </div>
        </div>
    </section>

    <section id="denah">
        <div class="section-content reveal" :class="{ 'in-view': isDenahInView }">
            <h2>Lokasi & Denah</h2>
            <div class="maps-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.132515518058!2d112.40273567526795!3d-7.560518392481143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78552b0b1456f7%3A0x8b4ac87fc7a8450!2sSDN%20Berat%20Wetan%201!5e0!3m2!1sid!2sid!4v1714550000000!5m2!1sid!2sid" allowfullscreen="" loading="lazy"></iframe>
            </div>
            <img src="{{ asset('web-perpus/img/meja-pustakawan.png') }}" alt="Denah" style="width: 100%; border-radius: 15px; border: 2px solid #000;">
        </div>
    </section>

    <footer>
        <p>SDN BERAT WETAN 1 © Famella Firda Levia</p>
    </footer>
</div>

<script>
    const { createApp, ref, onMounted, onUnmounted } = Vue;
    
    createApp({
        setup() {
            const isScrolled = ref(false);
            const isInfoInView = ref(false);
            const isVisiInView = ref(false);
            const isPustakawanInView = ref(false);
            const isDenahInView = ref(false);

            const handleScroll = () => {
                isScrolled.value = window.scrollY > 50;
                
                const triggerPoint = window.innerHeight * 0.8;
                
                const sections = [
                    { id: 'informasi', ref: isInfoInView },
                    { id: 'visi', ref: isVisiInView },
                    { id: 'pustakawan', ref: isPustakawanInView },
                    { id: 'denah', ref: isDenahInView }
                ];

                sections.forEach(sec => {
                    const el = document.getElementById(sec.id);
                    if (el) {
                        const rect = el.getBoundingClientRect();
                        if (rect.top < triggerPoint) {
                            sec.ref.value = true;
                        }
                    }
                });
            };

            onMounted(() => {
                window.addEventListener('scroll', handleScroll);
                handleScroll();
            });

            return {
                isScrolled,
                isInfoInView, isVisiInView, isPustakawanInView, isDenahInView
            };
        }
    }).mount('#app');
</script>
</body>
</html>