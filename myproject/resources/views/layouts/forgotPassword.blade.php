<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Vite para Tailwind CSS --}}
    @vite('resources/css/app.css')

    @livewireStyles  
</head>
<body class="bg-gray-100">
    {{ $slot }}     
    @livewireScripts 
</body>
</html>
