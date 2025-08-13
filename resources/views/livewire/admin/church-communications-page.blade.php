<div class="content">
    <div style="width: 100%;">
        <h1>Kommunikation - {{ $this->church->name }}</h1>

        <div class="card">
            <div class="card-body">
                <h2>Tilføj ny kommunikation</h2>
                <form wire:submit.prevent="addCommunication" class="form">
                    <div class="form-group">
                        <label for="subject">Emne</label>
                        <input id="subject" type="text" wire:model.defer="subject" class="input"
                            placeholder="Emne" />
                        @error('subject')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="message">Besked</label>
                        <textarea id="message" wire:model.defer="message" class="textarea" rows="5" placeholder="Skriv beskeden her..."></textarea>
                        @error('message')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sent_at">Sendt dato (valgfri)</label>
                        <input id="sent_at" type="datetime-local" wire:model.defer="sent_at" class="input" />
                        @error('sent_at')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Tilføj</button>
                </form>
            </div>
        </div>

        <div class="card" style="margin-top: 2rem;">
            <div class="card-body">
                <h2>Alle kommunikationer</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 25%">Emne</th>
                            <th style="width: 50%">Besked</th>
                            <th style="width: 15%">Sendt</th>
                            <th style="width: 10%">Handling</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($communications as $comm)
                            <tr>
                                <td>{{ $comm->subject }}</td>
                                <td style="white-space: pre-wrap;">{{ $comm->message }}</td>
                                <td>{{ optional($comm->sent_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <button wire:click="deleteCommunication({{ $comm->id }})" type="button"
                                        class="btn btn-danger" onclick="return confirm('Slet denne kommunikation?')"><i
                                            class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Ingen kommunikationer endnu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
