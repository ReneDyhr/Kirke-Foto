<div class="content admin-images">
    <div>
        <h1>Billeder - {{ $church->name }}</h1>

        <style>
            .admin-images .toolbar {
                display: flex;
                gap: 12px;
                align-items: center;
                margin: 10px 0 16px;
            }

            .admin-images .images-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0 8px;
            }

            .admin-images .images-table thead th {
                text-align: left;
                font-weight: 600;
                color: #555;
                padding: 8px 12px;
            }

            .admin-images .images-table tbody td {
                background: #fff;
                padding: 10px 12px;
                vertical-align: middle;
            }

            .admin-images .thumb {
                width: 110px;
                height: 80px;
                object-fit: cover;
                border-radius: 6px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
            }

            .admin-images .drag-handle {
                cursor: grab;
                color: #9aa1a9;
            }

            .admin-images .name {
                font-weight: 500;
                color: #333;
            }

            .admin-images .muted {
                color: #777;
                font-size: 12px;
            }

            .admin-images .actions {
                display: flex;
                gap: 8px;
                justify-content: flex-end;
            }

            .admin-images .btn-icon {
                border: 1px solid #e5e7eb;
                background: #fff;
                color: #374151;
                border-radius: 6px;
                padding: 6px 8px;
                cursor: pointer;
            }

            .admin-images .btn-icon:hover {
                background: #f3f4f6;
            }

            .admin-images tr.drag-over td {
                outline: 2px dashed #BDA79E;
                outline-offset: -6px;
            }

            @media (max-width: 640px) {
                .admin-images .thumb {
                    width: 86px;
                    height: 64px;
                }
            }
        </style>

        <div class="toolbar">
            <form wire:submit.prevent="storeImages" enctype="multipart/form-data" class="flex items-center gap-3">
                <input type="file" wire:key="photos-input-{{ $church->id }}" wire:model="photos" name="photos"
                    multiple accept=".jpg,.jpeg,image/jpeg" />
                <button type="submit" class="btn btn-default">Upload</button>
                <div wire:loading wire:target="photos" class="muted">Uploader...</div>
            </form>
            @error('photos.*')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div x-data="{
            images: [],
            draggingId: null,
            dragOverId: null,
            init() {
                const raw = this.$el.querySelector('table').getAttribute('data-images') || '[]';
                try { this.images = JSON.parse(raw); } catch (e) { this.images = []; }
                window.addEventListener('images-updated', (e) => {
                    if (e?.detail?.images) this.images = e.detail.images;
                });
            },
            wire() { return Livewire.find(this.$root.closest('[wire\\:id]').getAttribute('wire:id')); },
            dragStart(e, id) {
                this.draggingId = id;
                e.dataTransfer.effectAllowed = 'move';
            },
            dragEnter(id) { this.dragOverId = id; },
            dragLeave() { this.dragOverId = null; },
            drop(id) {
                if (this.draggingId === null || id === this.draggingId) { this.dragOverId = null; return; }
                const fromIndex = this.images.findIndex(i => i.id === this.draggingId);
                const toIndex = this.images.findIndex(i => i.id === id);
                const [moved] = this.images.splice(fromIndex, 1);
                this.images.splice(toIndex, 0, moved);
                this.dragOverId = null;
                this.draggingId = null;
                this.wire().sortImages(this.images.map(i => i.id));
            },
            del(id) {
                if (!confirm('Slet billede?')) return;
                this.wire().deleteImage(id);
                this.images = this.images.filter(i => i.id !== id);
            }
        }">
            <table class="images-table" data-images='@json($images->map(fn($i) => ['id' => $i->id, 'path' => $i->path, 'name' => $i->name])->values())'>
                <thead>
                    <tr>
                        <th style="width:36px;"></th>
                        <th style="width:130px;">Billede</th>
                        <th>Filnavn</th>
                        <th style="width:120px; text-align:right;">Handlinger</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="img in images" :key="img.id">
                        <tr :class="{ 'drag-over': dragOverId === img.id }" draggable="true"
                            @dragstart="dragStart($event, img.id)" @dragenter.prevent="dragEnter(img.id)"
                            @dragover.prevent @dragleave="dragLeave()" @drop.prevent="drop(img.id)">
                            <td><span class="drag-handle"><i class="fa fa-grip-vertical"></i></span></td>
                            <td><img class="thumb" :src="'/images/church/thumb_' + img.path" :alt="img.name ?? ''">
                            </td>
                            <td>
                                <div class="muted" x-text="img.path"></div>
                            </td>
                            <td class="actions">
                                <button class="btn-icon" @click.prevent="del(img.id)"><i
                                        class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
