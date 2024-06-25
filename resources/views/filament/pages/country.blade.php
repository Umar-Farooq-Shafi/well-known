@php
    $state = $getState();

    $state = strtolower($state);

    $componentName = 'flag-4x3-' . $state;
@endphp

<x-icon name="{{ $componentName }}" class="inline-block h-8 w-8 rounded-md shadow-md" />
