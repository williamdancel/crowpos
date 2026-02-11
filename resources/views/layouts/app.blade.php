<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
        <footer class="text-xs text-center">
                Powered by {{config('app.name')}} © {{ date('Y') }}
        </footer>
    </flux:main>
</x-layouts::app.sidebar>

