<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'My App' }}</title>
    <script src="https://kit.fontawesome.com/8a902d7685.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;500;600;700;800&display=swap" rel="stylesheet">
    <script src="
    https://cdn.jsdelivr.net/npm/nice-select2@2.3.1/dist/js/nice-select2.min.js
    "></script>
    <link href="
    https://cdn.jsdelivr.net/npm/nice-select2@2.3.1/dist/css/nice-select2.min.css
    " rel="stylesheet">
    @livewireStyles
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

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
    <div class="content">
        <div class="sidebar-main">
            <h1>Velkommen til Kirke-Foto</h1>
            <p>Formålet med denne hjemmeside er, at dokumentere projektet: "Billedlig bevaring af de danske kirker".</p>
            <p>Billederne er originale og er til fri afbenyttelse, såfremt Kirke-Foto.dk er angivet som kilde.</p>
            <p style="margin-bottom: 0px;">Der er i skrivende stund <b>128</b> kirker oprettet med billeder henover <b>25</b> provstier.</p>
            <p>Der er i alt <b>124</b> kirker med dronetilladelse fra tilhørende menighedsråd.</p>
            <div class="form-group">
                <label>Stift</label>
                <select id="stift">
                    <option value="1">Aalborg Provsti</option>
                    <option value="2">Aarhus Provsti</option>
                    <option value="3">Bornholm Provsti</option>
                    <option value="4">Esbjerg Provsti</option>
                    <option value="5">Fyns Provsti</option>
                    <option value="6">Københavns Provsti</option>
                    <option value="7">Lolland-Falsters Provsti</option>
                    <option value="8">Ribe Provsti</option>
                    <option value="9">Ringkøbing Provsti</option>
                    <option value="10">Roskilde Provsti</option>
                    <option value="11">Svendborg Provsti</option>
                </select>
            </div>
            <div class="form-group">
                <label>Provsti</label>
                <select id="provsti">
                    <option value="1">Aalborg Provsti</option>
                    <option value="2">Aarhus Provsti</option>
                    <option value="3">Bornholm Provsti</option>
                    <option value="4">Esbjerg Provsti</option>
                    <option value="5">Fyns Provsti</option>
                    <option value="6">Københavns Provsti</option>
                    <option value="7">Lolland-Falsters Provsti</option>
                    <option value="8">Ribe Provsti</option>
                    <option value="9">Ringkøbing Provsti</option>
                    <option value="10">Roskilde Provsti</option>
                    <option value="11">Svendborg Provsti</option>
                </select>
            </div>
            <div class="form-group">
                <label>Sogn</label>
                <select id="sogn">
                    <option value="1">Aalborg Provsti</option>
                    <option value="2">Aarhus Provsti</option>
                    <option value="3">Bornholm Provsti</option>
                    <option value="4">Esbjerg Provsti</option>
                    <option value="5">Fyns Provsti</option>
                    <option value="6">Københavns Provsti</option>
                    <option value="7">Lolland-Falsters Provsti</option>
                    <option value="8">Ribe Provsti</option>
                    <option value="9">Ringkøbing Provsti</option>
                    <option value="10">Roskilde Provsti</option>
                    <option value="11">Svendborg Provsti</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kirke</label>
                <select id="kirke">
                    <option value="1">Aalborg Provsti</option>
                    <option value="2">Aarhus Provsti</option>
                    <option value="3">Bornholm Provsti</option>
                    <option value="4">Esbjerg Provsti</option>
                    <option value="5">Fyns Provsti</option>
                    <option value="6">Københavns Provsti</option>
                    <option value="7">Lolland-Falsters Provsti</option>
                    <option value="8">Ribe Provsti</option>
                    <option value="9">Ringkøbing Provsti</option>
                    <option value="10">Roskilde Provsti</option>
                    <option value="11">Svendborg Provsti</option>
                </select>
            </div>
        </div>
        <div class="sidebar">
            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title">Seneste opdaterede</h5>
                    <div class="card-text">
                        <ul class="latest-churches">
                            <li><a class="" href="/kirke/gaarslev/gaarslev-kirke">Gårslev Kirke</a></li>
                            <li><a class="" href="/kirke/brudager/brudager-kirke">Brudager Kirke</a></li>
                            <li><a class="" href="/kirke/gauerslund/brejning-kirke">Brejning Kirke</a></li>
                            <li><a class="" href="/kirke/oesterhurup/oester-hurup-kirke">Øster Hurup Kirke</a></li>
                            <li><a class="" href="/kirke/roerslev/roerslev-kirke">Roerslev Kirke</a></li>
                            <li><a class="" href="/kirke/als/als-kirke">Als Kirke</a></li>
                            <li><a class="" href="/kirke/bredballe/bredballe-kirke">Bredballe Kirke</a></li>
                            <li><a class="" href="/kirke/mou/mou-kirke">Mou Kirke</a></li>
                            <li><a class="" href="/kirke/mou/dokkedal-kirke">Dokkedal Kirke</a></li>
                            <li><a class="" href="/kirke/norup/hasmark-kirke">Hasmark Kirke</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>© Copyright 2022-2025 - Kirke-Foto.dk af <a href="https://renedyhr.me" target="_blank" rel="noreferrer">René Dyhr</a></footer>
    @livewireScripts
</body>
</html>
