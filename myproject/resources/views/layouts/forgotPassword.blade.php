<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    {{-- Vite para Tailwind CSS --}}
    @vite('resources/css/app.css')

    @livewireStyles  
</head>
<body class="bg-gray-100">
    {{ $slot }}     
    @livewireScripts 
    @stack('scripts')

</body>
</html>
