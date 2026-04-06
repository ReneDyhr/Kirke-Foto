<div class="content">
    <div style="width: 100%;">
        <h1 class="outreach-mail-page__title">Mail-skabelon</h1>

        <div class="card">
            <div class="card-body">
                <div class="outreach-mail-pick">
                    <h2 class="outreach-mail-pick__title">Vælg kirker</h2>
                    <p class="outreach-mail-pick__lead">Vælg et sogn for at se lokale kirker, eller søg på tværs af hele
                        landet. Du kan kombinere begge dele.</p>

                    <div class="outreach-mail-pick__panel">
                        <div class="outreach-mail-pick__section">
                            <label class="outreach-mail-pick__label" for="parish_picker">Sogn</label>
                            <p class="outreach-mail-pick__hint">Viser kirker, der hører til det valgte sogn.</p>
                            <select id="parish_picker" wire:model.live="parishPickerId" class="form-control">
                                <option value="">— Vælg sogn —</option>
                                @foreach ($parishes as $parish)
                                    <option value="{{ $parish->id }}">{{ $parish->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($parishChurches->isNotEmpty())
                            <div class="outreach-mail-pick__section">
                                <fieldset class="outreach-mail-pick__fieldset">
                                    <legend class="outreach-mail-pick__subsection-title">Kirker i sognet</legend>
                                    <ul class="outreach-mail-pick__list">
                                        @foreach ($parishChurches as $church)
                                            @php
                                                $parishChurchId = (int) $church->id;
                                                $parishChurchSelected = in_array(
                                                    $parishChurchId,
                                                    $selectedChurchIds,
                                                    true,
                                                );
                                            @endphp
                                            <li wire:key="parish-church-row-{{ $church->id }}">
                                                <div class="outreach-mail-pick__row">
                                                    <span
                                                        class="outreach-mail-pick__church-title">{{ $church->name }}</span>
                                                    @if ($parishChurchSelected)
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            style="flex-shrink: 0;"
                                                            wire:click="removeChurch({{ $church->id }})">Fjern</button>
                                                    @else
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            style="flex-shrink: 0;"
                                                            wire:click="addChurch({{ $church->id }})">Tilføj</button>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </fieldset>
                            </div>
                        @endif

                        <div class="outreach-mail-pick__section">
                            <label class="outreach-mail-pick__label" for="church_search">Søg kirke</label>
                            <p class="outreach-mail-pick__hint">Tilføj kirker på tværs af sogn — mindst to tegn.
                                Resultatet
                                viser sogn og provsti.</p>
                            <input id="church_search" type="search" wire:model.live.debounce.300ms="churchSearch"
                                class="form-control" placeholder="Mindst 2 tegn…" autocomplete="off" />
                        </div>

                        @if ($searchResults->isNotEmpty())
                            <div class="outreach-mail-pick__section">
                                <p
                                    class="outreach-mail-pick__subsection-title outreach-mail-pick__subsection-title--tight">
                                    Søgeresultater</p>
                                <ul class="outreach-mail-pick__list outreach-mail-pick__list--flush">
                                    @foreach ($searchResults as $church)
                                        <li wire:key="search-church-{{ $church->id }}">
                                            <div class="outreach-mail-pick__row outreach-mail-pick__row--top">
                                                <div>
                                                    <div class="outreach-mail-pick__church-title">{{ $church->name }}
                                                    </div>
                                                    <div class="outreach-mail-pick__meta">
                                                        Sogn: {{ $church->parish->name ?? '—' }}
                                                        @if ($church->parish?->deanery)
                                                            <span aria-hidden="true"> · </span>Provsti:
                                                            {{ $church->parish->deanery->name }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    style="flex-shrink: 0;"
                                                    wire:click="addChurch({{ $church->id }})">Tilføj</button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="outreach-mail-pick__section">
                            <label class="outreach-mail-pick__label">Valgte kirker</label>
                            @if ($selectedChurches->isEmpty())
                                <p class="outreach-mail-pick__empty">Ingen kirker valgt endnu — vælg fra sogn eller
                                    søgning.
                                </p>
                            @else
                                <ul class="outreach-mail-pick__list outreach-mail-pick__list--selected">
                                    @foreach ($selectedChurches as $church)
                                        <li wire:key="selected-church-{{ $church->id }}">
                                            <div class="outreach-mail-pick__row">
                                                <span
                                                    class="outreach-mail-pick__church-title">{{ $church->name }}</span>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    style="flex-shrink: 0;"
                                                    wire:click="removeChurch({{ $church->id }})"
                                                    aria-label="Fjern {{ $church->name }}"><i class="fa fa-times"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="outreach-mail-pick__section">
                            <label class="outreach-mail-pick__label" for="recipient_first">Fornavn til hilsen
                                (valgfrit)</label>
                            <p class="outreach-mail-pick__hint">Tomt felt giver «Hej,» — udfyldt giver «Hej fornavn,».
                            </p>
                            <input id="recipient_first" type="text"
                                wire:model.live.debounce.300ms="recipientFirstName" class="form-control"
                                placeholder="Tom = «Hej,»" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 2rem;">
            <div class="card-body">
                <div class="outreach-mail-preview" x-data>
                    <h2 class="outreach-mail-preview__title">Genereret mail</h2>
                    <p class="outreach-mail-preview__lead">Kopier ind i dit mailprogram. Teksten opdateres automatisk,
                        når du ændrer kirker eller hilsen.</p>

                    <div class="outreach-mail-preview__panel">
                        <div class="outreach-mail-preview__block">
                            <div class="outreach-mail-preview__label-row">
                                <label for="generated_subject" class="outreach-mail-preview__label">Emne</label>
                                <button type="button" class="btn btn-secondary btn-sm"
                                    aria-label="Kopier emne til udklipsholder"
                                    @click="navigator.clipboard.writeText(document.getElementById('generated_subject').value)">
                                    <i class="fa fa-copy" aria-hidden="true"></i> Kopier emne
                                </button>
                            </div>
                            <textarea id="generated_subject" class="form-control outreach-mail-preview__textarea" rows="2" readonly>{{ $generatedSubject }}</textarea>
                        </div>

                        <div class="outreach-mail-preview__block">
                            <div class="outreach-mail-preview__label-row">
                                <label for="generated_body" class="outreach-mail-preview__label">Besked</label>
                                <button type="button" class="btn btn-secondary btn-sm"
                                    aria-label="Kopier besked til udklipsholder"
                                    @click="navigator.clipboard.writeText(document.getElementById('generated_body').value)">
                                    <i class="fa fa-copy" aria-hidden="true"></i> Kopier besked
                                </button>
                            </div>
                            <textarea id="generated_body"
                                class="form-control outreach-mail-preview__textarea outreach-mail-preview__textarea--body" rows="14"
                                readonly>{{ $generatedBody }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 2rem;">
            <div class="card-body">
                <div class="outreach-mail-register">
                    <h2 class="outreach-mail-register__title">Registrer kommunikation</h2>

                    <div class="outreach-mail-register__panel">
                        <p class="outreach-mail-register__lead">Gemmer én loglinje pr. valgt kirke (som på
                            kommunikationssiden). Brug knappen, når mailen er sendt.</p>

                        <div class="form-group">
                            <label for="sent_to">Sendt til (valgfrit)</label>
                            <input id="sent_to" type="text" wire:model="sentTo" class="form-control"
                                placeholder="fx 9137@SOGN.DK — tom = fuld beskedtekst gemmes" />
                        </div>

                        <button type="button" class="btn btn-primary" wire:click="registerCommunication"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="registerCommunication"><i class="fa fa-inbox"></i>
                                Registrer kommunikation</span>
                            <span wire:loading wire:target="registerCommunication">Gemmer…</span>
                        </button>

                        @error('selectedChurchIds')
                            <div class="error" style="margin-top: 0.75rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
