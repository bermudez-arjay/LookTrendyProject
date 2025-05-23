<div>
<div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center fashion-bg px-6 sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
            <div class="max-w-md mx-auto">
                <div class="flex justify-center">
                    <img src="/logotipo.png" alt="LOOK TRENDY" class="h-40 w-auto mb-6">
                </div>

                <h1 class="text-2xl font-semibold text-center mb-6 text-pink-600">Restablecer contraseña</h1>

                @if (session()->has('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="resetPassword" class="space-y-6">
                    <input type="hidden" wire:model="token">
                    <div class="relative">
                        <input type="email" wire:model="User_Email" disabled class="w-full bg-gray-100 border rounded p-2 cursor-not-allowed">
                        @error('User_Email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="relative">
                        <input id="password" wire:model="password" type="password" placeholder="Nueva contraseña" class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 focus:outline-none focus:border-pink-500" />
                        <label for="password" class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-gray-600">
                            Nueva contraseña
                        </label>
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="relative">
                        <input id="password_confirmation" wire:model="password_confirmation" type="password" placeholder="Confirmar contraseña" class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 focus:outline-none focus:border-pink-500" />
                        <label for="password_confirmation" class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-gray-600">
                            Confirmar contraseña
                        </label>
                        @error('password_confirmation') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                            Restablecer contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .fashion-bg {
      background-image: url('https://images.unsplash.com/photo-1483985988355-763728e1935b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
      background-size: cover;
      background-position: center;
      background-blend-mode: overlay;
      background-color: rgba(255, 255, 255, 0.85);
    }
</style>
</div>