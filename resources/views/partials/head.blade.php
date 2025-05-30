<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles