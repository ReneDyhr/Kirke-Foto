<div class="content">
    <div class="sidebar-main">
        <h1>Velkommen til Kirke-Foto</h1>
        <p>Formålet med denne hjemmeside er, at dokumentere projektet: "Billedlig bevaring af de danske kirker".</p>
        <p>Billederne er originale og er til fri afbenyttelse, såfremt Kirke-Foto.dk er angivet som kilde.</p>
        <p style="margin-bottom: 0px;">Der er i skrivende stund <b>128</b> kirker oprettet med billeder henover <b>25</b>
            provstier.</p>
        <p>Der er i alt <b>124</b> kirker med dronetilladelse fra tilhørende menighedsråd.</p>
        <div class="form-group">
            <label>Stift</label>
            <select id="stift">
                @foreach ($dioceses as $diocese)
                    <option value="{{ $diocese->id }}">{{ $diocese->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Provsti</label>
            <select id="provsti">
                @foreach ($deaneries as $deanery)
                    <option value="{{ $deanery->id }}">{{ $deanery->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Sogn</label>
            <select id="sogn">
                @foreach ($parishes as $parish)
                    <option value="{{ $parish->id }}">{{ $parish->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Kirke</label>
            <select id="kirke">
                @foreach ($churches as $church)
                    <option value="{{ $church->id }}">{{ $church->name }}</option>
                @endforeach
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
