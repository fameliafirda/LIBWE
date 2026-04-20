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
           1. COLOR PALETTE & RESET (SOFT Y2K AESTHETIC)
           ============================================================ */
        :root {
            --bg-dark: #050505; /* Deep Obsidian */
            --card-bg: rgba(20, 20, 20, 0.6); /* Translucent dark */
            --lavender: #cdb4db; /* Soft Lavender */
            --soft-pink: #ffc8dd; /* Soft Pink */
            --cotton-candy: #ffafcc; /* Cotton Candy Pink */
            --sky-blue: #bde0fe; /* Soft Sky Blue */
            --baby-blue: #a2d2ff; /* Baby Blue */
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-subtle: rgba(255, 255, 255, 0.7);
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
            background-color: var(--bg-dark);
            color: #ffffff;
            scroll-behavior: smooth;
        }

        /* Y2K Grid Background */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
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
            background: var(--soft-pink);
            color: var(--bg-dark);
        }

        strong {
            color: var(--soft-pink);
        }

        /* Floating Background Glow (Soft & Pulsing) */
        .bg-glow {
            position: fixed;
            width: 50vw; height: 50vw;
            border-radius: 50%;
            filter: blur(150px);
            z-index: -1;
            opacity: 0.15;
            pointer-events: none;
            animation: pulseGlow 12s infinite alternate ease-in-out;
        }

        .bg-glow:nth-child(1) { animation-delay: 0s; }
        .bg-glow:nth-child(2) { animation-delay: -4s; }
        .bg-glow:nth-child(3) { animation-delay: -8s; }

        @keyframes pulseGlow {
            0% { transform: scale(1) translate(0, 0); opacity: 0.1; }
            50% { transform: scale(1.1) translate(20px, -20px); opacity: 0.2; }
            100% { transform: scale(0.95) translate(-20px, 20px); opacity: 0.1; }
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
            background: rgba(10, 10, 10, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            z-index: 1000;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            animation: slideDownNav 1s ease-out forwards;
        }

        @keyframes slideDownNav {
            0% { transform: translate(-50%, -100px); opacity: 0; }
            100% { transform: translate(-50%, 0); opacity: 1; }
        }

        .libwe-nav.scrolled {
            top: 10px;
            background: rgba(5, 5, 5, 0.9);
            border-color: rgba(205, 180, 219, 0.3); /* Lavender subtle */
            box-shadow: 0 10px 25px rgba(205, 180, 219, 0.1);
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-container img {
            width: 30px;
            height: auto;
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5));
            transition: 0.5s ease;
        }

        .brand-container:hover img {
            transform: rotate(15deg) scale(1.1);
        }

        .brand-libwe {
            font-family: 'Unbounded', sans-serif;
            font-size: 1.4rem;
            font-weight: 900;
            background: linear-gradient(to right, var(--sky-blue), var(--cotton-candy));
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
            color: rgba(255,255,255,0.7); 
            font-weight: 600; 
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px; left: 0;
            width: 0; height: 2px;
            background: var(--soft-pink);
            transition: 0.3s;
        }

        .nav-links a:hover { 
            color: #fff;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }

        .btn-login {
            background: var(--lavender);
            color: var(--bg-dark) !important;
            padding: 8px 22px;
            border-radius: 30px;
            border: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800 !important;
            font-size: 0.8rem;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(205, 180, 219, 0.3);
        }

        .btn-login:hover {
            box-shadow: 0 5px 20px rgba(205, 180, 219, 0.5);
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
            background-color: var(--soft-pink);
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
            background: radial-gradient(circle at center, rgba(162, 210, 255, 0.05) 0%, #050505 70%);
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
            -webkit-text-stroke: 2px rgba(205, 180, 219, 0.15); /* Soft Lavender line */
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
            filter: drop-shadow(0 0 30px rgba(189, 224, 254, 0.2));
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
            color: #fff; 
            margin: 0; 
            line-height: 1.2;
            text-shadow: 0 5px 15px rgba(0,0,0,0.8);
            opacity: 0;
            animation: slideUpFade 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.3s;
        }

        .hero-content h1 span {
            color: var(--sky-blue);
            text-shadow: 0 0 20px rgba(189, 224, 254, 0.3);
        }

        .hero-content p {
            font-size: clamp(0.85rem, 1.5vw, 1.1rem);
            color: var(--text-subtle);
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
            background: var(--lavender);
            color: var(--bg-dark);
            padding: 15px 40px;
            border-radius: 50px;
            font-family: 'Unbounded', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            text-decoration: none;
            text-transform: uppercase;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid var(--lavender);
            box-shadow: 0 5px 15px rgba(205, 180, 219, 0.3);
            cursor: pointer;
        }

        .btn-hero:hover {
            background: transparent;
            color: var(--lavender);
            box-shadow: 0 5px 25px rgba(205, 180, 219, 0.5);
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
            color: var(--sky-blue); 
            font-weight: 800; 
            font-size: 0.8rem; 
            text-transform: uppercase;
            background: rgba(189, 224, 254, 0.05);
            padding: 6px 15px;
            border-radius: 20px;
            border: 1px solid rgba(189, 224, 254, 0.2);
        }

        .section-title {
            font-family: 'Unbounded', sans-serif; 
            font-size: clamp(1.8rem, 3.5vw, 2.5rem); 
            margin-top: 20px;
            color: #fff;
            text-shadow: 0 0 10px rgba(255,255,255,0.2);
        }

        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            padding: 50px;
            border-radius: 30px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            width: 100%;
            max-width: 900px;
            transition: 0.5s ease;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.8);
            border-color: var(--sky-blue);
        }

        .text-content p {
            line-height: 1.8;
            font-size: 1rem;
            color: var(--text-subtle);
            margin-bottom: 20px;
        }

        ul, ol {
            padding-left: 20px;
            line-height: 1.8;
            color: var(--text-subtle);
            margin-bottom: 20px;
        }

        ul li { margin-bottom: 12px; }
        ol li { margin-bottom: 12px; font-weight: 500; }

        /* Style Khusus Pustakawan */
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
            border: 4px solid var(--soft-pink);
            box-shadow: 0 0 20px rgba(255, 175, 204, 0.3);
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .pustakawan-card img:hover {
            transform: scale(1.1) rotate(5deg);
            border-color: var(--sky-blue);
            box-shadow: 0 0 30px rgba(189, 224, 254, 0.4);
        }

        .pustakawan-card p {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        /* Style Khusus Denah & Peta */
        .maps-wrapper {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            margin-bottom: 30px;
        }

        iframe {
            width: 100%;
            height: 350px;
            filter: grayscale(80%) invert(100%) contrast(90%);
            display: block;
        }

        .alamat-info {
            background: rgba(0, 0, 0, 0.4);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 35px;
            border: 1px solid var(--glass-border);
        }

        .alamat-info p {
            margin-bottom: 10px;
            color: var(--text-subtle);
            font-size: 0.95rem;
        }

        .alamat-info i {
            color: var(--sky-blue);
            margin-right: 8px;
        }

        .btn-direksi {
            display: inline-block;
            background: transparent;
            color: var(--lavender);
            border: 2px solid var(--lavender);
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.3s;
            margin-top: 15px;
        }
        
        .btn-direksi:hover {
            background: var(--lavender);
            color: var(--bg-dark);
            box-shadow: 0 0 15px rgba(205, 180, 219, 0.4);
        }

        .denah-img {
            width: 100%;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            opacity: 0.85;
            transition: 0.4s;
        }

        .denah-img:hover {
            opacity: 1;
            border-color: var(--sky-blue);
        }

        /* ============================================================
           5. SECTION REKOMENDASI (FIXED & aesthetic)
           =========================================================== */
        #top10 { 
            padding: 80px 20px; 
            position: relative; 
            z-index: 2; 
            background-color: #000; /* Distinct background from hero */
            border-top: 2px dashed rgba(255, 255, 255, 0.05);
            border-bottom: 2px dashed rgba(255, 255, 255, 0.05);
        }

        .slider-wrapper {
            position: relative;
            width: 100%;
            padding: 0 60px;
            margin-top: 40px;
        }

        .track-container {
            display: flex;
            gap: 25px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 20px 10px;
            scrollbar-width: none;
        }
        .track-container::-webkit-scrollbar { display: none; }

        /* Card Rekomendasi Ala Gramedia tapi Y2K */
        .slider-card {
            min-width: 220px;
            max-width: 220px;
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            padding: 20px;
            border-radius: 20px;
            transition: 0.4s;
            position: relative;
            display: flex;
            flex-direction: column;
            border-color: rgba(205, 180, 219, 0.2); /* Lavendar subtle */
        }

        .slider-card:hover {
            transform: translateY(-10px);
            border-color: var(--lavender);
            box-shadow: 0 10px 30px rgba(205, 180, 219, 0.2);
        }

        .slider-card img {
            width: 100%; height: 280px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
            background: #111;
        }

        .rank-badge {
            position: absolute;
            top: -15px; left: 20px;
            background: var(--cotton-candy);
            color: var(--bg-dark);
            padding: 5px 18px;
            border-radius: 10px;
            font-family: 'Unbounded';
            font-size: 0.75rem;
            font-weight: 900;
            z-index: 2;
            box-shadow: 0 5px 10px rgba(255, 175, 204, 0.3);
        }

        /* Statistik Peminjaman Real-Time */
        .borrow-stats {
            position: absolute;
            top: 20px; right: 20px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            color: var(--sky-blue);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 800;
            border: 1px solid rgba(189, 224, 254, 0.2);
        }

        .book-title-small { font-family: 'Unbounded'; font-size: 0.95rem; margin-bottom: 5px; color: #fff; line-height: 1.3; }
        .book-author-small { color: rgba(255,255,255,0.5); font-size: 0.8rem; font-weight: 600; }

        /* Tombol Navigasi Slider */
        .btn-nav-slider {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px; height: 50px;
            border-radius: 50%;
            background: rgba(10, 10, 10, 0.6);
            color: var(--soft-pink);
            border: 2px solid var(--glass-border);
            backdrop-filter: blur(5px);
            cursor: pointer;
            z-index: 100;
            transition: 0.3s;
            display: flex; align-items: center; justify-content: center;
        }

        .btn-nav-slider:hover {
            border-color: var(--soft-pink);
            background: rgba(10, 10, 10, 0.8);
            box-shadow: 0 0 15px rgba(255, 175, 204, 0.3);
        }

        .btn-prev { left: 0px; }
        .btn-next { right: 0px; }

        /* ============================================================
           6. KOLEKSI BUKU (GRID ALA GRAMEDIA COMPACT)
           =========================================================== */
        #koleksi { padding: 80px 20px; position: relative; z-index: 2; background: rgba(255,255,255,0.01); }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); /* Compact Gramedia size */
            gap: 25px;
            width: 100%;
            max-width: 1200px;
        }

        .book-item {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 15px;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .book-item:hover {
            border-color: var(--sky-blue);
            box-shadow: 0 10px 25px rgba(189, 224, 254, 0.1);
            transform: translateY(-5px);
        }

        .img-box {
            width: 100%; height: 240px; /* Seragam */
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            margin-bottom: 15px;
        }

        .img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .book-item:hover .img-box img { transform: scale(1.08); }

        .category-pill {
            position: absolute;
            top: 10px; right: 10px;
            padding: 4px 10px;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(5px);
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--baby-blue);
            border: 1px solid var(--baby-blue);
        }

        .b-title { font-family: 'Unbounded'; font-size: 1rem; margin-bottom: 5px; color: #fff; line-height: 1.3; }
        .b-author { color: rgba(255,255,255,0.5); font-size: 0.8rem; margin-bottom: 10px; font-weight: 600; }
        
        .b-meta {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            padding-top: 10px;
            border-top: 1px dashed rgba(255,255,255,0.1);
        }

        .b-year { color: var(--text-subtle); }
        .b-stock { font-weight: 800; color: var(--baby-blue); }

        /* Floating Back Button */
        .btn-kembali {
            position: fixed;
            bottom: 40px; left: 40px;
            background: var(--lavender);
            color: var(--bg-dark);
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none !important;
            font-weight: 800;
            z-index: 1000;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 5px 15px rgba(205, 180, 219, 0.3);
            transition: 0.3s;
            font-size: 0.85rem;
        }

        .btn-kembali:hover { transform: scale(1.05); background: #fff; }

        footer {
            text-align: center;
            padding: 30px;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
            font-weight: 600;
            border-top: 1px solid var(--glass-border);
            background: #000;
        }

        /* ============================================================
           7. RESPONSIVE MOBILE
           =========================================================== */
        @media (max-width: 992px) {
            .glass-card { padding: 30px; }
            section:not(.hero) { padding: 80px 20px; }
        }

        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .btn-login { display: none; }
            
            .nav-links {
                position: fixed;
                top: 80px; left: 0;
                width: 100%;
                background: rgba(10, 10, 10, 0.95);
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
                z-index: 1000;
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

            .slider-wrapper { padding: 0; }
            .btn-nav-slider { width: 35px; height: 35px; }

            .book-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
            .img-box { height: 200px; }
            .b-title { font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="bg-glow" style="top: -10%; right: -5%; background: var(--lavender);"></div>
        <div class="bg-glow" style="bottom: 10%; left: -5%; background: var(--sky-blue);"></div>
        <div class="bg-glow" style="top: 40%; left: 10%; background: var(--cotton-candy);"></div>

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
                <p>SDN Berat Wetan 1 — Membangun generasi cerdas dan berbudaya literasi.</p>
                <div class="btn-wrapper">
                    <button class="btn-hero" onclick="window.location.href='{{ route('katalog') }}'">TELUSURI BUKU</button>
                </div>
            </div>
        </section>

        <section id="top10">
            <div class="section-header">
                <span class="section-subtitle">🔥 Paling Banyak Dicari</span>
                <h2 class="section-title">TOP 10 BUKU POPULER</h2>
            </div>

            <div class="slider-wrapper">
                <button class="btn-nav-slider btn-prev" onclick="moveSlide(-245)"><i class="fas fa-chevron-left"></i></button>
                <button class="btn-nav-slider btn-next" onclick="moveSlide(245)"><i class="fas fa-chevron-right"></i></button>

                <div class="track-container" id="mainSlider">
                    @php
                        // dummy data untuk contoh, ganti dengan @foreach($popularBooks as $index => $pb)
                        $pBooks = [
                            ['judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'gambar' => 'book1.png', 'borrowed' => 120],
                            ['judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'gambar' => 'book2.png', 'borrowed' => 95],
                            ['judul' => 'Laut Bercerita', 'penulis' => 'Leila S. Chudori', 'gambar' => 'book3.png', 'borrowed' => 88],
                            ['judul' => 'Sapiens', 'penulis' => 'Yuval Noah Harari', 'gambar' => 'book4.png', 'borrowed' => 75],
                            ['judul' => 'Hujan', 'penulis' => 'Tere Liye', 'gambar' => 'book1.png', 'borrowed' => 70],
                            ['judul' => 'Bumi', 'penulis' => 'Tere Liye', 'gambar' => 'book2.png', 'borrowed' => 65],
                        ];
                    @endphp

                    {{-- @foreach($popularBooks as $index => $pb) --}}
                    @foreach($pBooks as $index => $pb)
                    <div class="slider-card">
                        <div class="rank-badge">NO. {{ $index + 1 }}</div>
                        
                        {{-- <div class="borrow-stats"> Dipinjam: {{ $pb->borrowed_count ?? 0 }}x </div> --}}
                        <div class="borrow-stats"> Dipinjam: {{ $pb['borrowed'] }}x </div>

                        {{-- 
                        @if($pb->gambar)
                            <img src="{{ asset('storage/' . $pb->gambar) }}" alt="{{ $pb->judul }}">
                        @else
                            <img src="{{ asset('web-perpus/img/bukubaru.png') }}" alt="Default Book">
                        @endif 
                        --}}
                        <img src="{{ asset('web-perpus/img/' . $pb['gambar']) }}" alt="Main Book">
                        
                        <h3 class="book-title-small">{{ Str::limit($pb['judul'], 30) }}</h3>
                        <p class="book-author-small"><i class="fas fa-pen-nib"></i> {{ $pb['penulis'] }}</p>
                    </div>
                    @endforeach
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
                
                <hr style="border: 0; border-top: 1px dashed rgba(255,255,255,0.1); margin: 30px 0;">
                
                <p><i class="fas fa-map-marker-alt" style="color: var(--sky-blue); margin-right: 10px; width: 20px; text-align: center;"></i> <strong>Alamat:</strong> Jl. KH. Abdul Fattah No.04, Berat Wetan, Kec. Gedeg, Kabupaten Mojokerto, Jawa Timur</p>
                <p><i class="fas fa-envelope" style="color: var(--soft-pink); margin-right: 10px; width: 20px; text-align: center;"></i> <strong>Email:</strong> berat.wetan1@gmail.com</p>
                <p><i class="fas fa-id-card" style="color: var(--lavender); margin-right: 10px; width: 20px; text-align: center;"></i> <strong>NPSN:</strong> 20502893</p>
                <p><i class="fas fa-clock" style="color: var(--baby-blue); margin-right: 10px; width: 20px; text-align: center;"></i> <strong>Jam Operasional:</strong></p>
                <ul style="list-style: none; padding-left: 40px; margin-top: 5px;">
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
                <p><strong>Nama:</strong> Lilik Nurhayati, S.Pd</p>
                <p><strong>Email:</strong> lilik.nur246@guruku.belajar.id</p>
                <p><strong>Jabatan:</strong> Pustakawan</p>
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
                    <a href="https://maps.google.com/?q=SDN+Berat+Wetan+1" target="_blank" class="btn-direksi">
                        <i class="fas fa-directions"></i> Buka di Google Maps
                    </a>
                </div>
                
                <div style="text-align: center;">
                    <p style="margin-bottom: 20px; font-family: 'Unbounded'; color: var(--lavender);">Denah Ruangan Perpustakaan</p>
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
    <script>
        // Fungsi untuk menggeser slider rekomendasi
        function moveSlide(offset) {
            const slider = document.getElementById('mainSlider');
            if (slider) {
                slider.scrollBy({ left: offset, behavior: 'smooth' });
            }
        }
    </script>
</body>
</html>