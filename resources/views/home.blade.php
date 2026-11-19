<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gestor de archivos')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.nav')

    <main>
        @include('partials.flash')
        @yield('content')
    </main>
</body>
</html>