@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="{{config('app.business_name')}}" {{ $attributes }}>
        <img src="{{config('app.business_logo')}}" alt="CrowPOS Logo" class="h-8 w-auto">
    </flux:sidebar.brand>
@else
    <flux:brand name="{{config('app.business_name')}}" {{ $attributes }}>
        <img src="{{config('app.business_logo')}}" alt="CrowPOS Logo" class="h-8 w-auto">
    </flux:brand>
@endif
