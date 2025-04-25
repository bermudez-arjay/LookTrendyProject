<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
  </head>
  <body>
    <h1 class="text-3xl font-bold ">
      Bienevenido a Looktrendy
      <a href="{{ route('login') }}" class="flex items-center p-2 rounded-lg hover:bg-blue-700" wire:navigate>
        <i class="fas fa-home text-xl"></i>
        <span id="menu-text-1" class="ml-4 duration-200">Login</span>
    </a>
    </h1>
      </body>
</html>