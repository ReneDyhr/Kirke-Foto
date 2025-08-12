<div class="content">
    <div>
        <h2 class="text-xl font-semibold mb-4">Log ind</h2>

        @if ($errorMessage)
            <div class="mb-3 text-red-600">{{ $errorMessage }}</div>
        @endif

        <form wire:submit.prevent="login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="email">Email</label>
                <input wire:model.defer="email" id="email" type="email" class="w-full border rounded px-3 py-2"
                    required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="password">Adgangskode</label>
                <input wire:model.defer="password" id="password" type="password"
                    class="w-full border rounded px-3 py-2" required />
            </div>
            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" wire:model="remember" class="border rounded" />
                <span>Husk mig</span>
            </label>
            <div>
                <button type="submit" class="btn btn-primary">Log ind</button>
            </div>
        </form>
    </div>
</div>
