<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Aafno Ticket' }}</title>
    @stack('metas')

    @stack('styles')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <wireui:scripts />
    @vite('resources/css/app.css')
    @livewireStyles

    @stack('styles')
</head>
<body class="bg-sky-50">
<x-notifications />

@include('components.layouts.nav')

{{ $slot }}

@include('components.layouts.footer')

@livewireScriptConfig
@livewire('slide-over-panel')
@vite(['resources/js/app.js'])
@stack('scripts')

</body>
</html>
