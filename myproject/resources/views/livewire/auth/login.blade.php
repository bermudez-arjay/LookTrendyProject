<div>
    <!doctype html>
    <html>
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        @vite('resources/css/app.css')
        <style>
     .fashion-bg {
      background-image: url('https://images.unsplash.com/photo-1483985988355-763728e1935b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
      background-size: cover;
      background-position: center;
      background-blend-mode: overlay;
      background-color: rgba(255, 255, 255, 0.85);
    }
        </style>
      </head>
      <body>
        <div class="min-h-screen bg-gray-100 py-6 flex flex-col fashion-bg px-6 text-center justify-center sm:py-12">
            <div class="relative py-3 sm:max-w-xl sm:mx-auto">
                <div class="absolute inset-0 bg-gradient-to-r from-gray-100 via-pink-200 to-gray-300 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
                </div>
        
                <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
                    <div class="max-w-md mx-auto">
                        <div class="flex justify-center">
                            <img src="/logotipo.png" alt="LOOK TRENDY" class="h-49 w-auto mb-6"> 
                        </div>
                        {{-- Mensaje de error --}}
                        @if (session()->has('error'))
                            <div class="text-red-500 text-sm mt-2">
                                {{ session('error') }}
                            </div>
                        @endif
        
                        <div class="divide-y divide-gray-200">
                            <form wire:submit.prevent="login" class="py-8 text-base leading-6 space-y-6 text-gray-700 sm:text-lg sm:leading-7">
                                {{-- Email --}}
                                <div class="relative">
                                    <input wire:model.lazy="User_Email" id="User_Email" name="User_Email" type="text"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-pink-500"                                        placeholder="Email address" autocomplete="off" />
                                    <label for="User_Email"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">                                        Email Address
                                    </label>
                                    @error('User_Email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
        
                                {{-- Password --}}
                                <div class="relative">
                                    <input wire:model.lazy="Password" id="Password" name="Password" type="password"
                                    class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-pink-500"                                        placeholder="Password" autocomplete="current-password" />
                                    <label for="Password"
                                    class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">                                        Password
                                    </label>
                                    @error('Password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                {{-- Botón de login --}}
                                <div class="relative flex items-center justify-center">
                                    <button type="submit"
                                    class="bg-pink-600 text-white rounded-md px-4 py-2 hover:bg-gray-800 transition">                             
                                       Iniciar Sesión
                                    </button>
                                </div>
        
                                {{-- Enlace a recuperar contraseña --}}
                                <div class="relative flex items-center justify-center">
                                <a href="{{ route('recuperar') }}" class="text-pink-600 hover:underline text-sm">¿Olvidaste tu contraseña?</a>
                                </div>
                            </form>
                            <div class="mt-8 text-center">
                                <p class="text-xs text-gray-500">
                                    © 2025 LOOKTRENDY. Todos los derechos reservados.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </body>
    </html>
</div>
