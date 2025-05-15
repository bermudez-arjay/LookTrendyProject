<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Looktrendy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <style>
  @keyframes fadeZoomIn {
    from {
      opacity: 0;
      transform: scale(0.9);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  .animate-fade-zoom {
    animation: fadeZoomIn 0.8s ease-out forwards;
  }
</style>

<style>
  @keyframes float {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-8px);
    }
  }

  .floating {
    animation: float 2.5s ease-in-out infinite;
  }

  .btn-zoom:hover {
    transform: scale(1.05);
  }

  .btn-zoom {
    transition: transform 0.3s ease;
  }
</style>

</head>
<body class="bg-white text-gray-900 font-sans">

  <header class="flex items-center justify-between px-8 py-6 shadow-md sticky top-0 bg-white z-10">
  <div class="flex items-center space-x-3">
  <img src="/logotipo-transparente.png"
      alt="Logo Looktrendy"
      class="h-16 shadow-md transition-all duration-300 ease-in-out hover:scale-110 hover:shadow-[0_0_20px_#ec008c] bg-white rounded-full p-1">

  <span class="text-2xl font-bold text-pink-600 animate-fade-zoom">
    LookTrendy
  </span>
  </div>

    <nav class="space-x-6 hidden md:block">
      <a href="#" class="text-gray-600 hover:text-fuchsia-600">Tu estilo, tu actitud. Viste Look Trendy</a>
    </nav>
  </header>

  <section class="text-center px-6 py-24 bg-gradient-to-br from-fuchsia-100 to-teal-100">
    <h1 class="text-5xl font-extrabold mb-4 text-gray-900 animate__animated animate__fadeInUp">Bienvenido a Looktrendy</h1>
    <p class="text-lg mb-6 max-w-xl mx-auto text-gray-700">Tienda Look Trendy es una  tienda de mercadería en general. Se dedica a ofrecer 
        mercadería variada de calidad y atención personalizada a sus clientes.</p>
    <div class="space-x-4">
    <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-pink-600 text-white rounded-lg text-lg font-medium hover:bg-pink-700">Login</a>
    </div>
  </section>

  <section class="py-20 px-6">
    <div class="flex justify-center items-center my-12">
  <a href="#" download class="flex items-center space-x-4 hover:bg-gray-100 p-4 rounded-lg transition">
    <div class="text-4xl floating">
      📖
    </div>
    <div>
      <h2 class="text-xl font-bold">Manual de usuario</h2>
      <p class="text-gray-600">PDF que proporciona instrucciones y guías sobre cómo se<br>
          utiliza el sistema de manera efectiva.</p>
    </div>
  </a>
</div>
  </section>

  <section class="py-20 text-white text-center" style="background: linear-gradient(to right, #eec0c6, #957dad);">
    <h2 class="text-3xl md:text-4xl font-extrabold mb-4">¿Listo para ver nuestra tienda?</h2>
  </section>

  <footer class="text-center text-sm py-6 text-gray-500 bg-gray-50">
    © 2025 Looktrendy. Todos los derechos reservados.
  </footer>

</body>
</html>