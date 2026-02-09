@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="CrowPOS" {{ $attributes }}>
        <img src="/images/crowPOS.png" alt="CrowPOS Logo" class="h-8 w-auto">
    </flux:sidebar.brand>
@else
    <flux:brand name="CrowPOS" {{ $attributes }}>
        <img src="/images/crowPOS.png" alt="CrowPOS Logo" class="h-8 w-auto">
    </flux:brand>
@endif
