<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calorcator - Lengkapi Biodata</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        .clip-bg { clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white overflow-x-hidden min-h-screen relative">

    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-br from-brandBlue to-blue-900 clip-bg -z-10"></div>
    <div class="absolute top-[-50px] right-[-50px] w-96 h-96 bg-brandOrange/20 rounded-full blur-3xl -z-10"></div>
    <div class="absolute top-[200px] left-[-100px] w-80 h-80 bg-white/10 rounded-full blur-3xl -z-10"></div>

    <!-- Navigation -->
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center relative z-10">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Calorcator Logo" class="h-10 w-10 object-contain drop-shadow-md" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48Y2lyY2xlIGN4PSI1MCIgY3k9IjUwIiByPSI0MCIgZmlsbD0iIzAyM2U4YSIvPjwvc3ZnPg=='">
            <span class="text-2xl font-extrabold text-white tracking-tight hidden sm:block">Calorcator</span>
        </div>
        
        <div class="flex items-center gap-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-blue-100 hover:text-white font-medium text-sm sm:text-base transition-colors bg-white/10 px-4 py-2 rounded-full border border-white/20 hover:bg-white/20">Batalkan & Keluar</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10 animate-fade-in-up">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">Satu Langkah Lagi! 🎉</h1>
            <p class="text-blue-100 text-lg">Beri tahu AI kami sedikit tentang dirimu agar hitungan kalorinya 100% akurat.</p>
        </div>

        <div class="bg-white p-6 sm:p-10 rounded-[2rem] shadow-2xl shadow-brandBlue/20 border border-gray-100 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-brandOrange/10 rounded-full blur-2xl"></div>
            
            <form method="POST" action="{{ route('bio.store') }}" class="space-y-6 relative z-10">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Umur -->
                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100/50 hover:border-brandBlue/30 transition-colors">
                        <label for="age" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider flex items-center gap-2">
                            <span>🎂</span> Umur (Tahun)
                        </label>
                        <input id="age" type="number" name="age" value="{{ old('age') }}" class="w-full text-lg font-bold text-brandBlue bg-white border-none rounded-xl focus:ring-4 focus:ring-brandBlue/10 shadow-sm" placeholder="Contoh: 25" required autofocus />
                        <x-input-error :messages="$errors->get('age')" class="mt-2 text-xs" />
                    </div>

                    <!-- Gender -->
                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100/50 hover:border-brandBlue/30 transition-colors">
                        <label for="gender" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider flex items-center gap-2">
                            <span>🚻</span> Jenis Kelamin
                        </label>
                        <select id="gender" name="gender" class="w-full text-lg font-bold text-brandBlue bg-white border-none rounded-xl focus:ring-4 focus:ring-brandBlue/10 shadow-sm" required>
                            <option value="" disabled selected class="text-gray-400">Pilih...</option>
                            <option value="male" @if(old('gender') == 'male') selected @endif>Laki-laki</option>
                            <option value="female" @if(old('gender') == 'female') selected @endif>Perempuan</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2 text-xs" />
                    </div>

                    <!-- Berat -->
                    <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100/50 hover:border-brandOrange/30 transition-colors">
                        <label for="weight" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider flex items-center gap-2">
                            <span>⚖️</span> Berat Badan (Kg)
                        </label>
                        <input id="weight" type="number" name="weight" value="{{ old('weight') }}" class="w-full text-lg font-bold text-brandOrange bg-white border-none rounded-xl focus:ring-4 focus:ring-brandOrange/10 shadow-sm" placeholder="Contoh: 65" required />
                        <x-input-error :messages="$errors->get('weight')" class="mt-2 text-xs" />
                    </div>

                    <!-- Tinggi -->
                    <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100/50 hover:border-brandOrange/30 transition-colors">
                        <label for="height" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider flex items-center gap-2">
                            <span>📏</span> Tinggi Badan (Cm)
                        </label>
                        <input id="height" type="number" name="height" value="{{ old('height') }}" class="w-full text-lg font-bold text-brandOrange bg-white border-none rounded-xl focus:ring-4 focus:ring-brandOrange/10 shadow-sm" placeholder="Contoh: 170" required />
                        <x-input-error :messages="$errors->get('height')" class="mt-2 text-xs" />
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-brandOrange hover:bg-orange-500 text-white font-black text-lg sm:text-xl py-4 rounded-2xl shadow-xl shadow-brandOrange/30 hover:shadow-brandOrange/50 hover:-translate-y-1 transition-all flex justify-center items-center gap-3">
                        Simpan & Mulai Diet! 🚀
                    </button>
                </div>
                
                <p class="text-center text-xs text-gray-400 font-medium mt-4">
                    Data ini hanya digunakan untuk menghitung target harian dan privasinya terjaga.
                </p>
            </form>
        </div>
        
    </main>
</body>
</html>
