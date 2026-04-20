import './bootstrap';
import { createApp, ref, onMounted, onUnmounted } from 'vue';

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

            const checkSection = (id, setter) => {
                const el = document.getElementById(id);
                if (el && scrollPosition > el.offsetTop + 100) {
                    setter.value = true;
                }
            };

            checkSection('informasi', isInfoInView);
            checkSection('visi', isVisiInView);
            checkSection('pustakawan', isPustakawanInView);
            checkSection('denah', isDenahInView);
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
                'web-perpus/img/book1.png',
                'web-perpus/img/book2.png',
                'web-perpus/img/book3.png',
                'web-perpus/img/book4.png',
                'web-perpus/img/book5.png'
            ];

            container.innerHTML = '';

            for (let i = 0; i < 10; i++) {
                const book = document.createElement('div');
                book.className = 'book';

                const randomImage = bookImages[Math.floor(Math.random() * bookImages.length)];
                book.style.backgroundImage = `url(${randomImage})`;

                book.style.left = `${Math.random() * 100}%`;
                book.style.top = `${Math.random() * 100}%`;

                book.style.animationDuration = `${15 + Math.random() * 15}s`;
                book.style.animationDelay = `${Math.random() * 5}s`;

                book.style.transform = `scale(${0.8 + Math.random() * 0.7})`;

                container.appendChild(book);
            }
        };

        onMounted(() => {
            window.addEventListener('scroll', handleScroll);
            window.addEventListener('mousemove', handleMouseMove);

            createFloatingBooks();
            handleScroll();
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