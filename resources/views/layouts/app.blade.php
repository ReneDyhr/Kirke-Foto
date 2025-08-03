<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'My App' }}</title>
    <script src="https://kit.fontawesome.com/8a902d7685.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/nice-select2@2.3.1/dist/js/nice-select2.min.js"></script>
    <link href="
    https://cdn.jsdelivr.net/npm/nice-select2@2.3.1/dist/css/nice-select2.min.css
    " rel="stylesheet">
    @livewireStyles
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="main">
        <header class="header">
            <a aria-current="page" class="logo active" href="/">
                <img src="https://kirke-foto.dk/logo.svg" alt="Logo">
                <h1>Kirke-Foto</h1>
            </a>
            <nav>
                <ul class="menu">
                    <li><a aria-current="page" class="active" href="/">Forside</a></li>
                    <li><a class="" href="/kort">Kort</a></li>
                    <li><a class="" href="/om-os">Om os</a></li>
                    <li><a class="" href="/kontakt">Kontakt</a></li>
                </ul>
            </nav>
        </header>
        {{ $slot }}
        <footer>© Copyright 2022-2025 - Kirke-Foto.dk af <a href="https://renedyhr.me" target="_blank"
                rel="noreferrer">René
                Dyhr</a></footer>
    </div>
    @livewireScripts
</body>

</html>
