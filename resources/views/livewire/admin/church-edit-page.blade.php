<div class="content">
    <div>
        <h1>Rediger kirke - {{ $church->name }}</h1>

        <form wire:submit.prevent="save" class="form">
            <div class="form-group">
                <label for="seo_description">SEO Beskrivelse</label>
                <input id="seo_description" type="text" wire:model.defer="seo_description" class="form-control"
                    placeholder="Kort beskrivelse" />
            </div>

            <div class="form-group">
                <label for="seo_tags">SEO Tags</label>
                <input id="seo_tags" type="text" wire:model.defer="seo_tags" class="form-control"
                    placeholder="komma-separeret tags" />
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" wire:model.defer="drone_approval" />
                    <span>Drone godkendt</span>
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" wire:model.defer="open_area" />
                    <span>Åbent område</span>
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" wire:model.defer="contact_later" />
                    <span>Kontakt senere</span>
                </label>
            </div>

            <div class="actions">
                <a href="/admin/" class="btn btn-secondary">Annuller</a>
                <button type="submit" class="btn btn-default">Gem</button>
            </div>
        </form>
    </div>

    <style>
        .form {
            max-width: 720px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        .checkbox {
            display: flex;
            gap: 10px;
            align-items: center;
            font-weight: 500;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-default {
            background: #1f2937;
            color: #fff;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }
    </style>
</div>
