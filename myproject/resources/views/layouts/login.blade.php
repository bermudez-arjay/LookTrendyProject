<!DOCTYPE html>
<html>
<head>
    <title>Mi App</title>
    @livewireStyles  <!-- CSS de Livewire -->
</head>
<body>
    {{ $slot }}     <!-- Aquí se renderizarán los componentes -->
    @livewireScripts <!-- JS de Livewire -->
</body>
</html>