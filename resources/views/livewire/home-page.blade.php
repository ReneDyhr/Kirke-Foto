<div class="content">
    <div class="sidebar-main">
        <h1>Velkommen til Kirke-Foto</h1>
        <p>Formålet med denne hjemmeside er, at dokumentere projektet: "Billedlig bevaring af de danske kirker".</p>
        <p>Billederne er originale og er til fri afbenyttelse, såfremt Kirke-Foto.dk er angivet som kilde.</p>
        <p style="margin-bottom: 0px;">Der er i skrivende stund <b>{{ $this->totalChurches }}</b> kirker oprettet med
            billeder henover <b>{{ $this->totalDeaneries }}</b>
            provstier.</p>
        <p>Der er i alt <b>{{ $this->totalDroneAccepted }}</b> kirker med dronetilladelse fra tilhørende menighedsråd.
        </p>
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
        @if ($selectedChurch)
            <a class="" href="/kirke/{{ $selectedChurchModel->parish->url }}/{{ $selectedChurchModel->url }}">
                <button class="btn btn-primary">Gå til
                    {{ $selectedChurchModel->name }}&nbsp;&nbsp;
                    <i class="fa fa-chevron-right"></i>
                </button>
                <br>
                <br>
            </a>
        @endif
    </div>
    <div class="sidebar">
        <div class="card ">
            <div class="card-body">
                <h5 class="card-title">Seneste opdaterede</h5>
                <div class="card-text">
                    <ul class="latest-churches">
                        @foreach ($latestChurches as $church)
                            <li>
                                <a class="" href="/kirke/{{ $church['parish']['url'] }}/{{ $church['url'] }}">
                                    {{ $church['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@script
    <script>
        document.addEventListener('livewire:load', function() {
            // Initialize Nice Select2
            niceSelect2.init();
        });

        Livewire.on('select2Updated', () => {
            niceSelect2.update();
        });
    </script>
@endscript
