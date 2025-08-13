<div class="content">
    <div>
        <h1>Billeder - {{ $church->name }}</h1>

        <div class="mb-6">
            <form wire:submit.prevent="storeImages" class="flex items-center gap-3" enctype="multipart/form-data">
                <input type="file"
                       wire:key="photos-input-{{ $church->id }}"
                       wire:model="photos"
                       name="photos"
                       multiple
                       accept=".jpg,.jpeg,image/jpeg" />
                <button type="submit" class="btn btn-default">Upload</button>
                <div wire:loading wire:target="photos">Uploader...</div>
                @error('photos.*') <span class="text-red-600">{{ $message }}</span> @enderror
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4"
             x-data="{ draggingId: null, images: [], init() { const raw = this.$el.getAttribute('data-images') || '[]'; try { this.images = JSON.parse(raw); } catch (e) { this.images = []; } window.addEventListener('images-updated', (e) => { if (e?.detail?.images) this.images = e.detail.images; }); }, dragStart(e, id) { this.draggingId = id; e.dataTransfer.effectAllowed = 'move'; }, drop(e, overId) { if (this.draggingId === null || this.draggingId === overId) return; const fromIndex = this.images.findIndex(i => i.id === this.draggingId); const toIndex = this.images.findIndex(i => i.id === overId); const [moved] = this.images.splice(fromIndex, 1); this.images.splice(toIndex, 0, moved); const orderedIds = this.images.map(i => i.id); Livewire.find(this.$root.closest('[wire\\:id]').getAttribute('wire:id')).sortImages(orderedIds); }, deleteImage(id) { if (!confirm('Slet billede?')) return; Livewire.find(this.$root.closest('[wire\\:id]').getAttribute('wire:id')).deleteImage(id); this.images = this.images.filter(i => i.id !== id); } }"
             data-images='@json($images->map(fn($i) => ["id" => $i->id, "path" => $i->path, "name" => $i->name])->values())'>
            <template x-for="img in images" :key="img.id">
                <div class="relative group" draggable="true" @dragstart="dragStart($event, img.id)"
                    @dragover.prevent @drop="drop($event, img.id)">
                    <img class="w-full h-40 object-cover rounded" :src="'/images/church/thumb_' + img.path" :alt="img.name ?? ''" />
                    <button class="absolute top-2 right-2 bg-white/80 hover:bg-red-600 hover:text-white rounded p-1"
                        @click.prevent="deleteImage(img.id)"><i class="fa fa-trash"></i></button>
                </div>
            </template>
        </div>
    </div>
</div>
