<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen font-sans antialiased bg-gradient-to-r from-primary to-secondary">

    {{ $slot }}
                
    <x-toast />  
    <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
    @livewireScripts
    </body>
</html>
