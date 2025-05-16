<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LookTrendy - Moda y Estilo</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-8px);
      }
    }

    @keyframes slideText {
      0% {
        transform: translateX(-10px);
        opacity: 0;
      }
      100% {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes colorChange {
      0% { color: #ec4899; }
      25% { color: #a855f7; }
      50% { color: #6366f1; }
      75% { color: #14b8a6; }
      100% { color: #ec4899; }
    }

    .animate-fade-zoom {
      animation: fadeZoomIn 0.8s ease-out forwards;
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

    .slogan-animated {
      animation: slideText 1s ease-out, colorChange 8s infinite;
      display: inline-block;
    }

    .fashion-bg {
      background-image: url('https://images.unsplash.com/photo-1483985988355-763728e1935b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
      background-size: cover;
      background-position: center;
      background-blend-mode: overlay;
      background-color: rgba(255, 255, 255, 0.85);
    }

    .clothing-card {
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .clothing-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .gradient-text {
      background: linear-gradient(45deg, #ec4899, #8b5cf6, #3b82f6);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
  </style>
</head>
<body class="bg-white text-gray-900 font-sans">

  <!-- Header con menú de moda -->
  <header class="flex items-center justify-between px-8 py-6 shadow-md sticky top-0 bg-white z-50">
    <div class="flex items-center space-x-3">
      <img src="/logotipo-transparente.png"
          alt="Logo LookTrendy"
          class="h-16 shadow-md transition-all duration-300 ease-in-out hover:scale-110 hover:shadow-[0_0_20px_#ec008c] bg-white rounded-full p-1">

      <span class="text-2xl font-bold gradient-text animate-fade-zoom">
        LookTrendy
      </span>
    </div>

    <nav class="flex items-center space-x-8">
      <div class="hidden md:block">
        <a href="#" class="text-xl slogan-animated font-medium">Tu estilo, tu actitud. Viste Look Trendy</a>
      </div>
      <div class="flex space-x-4">
        <a href="#" class="text-gray-600 hover:text-fuchsia-600"><i class="fas fa-search"></i></a>
        <a href="#" class="text-gray-600 hover:text-fuchsia-600"><i class="fas fa-user"></i></a>
        <a href="#" class="text-gray-600 hover:text-fuchsia-600"><i class="fas fa-shopping-bag"></i></a>
      </div>
    </nav>
  </header>

  <!-- Hero section con modelo de ropa -->
  <section class="fashion-bg px-6 py-32 text-center">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-5xl md:text-6xl font-extrabold mb-6 text-gray-900 animate__animated animate__fadeInUp">
        Descubre tu <span class="gradient-text">estilo único</span>
      </h1>
      
      <div class="space-x-4">
        <a href="{{ route('login') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-full text-lg font-medium hover:from-pink-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
          Inicia Sesión
        </a>
      </div>
    </div>
  </section>
  <!-- Manual de usuario -->
  <section class="py-16 px-6 bg-white">
    <div class="flex justify-center items-center my-8 max-w-4xl mx-auto bg-gradient-to-r from-pink-50 to-purple-50 rounded-xl p-8">
      <a href="#" download class="flex items-center space-x-6 hover:bg-white p-6 rounded-lg transition-all duration-300">
        <div class="text-5xl floating text-pink-500">
          <i class="fas fa-book-open"></i>
        </div>
        <div>
          <h2 class="text-2xl font-bold mb-2 gradient-text">Manual de usuario</h2>
          <p class="text-gray-600">PDF que proporciona instrucciones y guías sobre cómo se<br>
              utiliza el sistema de manera efectiva.</p>
        </div>
      </a>
    </div>
  </section>
  <!-- Footer -->
  <footer class="bg-gray-900 text-white justify-center py-12 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
      <div>
        <h3 class="text-xl font-bold mb-4 gradient-text">LookTrendy</h3>
        <p class="text-gray-400">Tu estilo, tu actitud. Viste Look Trendy</p>
      </div>
      <div>
        <h4 class="font-semibold mb-4">Contacto</h4>
        <ul class="space-y-2 text-gray-400">
          <li><i class="fas fa-map-marker-alt mr-2"></i> Av. Moda 123</li>
          <li><i class="fas fa-phone mr-2"></i> +123 456 789</li>
          <li><i class="fas fa-envelope mr-2"></i> info@looktrendy.com</li>
        </ul>
      </div>
      <div>
        <h4 class="font-semibold mb-4">Redes Sociales</h4>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-pink-500 text-xl"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-gray-400 hover:text-pink-500 text-xl"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-gray-400 hover:text-pink-500 text-xl"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-gray-400 hover:text-pink-500 text-xl"><i class="fab fa-pinterest-p"></i></a>
        </div>
      </div>
    </div>
    <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
      © 2025 LookTrendy. Todos los derechos reservados.
    </div>
  </footer>
</body>
</html>