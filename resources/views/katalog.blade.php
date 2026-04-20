<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Katalog Buku')</title>
    <link rel="stylesheet" href="{{ asset('css/katalog.css') }}">
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
