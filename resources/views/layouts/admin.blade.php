<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/images/favicon.ico" />
    <title>{{ $title ?? 'Kirke-Foto' }} - Kirke-Foto</title>
    <script src="https://kit.fontawesome.com/8a902d7685.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/nice-select2@2.3.1/dist/js/nice-select2.min.js"></script>
    <link href="
    https://cdn.jsdelivr.net/npm/nice-select2@2.3.1/dist/css/nice-select2.min.css
    " rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css" rel="stylesheet" />
    <!-- Alpine.js guarded loader to avoid multiple instances -->
    <script>
        if (!window.Alpine) {
            var s = document.createElement('script');
            s.src = 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js';
            s.defer = true;
            document.head.appendChild(s);
        }
    </script>
    @livewireStyles
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="main">
        <header class="header">
            <a aria-current="page" class="logo active" href="/admin/">
                <img src="/images/logo.svg" alt="Logo">
                <h1>Kirke-Foto</h1>
            </a>
            <nav>
                @if (auth()->check())
                    <ul class="menu">
                        <li><a href="/admin/">Kirker</a></li>
                    </ul>
                @endif
            </nav>
        </header>
        @if (auth()->check())
            {{ $slot }}
            @yield('content')
        @else
            @livewire('auth.login-form')
        @endif
        <footer>© Copyright 2022-2025 - Kirke-Foto.dk af <a href="https://renedyhr.me" target="_blank"
                rel="noreferrer">René
                Dyhr</a></footer>
    </div>
    @livewireScripts
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1O33PxYdUCfITPpJblqE9yk_k5BtiIcA" async defer></script>
</body>

</html>
