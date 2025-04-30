<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
    @vite('resources/css/app.css') 
    @livewireStyles
</head>
<body>
    <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-100 via-pink-200 to-gray-300 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
            
            <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
                <div class="max-w-md mx-auto">
                    <div class="flex justify-center">
                        <img src="/logotipo.png" alt="LOOK TRENDY" class="h-40 w-auto mb-6"> 
                    </div>

                    <!-- <h1 class="text-2xl font-bold text-center mb-4 text-pink-600">¿Olvidaste tu contraseña?</h1> -->
                    <p class="text-sm text-gray-600 text-center mb-6">Te enviaremos un enlace para restablecer tu contraseña.</p>

                

                    <form wire:submit.prevent="send" class="space-y-6">
                        <div class="relative">
                            <input wire:model.lazy="email" id="email" name="email" type="email" 
                                class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-pink-500" 
                                placeholder="Correo electrónico" autocomplete="email">
                            <label for="email"
                                class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">
                                Correo electrónico
                            </label>
                            @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" 
                                class="bg-pink-600 text-white rounded-md px-4 py-2 hover:bg-gray-800 transition">
                                Enviar enlace
                            </button>
                        </div>
                    </form>

                    @if (session('status'))
                        <div class="mb-4 text-green-600 text-center text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="text-sm text-pink-600 hover:underline">
                            Volver al inicio de sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>

