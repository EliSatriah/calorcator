<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lengkapi Biodata Anda') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-soft min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border-t-4 border-primary-500">
                <div class="p-8 text-gray-900">
                    <p class="mb-6 text-gray-600 text-center">Untuk menghitung kebutuhan kalori yang tepat, kami memerlukan sedikit informasi tentang Anda.</p>
                    
                    <form method="POST" action="{{ route('bio.store') }}" class="max-w-lg mx-auto">
                        @csrf
                        
                        <!-- Umur -->
                        <div class="mb-5">
                            <x-input-label for="age" :value="__('Umur (Tahun)')" />
                            <x-text-input id="age" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" type="number" name="age" :value="old('age')" required autofocus />
                            <x-input-error :messages="$errors->get('age')" class="mt-2" />
                        </div>

                        <!-- Gender -->
                        <div class="mb-5">
                            <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                            <select id="gender" name="gender" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm" required>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <!-- Berat -->
                        <div class="mb-5">
                            <x-input-label for="weight" :value="__('Berat Badan (Kg)')" />
                            <x-text-input id="weight" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" type="number" name="weight" :value="old('weight')" required />
                            <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                        </div>

                        <!-- Tinggi -->
                        <div class="mb-5">
                            <x-input-label for="height" :value="__('Tinggi Badan (Cm)')" />
                            <x-text-input id="height" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" type="number" name="height" :value="old('height')" required />
                            <x-input-error :messages="$errors->get('height')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button class="bg-primary-600 hover:bg-primary-700 w-full justify-center py-3 rounded-lg text-base">
                                {{ __('Simpan Biodata & Mulai') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
