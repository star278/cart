<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Магазин</title>
</head>
<body>
@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@yield('content')
</body>
</html>
