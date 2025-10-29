<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/favicon.ico" />
    <title>{{ $title ?? 'Kirke-Foto' }} - Kirke-Foto</title>

    @php
        $ogTitle = $ogTitle ?? 'Forside - Kirke-Foto';
        $ogType = $ogType ?? 'website';
        $ogDescription = $ogDescription ?? 'Et billedgalleri over de danske kirker både fra landjorden og luften.';
        $ogImage = $ogImage ?? 'https://kirke-foto.dk/images/church/high_P4Ai1Hv65iGQfrnPlXO70XP8TBch4wtcwlrL1DQ3.jpg';
        $ogUrl = $ogUrl ?? 'https://kirke-foto.dk';
        $metaDescription =
            $metaDescription ??
            'Et billedgalleri over de danske kirker både fra landjorden og luften. Søg nemt imellem stifter, provstier, sogne og kirker og find netop din kirke frem.';
        $metaKeywords =
            $metaKeywords ??
            'kirke, foto, galleri, billeder, kirke-foto, kirkefoto, billed, kirke-galleri, kirkegalleri, dansk, danmark';
    @endphp

    <meta name="og:title" property="og:title" content="{{ $ogTitle }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta name="og:description" property="og:description" content="{{ $ogDescription }}">
    <meta name="og:image" property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ $ogUrl }}">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">

    <script src="https://kit.fontawesome.com/8a902d7685.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/nice-select2@2.3.1/dist/js/nice-select2.min.js"></script>
    <link href="
    https://cdn.jsdelivr.net/npm/nice-select2@2.3.1/dist/css/nice-select2.min.css
    "
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css" rel="stylesheet" />
    @livewireStyles
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="main">
        <header class="header">
            <a aria-current="page" class="logo active" href="/">
                <img src="/images/logo.svg" alt="Logo">
                <h1>Kirke-Foto</h1>
            </a>
            <nav>
                <ul class="menu">
                    <li><a class="{{ request()->is('/') ? 'active' : '' }}" href="/">Forside</a></li>
                    <li><a class="{{ request()->is('kort') ? 'active' : '' }}" href="/kort">Kort</a></li>
                    <li><a class="{{ request()->is('om-os') ? 'active' : '' }}" href="/om-os">Om os</a></li>
                    <li><a class="{{ request()->is('kontakt') ? 'active' : '' }}" href="/kontakt">Kontakt</a></li>
                </ul>
            </nav>
        </header>
        {{ $slot }}
        <footer>© Copyright 2022-2025 - Kirke-Foto.dk af <a href="https://renedyhr.me" target="_blank"
                rel="noreferrer">René
                Dyhr</a></footer>
    </div>
    @livewireScripts
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1O33PxYdUCfITPpJblqE9yk_k5BtiIcA" async defer></script>
</body>

</html>
