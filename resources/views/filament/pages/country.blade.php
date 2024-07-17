@php
    $state = $getState();

    $state = strtolower($state);

    $componentName = 'flag-4x3-' . $state;
@endphp

<x-dynamic-component :component="$componentName" class="inline-block h-8 w-8 rounded-md shadow-md" />
