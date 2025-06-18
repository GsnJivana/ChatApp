<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('chat-svgrepo-com.svg')}}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    @auth
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"> </script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const userId = {{ auth()->id() }};
        const notificationBadge = document.getElementById('notification-badge');
        let notificationCount = {{ auth()->user()->unreadNotifications->count() }};

        function updateBadge() {
            if (notificationCount > 0) {
                notificationBadge.innerText = notificationCount;
                notificationBadge.style.display = 'inline-block';
            } else {
                notificationBadge.style.display = 'none';
            }
        }

        updateBadge(); // Mettre à jour au chargement de la page

        window.Echo.private('App.Models.User.' + userId)
            .notification((notification) => {
                console.log('Notification reçue:', notification);
                notificationCount++;
                updateBadge();
                // Optionnel : afficher un "toast"
                alert(notification.sender_name + " " + notification.text);
            });
    });
    </script>
    @endauth
</html>
