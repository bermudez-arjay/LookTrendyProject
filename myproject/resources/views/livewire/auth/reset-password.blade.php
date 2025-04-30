<div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
            <div class="max-w-md mx-auto">
                <div class="flex justify-center">
                    <img src="/logotipo.png" alt="LOOK TRENDY" class="h-40 w-auto mb-6">
                </div>

                <h1 class="text-2xl font-semibold text-center mb-6 text-pink-600">Restablecer contraseña</h1>

                <form wire:submit.prevent="resetPassword" class="space-y-6">
                    <input type="hidden" wire:model="token">

                    <div class="relative">
                        <input type="email" wire:model="email" disabled
                               class="w-full bg-gray-100 border rounded p-2 cursor-not-allowed">
                    </div>

                    <div class="relative">
                        <input wire:model.lazy="password" type="password"
                            placeholder="Nueva contraseña"
                            class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 focus:outline-none focus:border-pink-500" />
                        <label
                            class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-gray-600">
                            Nueva contraseña
                        </label>
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="relative">
                        <input wire:model.lazy="password_confirmation" type="password"
                            placeholder="Confirmar contraseña"
                            class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 focus:outline-none focus:border-pink-500" />
                        <label
                            class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-gray-600">
                            Confirmar contraseña
                        </label>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit"
                                class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                            Restablecer contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
