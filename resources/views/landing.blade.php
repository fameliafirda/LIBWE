{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Buku | Perpustakaan Online SDN BERATWETAN 1</title>
    
    {{-- Dependencies --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Mengembalikan Custom CSS yang Kompleks */
        :root {
            --pink-y2k: #ff5ea3;
            --blue-y2k: #5eb0ff;
            --dark-y2k: #0a0a0a;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--dark-y2k);
            color: white;
            overflow-x: hidden;
        }

        .font-kids { font-family: 'Fredoka One', cursive; }

        /* Animation Keyframes (Menambah baris kode untuk visual interaktif) */
        @keyframes float-y {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .book-float {
            position: absolute;
            background-size: contain;
            background-repeat: no-repeat;
            z-index: 1;
            transition: all 0.5s ease;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.5));
        }

        /* Navbar Glassmorphism */
        .nav-glass {
            background: rgba(10, 10, 10, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-scrolled { padding: 0.75rem 2rem !important; }

        /* Card Bento Effects */
        .bento-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 2rem;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .bento-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--pink-y2k);
            box-shadow: 0 20px 40px rgba(255, 94, 163, 0.15);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--dark-y2k); }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--pink-y2k); }

        /* Search Bar Floating Style */
        .search-container {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 100px;
            padding: 5px 20px;
            transition: 0.3s;
        }
        .search-container:focus-within {
            border-color: var(--blue-y2k);
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <div id="app" class="relative min-h-screen">
        
        <div id="floatingBooks" class="fixed inset-0 pointer-events-none overflow-hidden"></div>

        <header :class="['fixed top-0 w-full z-50 flex justify-between items-center px-8 py-6 nav-glass transition-all', 
                        isScrolled ? 'nav-scrolled shadow-2xl' : '']">
            <div class="flex items-center gap-4">
                <img src="{{ asset('web-perpus/img/logo.png') }}" class="w-10 h-10 object-contain" alt="Logo">
                <div class="flex flex-col">
                    <span class="font-kids text-2xl tracking-tighter leading-none">LIBWE</span>
                    <span class="text-[10px] uppercase tracking-[0.2em] text-pink-500">Digital Library</span>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-3 flex-1 max-w-xl mx-10">
                <div class="search-container flex items-center w-full">
                    <i class="fas fa-search text-gray-400 mr-3"></i>
                    <input type="text" placeholder="Cari judul buku atau penulis..." 
                           class="bg-transparent border-none outline-none text-sm w-full py-2">
                    <button class="ml-2 p-2 hover:text-blue-400 transition" title="Scan Database">
                        <i class="fas fa-database text-lg"></i>
                    </button>
                </div>
                <button class="bg-white/10 hover:bg-white/20 p-3 rounded-full transition" title="Kategori Baru">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>

            <nav class="hidden lg:flex items-center gap-8">
                <a href="#informasi" class="text-xs font-bold uppercase tracking-widest hover:text-pink-500 transition">Informasi</a>
                <a href="#visi" class="text-xs font-bold uppercase tracking-widest hover:text-pink-500 transition">Visi Misi</a>
                <button onclick="window.location.href='{{ route('login') }}'" 
                        class="bg-pink-500 hover:bg-pink-600 px-8 py-2 rounded-full font-kids text-sm transition-all transform hover:scale-105">
                    MASUK
                </button>
            </nav>
        </header>

        <section class="relative pt-40 pb-20 px-8 flex flex-col items-center text-center">
            <div class="absolute top-20 left-10 w-32 h-32 bg-blue-500/20 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 right-10 w-32 h-32 bg-pink-500/20 rounded-full blur-[100px]"></div>
            
            <h1 class="font-kids text-7xl md:text-9xl mb-4 leading-tight">
                KATALOG <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-blue-500">BUKU</span>
            </h1>
            <p class="max-w-2xl text-gray-400 text-lg md:text-xl mb-10">
                Eksplorasi ribuan koleksi buku digital dan fisik di Perpustakaan Online 
                <span class="text-white font-bold">SDN BERATWETAN 1</span>. Belajar jadi lebih asik!
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <button class="bg-white text-black font-kids px-10 py-4 rounded-2xl hover:bg-pink-500 hover:text-white transition-all shadow-xl">
                    LIHAT KOLEKSI 📖
                </button>
                <button class="border-2 border-white/20 px-10 py-4 rounded-2xl font-kids hover:bg-white/10 transition">
                    TENTANG KAMI
                </button>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-8 py-20 grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div id="informasi" :class="['md:col-span-2 bento-card p-10 flex flex-col justify-center transition-all duration-1000', 
                                      isInfoInView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-20']">
                <div class="flex items-center gap-4 mb-6">
                    <span class="p-3 bg-blue-500/20 rounded-2xl text-blue-400">
                        <i class="fas fa-info-circle text-2xl"></i>
                    </span>
                    <h2 class="font-kids text-3xl italic">SEJARAH & INFO</h2>
                </div>
                <p class="text-gray-400 leading-relaxed text-lg">
                    Perpustakaan SDN Berat Wetan 1 bukan sekadar tempat menyimpan buku. Sejak 2005, kami telah bertransformasi menjadi jantung literasi desa. Dengan koleksi lebih dari 5.000 judul, kami melayani mimpi anak-anak bangsa setiap harinya.
                </p>
            </div>

            <div class="bento-card p-10 bg-pink-500 flex flex-col justify-between group transition-all duration-1000">
                <i class="fas fa-bolt text-5xl text-white/30 group-hover:rotate-12 transition"></i>
                <div>
                    <h3 class="font-kids text-4xl mb-2 text-white">5K+</h3>
                    <p class="text-white/80 font-bold uppercase tracking-tighter">Buku Tersedia</p>
                </div>
            </div>

            <div id="visi" :class="['md:col-span-3 bento-card p-12 transition-all duration-1000 delay-200',
                                  isVisiInView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-20']">
                <div class="grid md:grid-cols-2 gap-12">
                    <div class="border-r border-white/10 pr-8">
                        <h2 class="font-kids text-4xl mb-6 text-blue-400">VISI KAMI</h2>
                        <p class="text-2xl font-light italic leading-relaxed text-gray-300">
                            "Mewujudkan generasi cerdas, kreatif, dan berakhlak mulia melalui akses literasi digital yang inklusif."
                        </p>
                    </div>
                    <div>
                        <h2 class="font-kids text-4xl mb-6 text-pink-400">MISI KAMI</h2>
                        <ul class="space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="text-pink-500 mt-1"><i class="fas fa-check-circle"></i></span>
                                <span class="text-gray-400">Menyediakan bahan bacaan yang edukatif dan menghibur.</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-pink-500 mt-1"><i class="fas fa-check-circle"></i></span>
                                <span class="text-gray-400">Mengintegrasikan teknologi dalam sistem peminjaman buku.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="pustakawan" :class="['bento-card p-8 text-center flex flex-col items-center transition-all duration-1000',
                                        isPustakawanInView ? 'opacity-100' : 'opacity-0']">
                <div class="relative mb-6">
                    <div class="absolute inset-0 bg-blue-500 rounded-full blur-2xl opacity-20 animate-pulse"></div>
                    <img src="{{ asset('web-perpus/img/pustakawan.png') }}" 
                         class="w-32 h-32 rounded-full border-4 border-white/10 relative z-10 object-cover shadow-2xl">
                </div>
                <h3 class="font-kids text-xl mb-1">Lilik Nurhayati, S.Pd</h3>
                <p class="text-xs text-blue-400 uppercase font-bold tracking-widest mb-4">Kepala Perpustakaan</p>
                <div class="flex gap-3">
                    <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-pink-500 transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-blue-500 transition"><i class="fas fa-envelope"></i></a>
                </div>
            </div>

            <div id="denah" :class="['md:col-span-2 bento-card overflow-hidden transition-all duration-1000 delay-300',
                                   isDenahInView ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-20']">
                <iframe src="https://www.google.com/maps/embed?..." 
                        class="w-full h-full min-h-[300px] border-none grayscale invert contrast-125" 
                        allowfullscreen="" loading="lazy"></iframe>
            </div>
        </main>

        <footer class="py-20 text-center border-t border-white/5">
            <p class="font-kids text-2xl mb-4">SDN BERATWETAN 1</p>
            <p class="text-xs tracking-[0.5em] text-gray-500">DESIGNED BY FAMELLA FIRDA LEVIA • 2026</p>
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
                    const scrollPos = window.scrollY + window.innerHeight * 0.85;

                    const sections = [
                        { id: 'informasi', ref: isInfoInView },
                        { id: 'visi', ref: isVisiInView },
                        { id: 'pustakawan', ref: isPustakawanInView },
                        { id: 'denah', ref: isDenahInView }
                    ];

                    sections.forEach(sec => {
                        const el = document.getElementById(sec.id);
                        if (el && scrollPos > el.offsetTop) {
                            sec.ref.value = true;
                        }
                    });
                };

                const createFloatingBooks = () => {
                    const container = document.getElementById('floatingBooks');
                    if (!container) return;

                    const bookColors = ['#ff5ea3', '#5eb0ff', '#ffffff'];
                    for (let i = 0; i < 15; i++) {
                        const book = document.createElement('div');
                        book.className = 'book-float';
                        book.style.width = Math.random() * 40 + 30 + 'px';
                        book.style.height = Math.random() * 60 + 40 + 'px';
                        book.style.left = Math.random() * 100 + '%';
                        book.style.top = Math.random() * 100 + '%';
                        book.style.backgroundColor = bookColors[Math.floor(Math.random() * 3)];
                        book.style.opacity = '0.1';
                        book.style.borderRadius = '4px';
                        book.style.animation = `float-y ${10 + Math.random() * 10}s infinite ease-in-out`;
                        book.style.animationDelay = `${Math.random() * 5}s`;
                        container.appendChild(book);
                    }
                };

                onMounted(() => {
                    window.addEventListener('scroll', handleScroll);
                    createFloatingBooks();
                    handleScroll();
                });

                onUnmounted(() => {
                    window.removeEventListener('scroll', handleScroll);
                });

                return { isScrolled, isInfoInView, isVisiInView, isPustakawanInView, isDenahInView };
            }
        }).mount('#app');
    </script>
</body>
</html>