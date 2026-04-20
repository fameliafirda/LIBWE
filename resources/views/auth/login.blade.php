<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pustakawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-hover: #7c3aed;
            --pink: #ec4899;
            --blue: #3b82f6;
            --dark: #0f172a;
            --darker: #020617;
            --card-bg: rgba(15, 23, 42, 0.8);
            --text: #e2e8f0;
            --text-secondary: #94a3b8;
            --border: rgba(255, 255, 255, 0.08);
            --border-focus: rgba(139, 92, 246, 0.5);
            --error: #ef4444;
            --back-button: rgba(30, 41, 59, 0.8);
            --back-button-hover: rgba(30, 41, 59, 1);
            --glow: 0 0 30px rgba(139, 92, 246, 0.3);
            --magic-spark: rgba(255, 255, 255, 0.8);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: 
                radial-gradient(circle at 20% 30%, rgba(236, 72, 153, 0.2) 0%, transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.2) 0%, transparent 30%),
                linear-gradient(to bottom, var(--darker), var(--dark));
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            overflow: hidden;
            position: relative;
        }

        /* Magical Sparkles */
        .sparkle {
            position: absolute;
            background: var(--magic-spark);
            border-radius: 50%;
            pointer-events: none;
            animation: float 3s ease-in-out infinite, fade 3s ease-in-out infinite;
            filter: blur(1px);
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        @keyframes fade {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(139, 92, 246, 0); }
        }

        @keyframes glow {
            0%, 100% { text-shadow: 0 0 5px rgba(236, 72, 153, 0.5); }
            50% { text-shadow: 0 0 20px rgba(236, 72, 153, 0.8); }
        }

        .login-container {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: var(--glow);
            border: 1px solid var(--border);
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
            z-index: -1;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h2 {
            text-align: center;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 32px;
            color: var(--text);
            letter-spacing: -0.5px;
            position: relative;
            animation: glow 3s ease-in-out infinite;
        }

        h2::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--pink), var(--primary));
            margin: 12px auto 0;
            border-radius: 3px;
            box-shadow: 0 0 10px rgba(236, 72, 153, 0.5);
        }

        .input-group {
            position: relative;
            margin-bottom: 24px;
        }

        input {
            width: 100%;
            padding: 16px 20px;
            background: rgba(2, 6, 23, 0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        input::placeholder {
            color: var(--text-secondary);
            opacity: 0.6;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--border-focus), 0 0 20px rgba(139, 92, 246, 0.3);
            background: rgba(2, 6, 23, 0.7);
            animation: pulse 2s infinite;
        }

        button, .back-button {
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: none;
            margin-top: 8px;
            position: relative;
            overflow: hidden;
        }

        button {
            background: linear-gradient(to right, var(--pink), var(--primary));
            color: var(--text);
            margin-bottom: 16px;
            box-shadow: 0 4px 20px rgba(139, 92, 246, 0.5);
            z-index: 1;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
            z-index: -1;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.7);
            background: linear-gradient(to right, #db2777, #7c3aed);
        }

        button:hover::before {
            left: 100%;
        }

        .back-button {
            background-color: var(--back-button);
            color: var(--text-secondary);
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .back-button:hover {
            background-color: var(--back-button-hover);
            color: var(--text);
            transform: translateY(-1px);
        }

        .error {
            color: var(--error);
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            padding: 12px;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 8px;
            border-left: 3px solid var(--error);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .logo svg {
            width: 60px;
            height: 60px;
            fill: url(#gradient);
            filter: drop-shadow(0 0 10px rgba(139, 92, 246, 0.5));
            animation: float 4s ease-in-out infinite;
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Floating books animation */
        .floating-books {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
        }

        .book {
            position: absolute;
            opacity: 0.1;
            animation: float-up 15s linear infinite;
        }

        @keyframes float-up {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.1; }
            90% { opacity: 0.1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px;
                border-radius: 16px;
            }
            
            h2 {
                font-size: 24px;
            }
            
            input, button, .back-button {
                padding: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Magical Sparkles -->
    <div class="floating-books">
        <div class="book" style="left: 10%; animation-delay: 0s;">📕</div>
        <div class="book" style="left: 20%; animation-delay: 2s;">📗</div>
        <div class="book" style="left: 30%; animation-delay: 4s;">📘</div>
        <div class="book" style="left: 40%; animation-delay: 6s;">📙</div>
        <div class="book" style="left: 50%; animation-delay: 8s;">📔</div>
        <div class="book" style="left: 60%; animation-delay: 10s;">📒</div>
        <div class="book" style="left: 70%; animation-delay: 12s;">📚</div>
        <div class="book" style="left: 80%; animation-delay: 14s;">📖</div>
    </div>

    <div class="login-container">
        <div class="logo">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#ec4899" />
                        <stop offset="100%" stop-color="#8b5cf6" />
                    </linearGradient>
                </defs>
                <path d="M12 3c-4.97 0-9 3.185-9 7.115 0 2.557 1.522 4.82 3.889 6.115l-.78 2.77h2.178l.5-1.758c.633.225 1.313.353 2.013.38v2.393h2v-2.393c.7-.027 1.38-.155 2.013-.38l.5 1.758h2.178l-.78-2.77c2.367-1.295 3.889-3.558 3.889-6.115 0-3.93-4.03-7.115-9-7.115zm0 1c4.418 0 8 2.691 8 6.115 0 2.01-1.217 3.86-3.184 5.027l-.816-2.872h-8l-.816 2.872c-1.967-1.167-3.184-3.017-3.184-5.027 0-3.424 3.582-6.115 8-6.115zm-4 3v1h8v-1h-8zm0 2v1h8v-1h-8zm0 2v1h5v-1h-5z"/>
            </svg>
        </div>
        
        <h2>Login Pustakawan</h2>

        {{-- Menampilkan pesan error dari session --}}
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        {{-- Menampilkan error validasi --}}
        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        {{-- Form login --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Masuk</button>
        </form>

        {{-- Tombol kembali ke beranda --}}
        <a href="{{ url('/') }}" class="back-button">← Kembali ke Beranda</a>
        
        <div class="footer-text">
            Sistem Perpustakaan Digital | Famelia © 2025
        </div>
    </div>

    <script>
        // Create magical sparkles
        document.addEventListener('DOMContentLoaded', function() {
            // Create sparkles
            function createSparkles() {
                const colors = ['#ec4899', '#8b5cf6', '#3b82f6', '#ffffff'];
                const container = document.body;
                
                for (let i = 0; i < 20; i++) {
                    const sparkle = document.createElement('div');
                    sparkle.className = 'sparkle';
                    
                    // Random properties
                    const size = Math.random() * 6 + 2;
                    const posX = Math.random() * 100;
                    const posY = Math.random() * 100;
                    const delay = Math.random() * 5;
                    const duration = Math.random() * 3 + 2;
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    
                    sparkle.style.width = `${size}px`;
                    sparkle.style.height = `${size}px`;
                    sparkle.style.left = `${posX}%`;
                    sparkle.style.top = `${posY}%`;
                    sparkle.style.animationDelay = `${delay}s`;
                    sparkle.style.animationDuration = `${duration}s`;
                    sparkle.style.backgroundColor = color;
                    
                    container.appendChild(sparkle);
                }
            }
            
            createSparkles();
            
            // Add sparkle effect on button hover
            const button = document.querySelector('button');
            button.addEventListener('mouseenter', function() {
                for (let i = 0; i < 5; i++) {
                    createButtonSparkle(this);
                }
            });
            
            function createButtonSparkle(button) {
                const rect = button.getBoundingClientRect();
                const sparkle = document.createElement('div');
                sparkle.className = 'sparkle';
                
                const size = Math.random() * 8 + 4;
                const posX = Math.random() * rect.width;
                const posY = Math.random() * rect.height;
                const duration = Math.random() * 1 + 0.5;
                
                sparkle.style.width = `${size}px`;
                sparkle.style.height = `${size}px`;
                sparkle.style.left = `${rect.left + posX}px`;
                sparkle.style.top = `${rect.top + posY}px`;
                sparkle.style.animationDuration = `${duration}s`;
                sparkle.style.backgroundColor = '#ffffff';
                sparkle.style.opacity = '0.8';
                
                document.body.appendChild(sparkle);
                
                // Remove sparkle after animation
                setTimeout(() => {
                    sparkle.remove();
                }, duration * 1000);
            }
        });
    </script>
</body>
</html>