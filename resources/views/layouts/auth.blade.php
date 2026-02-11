<x-layouts::auth.simple :title="$title ?? null">
    {{ $slot }}
    <div class=" text-center text-xs">
        Powered by {{config('app.name')}} © {{ date('Y') }}
    </div>
</x-layouts::auth.simple>
