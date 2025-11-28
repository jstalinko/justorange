<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <form wire:submit.prevent="updateStats">
                {{ $this->form }}
            </form>

            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($this->getStats() as $stat)
                    <div class="rounded-lg border p-4 bg-white dark:bg-gray-800">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $stat['label'] }}
                        </div>
                        <div class="mt-2 text-2xl font-bold text-{{ $stat['color'] }}-600">
                            {{ $stat['value'] }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            {{ $stat['description'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>