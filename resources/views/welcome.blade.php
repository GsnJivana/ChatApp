<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application de messagerie </title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('chat-svgrepo-com.svg')}}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons (juste pour l'icône) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles Personnalisés -->
    <style>
        :root {
            --dark-bg: #0D0D2B;
            --blur-strength: 100px; /* Force du flou des blobs */
        }
        
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: #fff;
            overflow: hidden;
        }

        .hero-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        /* --- Le conteneur pour nos blobs animés --- */
        .background-blobs {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            opacity: 0.7;
            filter: blur(var(--blur-strength));
            animation: move 25s infinite alternate;
        }

        .blob1 {
            width: 400px;
            height: 400px;
            top: -150px;
            left: -150px;
            background: radial-gradient(circle, #ff2a6d, #ff7f50);
        }

        .blob2 {
            width: 300px;
            height: 300px;
            bottom: -100px;
            right: -100px;
            background: radial-gradient(circle, #05d9e8, #28b5b5);
            animation-duration: 20s;
            animation-delay: -10s;
        }

        .blob3 {
            width: 250px;
            height: 250px;
            top: 50%;
            left: 50%;
            background: radial-gradient(circle, #ad5389, #3c1053);
            animation-duration: 30s;
        }

        @keyframes move {
            from {
                transform: translate(-100px, 50px) rotate(-90deg);
            }
            to {
                transform: translate(100px, -50px) rotate(90deg);
            }
        }

        /* --- La carte Glassmorphism --- */
        .glass-card {
            position: relative;
            z-index: 2;
            width: 90%;
            max-width: 600px;
            padding: 50px;
            
            /* L'effet de verre */
            background: rgba(255, 255, 255, 0.05);
            -webkit-backdrop-filter: blur(40px);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .logo .bi {
            color: #fff;
            opacity: 0.8;
            font-size: 2.2rem;
        }
        
        .glass-card h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .glass-card p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .action-buttons a {
            text-decoration: none;
            color: #7ac374;
            padding: 14px 32px;
            margin: 0 10px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background-color: #fff;
            color: var(--dark-bg);
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        .btn-secondary {
            background-color: transparent;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
        }

        .top-right-links {
            position: absolute;
            top: 25px;
            right: 40px;
            z-index: 3;
        }

        .top-right-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            margin-left: 25px;
            font-weight: 600;
            transition: color 0.2s;
        }
        .top-right-links a:hover {
            color: white;
        }
    </style>
</head>
<body>

    <div class="hero-wrapper">
        <!-- Les blobs animés en arrière-plan -->
        <div class="background-blobs">
            <div class="blob blob1"></div>
            <div class="blob blob2"></div>
            <div class="blob blob3"></div>
        </div>
        
        @if (Route::has('login'))
            <div class="top-right-links">
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Se connecter</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">S'inscrire</a>
                    @endif
                @endauth
            </div>
        @endif
        
        <!-- La carte centrale en verre -->
        <div class="glass-card">
            <div class="logo"><i class="bi bi-stars"></i>Chat App</div>
            <h1>Des conversations qui rayonnent.</h1>
            <p>Plongez dans une expérience de messagerie immersive, où chaque mot flotte dans un univers de couleurs et de fluidité.</p>
            <div class="action-buttons">
                 @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">Entrer dans l'Aura</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn-secondary">S'inscrire</a>
                @endauth
            </div>
        </div>
    </div>

</body>
</html>