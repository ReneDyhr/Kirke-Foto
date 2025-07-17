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
            <select id="stift" wire:model.live="selectedDiocese">
                @foreach ($dioceses as $id => $name)
                    <option value="{{ $id }}" @if ($id == $selectedDiocese) selected @endif>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Provsti</label>
            <select id="provsti" wire:model.live="selectedDeanery">
                @foreach ($deaneries as $id => $name)
                    <option value="{{ $id }}" @if ($id == $selectedDeanery) selected @endif>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Sogn</label>
            <select id="sogn" wire:model.live="selectedParish">
                @foreach ($parishes as $id => $name)
                    <option value="{{ $id }}" @if ($id == $selectedParish) selected @endif>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Kirke</label>
            <select id="kirke" wire:model.live="selectedChurch">
                @foreach ($churches as $id => $name)
                    <option value="{{ $id }}" @if ($id == $selectedChurch) selected @endif>
                        {{ $name }}</option>
                @endforeach
            </select>
        </div>
        <pre class="bg-gray-100 p-2 rounded text-xs">
{{ json_encode(
    [
        'diocese' => $selectedDiocese,
        'deanery' => $selectedDeanery,
        'parish' => $selectedParish,
        'church' => $selectedChurch,
    ],
    JSON_PRETTY_PRINT,
) }}
    </pre>
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
@script
    <script>
        console.log('HEJ');
        document.addEventListener('livewire:load', function() {
            // Initialize Nice Select2
            console.log('HERE');
            niceSelect2.init();
        });

        Livewire.on('select2Updated', () => {
            niceSelect2.update();
        });
    </script>
@endscript
