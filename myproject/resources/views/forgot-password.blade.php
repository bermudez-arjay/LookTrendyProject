<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100">
    @livewire('auth.forgot-password') 
    @livewireScripts
</body>
</html>
