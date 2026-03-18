<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Projeto')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="navbar">
        <div class="navbar-container">
            <a href="{{ route('pedidos.index') }}" class="navbar-brand">
                PDF Transformer
            </a>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
