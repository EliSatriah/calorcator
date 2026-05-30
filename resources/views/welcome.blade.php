<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calorcator - Cerdas Pantau Kalori</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .clip-bg { clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white overflow-x-hidden">

    <div class="relative overflow-hidden min-h-screen">
        <!-- Background Decoration -->
        <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-br from-white to-blue-50 clip-bg -z-10"></div>
        <div class="absolute top-[-100px] right-[-100px] w-96 h-96 bg-brandOrange/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-[200px] left-[-100px] w-80 h-80 bg-brandBlue/5 rounded-full blur-3xl -z-10"></div>

        <!-- Navigation -->
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center animate-fade-in-up">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Calorcator Logo" class="h-10 w-10 object-contain" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48Y2lyY2xlIGN4PSI1MCIgY3k9IjUwIiByPSI0MCIgZmlsbD0iIzAyM2U4YSIvPjwvc3ZnPg=='">
                <span class="text-2xl font-extrabold text-brandBlue tracking-tight">Calorcator</span>
            </div>
            
            @if (Route::has('login'))
                <div class="flex items-center gap-4">
                    @auth
                        <!-- Dropdown Logout untuk Member -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 text-brandBlue font-bold hover:text-brandOrange transition-colors">
                                {{ auth()->user()->name }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50" style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brandBlue hover:text-white font-medium transition-colors">Profile Setting</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold transition-colors">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 font-medium hover:text-brandBlue transition-colors">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-brandOrange text-white font-semibold px-6 py-2.5 rounded-full shadow-lg shadow-brandOrange/30 hover:bg-orange-500 hover:shadow-brandOrange/50 hover:-translate-y-0.5 transition-all duration-300">Daftar Gratis</a>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            @auth
                <!-- LAYOUT MEMBER (DASHBOARD) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Kiri: Target & Form -->
                    <div class="lg:col-span-7 space-y-6">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-brandBlue mb-4">Halo, {{ auth()->user()->name }} 👋</h1>
                        
                        <!-- Target Progress -->
                        <div class="bg-white overflow-hidden shadow-sm sm:shadow-lg rounded-3xl border border-gray-100 p-5 sm:p-8">
                            <h3 class="text-xl sm:text-2xl font-black text-brandBlue mb-4">🎯 Target Hari Ini</h3>
                            
                            @php
                                $percentage = min(100, round(($today_calories / $daily_calories) * 100));
                                $remaining = $daily_calories - $today_calories;
                                $isOver = $remaining < 0;
                            @endphp

                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-2xl sm:text-3xl font-black text-brandBlue">{{ $today_calories }} <span class="text-sm font-bold text-gray-400">/ {{ $daily_calories }} kkal</span></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-5 sm:h-6 p-1 border border-gray-200">
                                    <div class="@if($isOver) bg-red-500 @elseif($percentage > 85) bg-brandOrange @else bg-brandBlue @endif h-full rounded-full transition-all duration-1000 shadow-sm relative overflow-hidden" style="width: {{ $percentage }}%">
                                        <div class="absolute top-0 right-0 bottom-0 left-0 bg-white/20 animate-pulse"></div>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-center font-medium">
                                    @if($isOver)
                                        <span class="text-red-500 bg-red-50 px-3 py-1 rounded-full">⚠️ Melebihi batas {{ abs($remaining) }} kkal</span>
                                    @else
                                        <span class="text-gray-500">Sisa jatah: <strong class="text-brandOrange">{{ $remaining }} kkal</strong> 🔥</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- AI Form -->
                        <div id="calculator-section" class="bg-gradient-to-br from-brandBlue to-blue-900 overflow-hidden shadow-xl shadow-brandBlue/20 rounded-3xl border border-brandBlue/50 relative">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-brandOrange/20 rounded-full blur-2xl"></div>
                            
                            <div class="p-6 sm:p-8 relative z-10">
                                <h3 class="text-xl sm:text-2xl font-black text-white mb-2">Yuk, catat makanmu! 🍽️</h3>
                                <p class="text-blue-100 mb-6 text-xs sm:text-sm">Satu jenis atau porsi lengkap, ceritakan saja semuanya.</p>
                                
                                @if(session('error'))
                                    <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-xl text-sm border border-red-100 font-medium">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('food.analyze') }}">
                                    @csrf
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <input type="text" name="food_input" class="flex-1 w-full p-4 text-center sm:text-left text-base text-gray-900 border-2 border-transparent rounded-2xl sm:rounded-full bg-white focus:border-brandOrange focus:ring-4 focus:ring-brandOrange/30 transition-all shadow-inner" placeholder="Cth: Nasi padang lauk rendang" required>
                                        <button type="submit" class="w-full sm:w-auto text-brandBlue bg-brandOrange hover:bg-orange-400 font-black rounded-2xl sm:rounded-full px-8 py-4 shadow-lg shadow-black/20 hover:-translate-y-1 transition-all whitespace-nowrap">
                                            HITUNG ✨
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Result Card -->
                        @if(session('result'))
                            <div class="bg-white p-6 md:p-8 rounded-3xl shadow-xl shadow-brandBlue/10 border border-green-100 animate-fade-in-up relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-2 h-full bg-green-500"></div>
                                <h4 class="text-lg font-black text-brandBlue mb-4">{{ session('result')['food_name'] }}</h4>
                                
                                <div class="flex flex-col sm:flex-row gap-4 mb-4">
                                    <div class="bg-brandBlue/5 p-4 rounded-2xl border border-brandBlue/10 sm:w-1/3 text-center sm:text-left">
                                        <span class="text-brandBlue/60 text-[10px] font-bold uppercase tracking-wider">Kalori</span>
                                        <p class="text-3xl font-black text-brandBlue mt-1">{{ session('result')['calories'] }}</p>
                                    </div>
                                    <div class="flex-1 flex flex-wrap gap-2 items-center justify-center sm:justify-start">
                                        <span class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold px-4 py-2 rounded-xl flex-1 text-center sm:flex-none">P: {{ session('result')['protein'] ?? 0 }}g</span>
                                        <span class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold px-4 py-2 rounded-xl flex-1 text-center sm:flex-none">K: {{ session('result')['carbo'] ?? 0 }}g</span>
                                        <span class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold px-4 py-2 rounded-xl flex-1 text-center sm:flex-none">L: {{ session('result')['fat'] ?? 0 }}g</span>
                                    </div>
                                </div>
                                
                                <div class="bg-orange-50 p-5 rounded-2xl border border-orange-100">
                                    <span class="text-brandOrange text-sm font-bold tracking-wider mb-2 block">{{ session('result')['evaluation'] }}</span>
                                    <p class="text-gray-700 text-sm leading-relaxed font-medium">"{{ session('result')['impact_analysis'] }}"</p>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Kanan: Statistik & Histori -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <!-- Insight & Statistik -->
                        <div class="bg-gradient-to-br from-brandOrange to-orange-500 rounded-3xl p-6 shadow-lg shadow-brandOrange/20 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
                            <h4 class="text-white font-bold mb-4 relative z-10 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Insight & Statistik Anda
                            </h4>
                            
                            <div class="space-y-3 relative z-10">
                                <!-- Rekomendasi Menu -->
                                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="text-3xl">{{ $recommendation['icon'] ?? '💡' }}</div>
                                        <div>
                                            <h5 class="text-white font-bold text-sm">{{ $recommendation['title'] ?? 'Menu Sehat' }}</h5>
                                            <p class="text-orange-50 text-xs mt-1 leading-relaxed">{{ $recommendation['desc'] ?? 'Jaga pola makan Anda agar selalu sehat.' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Top Food -->
                                    <div class="bg-white rounded-2xl p-3 shadow-sm">
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Paling Sering</span>
                                        @if(isset($top_food) && $top_food)
                                            <p class="text-sm font-black text-brandBlue leading-tight line-clamp-2" title="{{ $top_food->food_name }}">{{ $top_food->food_name }}</p>
                                            <span class="text-[10px] font-bold text-brandOrange bg-orange-50 px-2 py-0.5 rounded-full mt-1 inline-block">{{ $top_food->total }}x Dimakan</span>
                                        @else
                                            <p class="text-xs text-gray-400 font-medium">Belum ada data</p>
                                        @endif
                                    </div>

                                    <!-- Highest Calorie -->
                                    <div class="bg-white rounded-2xl p-3 shadow-sm">
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Rekor Kalori</span>
                                        @if(isset($highest_calorie_food) && $highest_calorie_food)
                                            <p class="text-sm font-black text-brandBlue leading-tight line-clamp-2" title="{{ $highest_calorie_food->food_name }}">{{ $highest_calorie_food->food_name }}</p>
                                            <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full mt-1 inline-block">{{ $highest_calorie_food->calories }} kkal</span>
                                        @else
                                            <p class="text-xs text-gray-400 font-medium">Belum ada data</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Histori Hari Ini -->
                        <div class="bg-white overflow-hidden shadow-sm sm:shadow-lg rounded-3xl border border-gray-100 p-5 sm:p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="text-lg font-bold text-brandBlue">Histori Hari Ini</h4>
                                <span class="bg-blue-50 text-brandBlue text-xs font-bold px-3 py-1 rounded-full">{{ $today_histories->count() }} item</span>
                            </div>
                            
                            @if($today_histories->isEmpty())
                                <div class="text-center py-10 bg-gray-50 rounded-2xl border-dashed border border-gray-200">
                                    <p class="text-gray-400 text-sm">Belum ada makanan dicatat hari ini.</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($today_histories as $history)
                                        <div class="p-4 border rounded-2xl border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                                            <div class="flex justify-between items-start mb-2">
                                                <h5 class="font-bold text-gray-800 text-base line-clamp-2 leading-tight">{{ $history->food_name }}</h5>
                                                <span class="bg-brandBlue/10 text-brandBlue text-xs font-black px-2 py-1 rounded-full whitespace-nowrap ml-2">+{{ $history->calories }}</span>
                                            </div>
                                            <p class="text-[11px] text-gray-400 mb-2">{{ $history->created_at->format('H:i') }} • <span class="font-medium text-gray-500">{{ $history->evaluation }}</span></p>
                                            <div class="flex gap-2 text-[10px] text-gray-500 font-medium">
                                                <span class="bg-gray-50 border border-gray-100 px-2 py-1 rounded-lg">P: <strong class="text-gray-700">{{ $history->protein ?? 0 }}g</strong></span>
                                                <span class="bg-gray-50 border border-gray-100 px-2 py-1 rounded-lg">K: <strong class="text-gray-700">{{ $history->carbo ?? 0 }}g</strong></span>
                                                <span class="bg-gray-50 border border-gray-100 px-2 py-1 rounded-lg">L: <strong class="text-gray-700">{{ $history->fat ?? 0 }}g</strong></span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            @else
                <!-- LAYOUT GUEST (LANDING PAGE ORIGINAL) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <!-- Kiri: Text content & Form -->
                    <div class="space-y-6 animate-fade-in-up delay-100">
                        <div class="inline-block bg-brandBlue/10 border border-brandBlue/20 px-4 py-1.5 rounded-full">
                            <span class="text-sm font-bold text-brandBlue flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brandOrange animate-pulse"></span>
                                Didukung oleh AI Cerdas
                            </span>
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-brandBlue leading-tight">
                            Hitung Kalori <br/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brandOrange to-orange-400">
                                Semudah Bercerita
                            </span>
                        </h1>
                        
                        <p class="text-lg md:text-xl text-gray-600 leading-relaxed max-w-lg">
                            Cukup ketik apa yang Anda makan dengan bahasa sehari-hari. AI cerdas kami akan menganalisis porsi, menghitung kalori, dan memantau target harian Anda secara otomatis.
                        </p>

                        <div class="pt-2" id="calculator-section">
                            @if(session('error'))
                                <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-2xl text-sm border border-red-100 font-medium">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if(!session()->has('guest_bio'))
                                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-brandBlue/10 border border-gray-100">
                                    <h3 class="font-bold text-brandBlue mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Coba Gratis Sekarang
                                    </h3>
                                    <form method="POST" action="{{ route('food.analyze') }}" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Umur</label>
                                                <input type="number" name="age" class="w-full p-2.5 rounded-xl border-gray-200 text-sm focus:border-brandOrange focus:ring-brandOrange/30 bg-gray-50" placeholder="Cth: 25" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Gender</label>
                                                <select name="gender" class="w-full p-2.5 rounded-xl border-gray-200 text-sm focus:border-brandOrange focus:ring-brandOrange/30 bg-gray-50" required>
                                                    <option value="male">Laki-laki</option>
                                                    <option value="female">Perempuan</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Berat (kg)</label>
                                                <input type="number" name="weight" class="w-full p-2.5 rounded-xl border-gray-200 text-sm focus:border-brandOrange focus:ring-brandOrange/30 bg-gray-50" placeholder="Cth: 65" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Tinggi (cm)</label>
                                                <input type="number" name="height" class="w-full p-2.5 rounded-xl border-gray-200 text-sm focus:border-brandOrange focus:ring-brandOrange/30 bg-gray-50" placeholder="Cth: 170" required>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-2 mt-4">
                                            <input type="text" name="food_input" class="flex-1 w-full p-3 text-center sm:text-left text-sm text-gray-900 border-2 border-brandBlue/10 rounded-xl focus:border-brandBlue focus:ring-4 focus:ring-brandBlue/10 transition-all bg-white" placeholder="Baru makan apa nih? 🍔" required>
                                            <button type="submit" class="w-full sm:w-auto text-white bg-brandOrange hover:bg-orange-500 font-bold text-sm rounded-xl px-6 py-3 shadow-md hover:shadow-lg transition-all whitespace-nowrap">
                                                HITUNG
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <!-- Form simple untuk yang sudah punya session bio -->
                                <form method="POST" action="{{ route('food.analyze') }}">
                                    @csrf
                                    <div class="flex flex-col sm:flex-row gap-3 max-w-lg shadow-2xl shadow-brandBlue/20 rounded-2xl sm:rounded-full border-2 border-white bg-white/50 p-1">
                                        <input type="text" name="food_input" class="flex-1 w-full p-4 sm:p-5 text-center sm:text-left text-base text-gray-900 border-none rounded-2xl sm:rounded-full bg-white focus:ring-4 focus:ring-brandOrange/30 transition-all" placeholder="Baru makan apa nih? 🍕" required>
                                        <button type="submit" class="w-full sm:w-auto text-white bg-brandOrange hover:bg-orange-500 font-black text-lg rounded-xl sm:rounded-full px-8 py-4 sm:py-5 shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 whitespace-nowrap">
                                            HITUNG ✨
                                        </button>
                                    </div>
                                </form>
                                <p class="text-xs text-gray-400 mt-4 ml-4">
                                    Biodata Anda sudah tersimpan sementara. <a href="{{ route('register') }}" class="text-brandOrange hover:underline font-bold">Daftar sekarang</a> untuk menyimpan riwayat.
                                </p>
                            @endif
                        </div>

                        @if(session('result'))
                            <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-2xl shadow-brandBlue/10 border border-green-100 animate-fade-in-up mt-4 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-2 h-full bg-green-500"></div>
                                <h4 class="text-lg font-black text-brandBlue mb-4">{{ session('result')['food_name'] }}</h4>
                                
                                <div class="flex flex-col sm:flex-row gap-4 mb-4">
                                    <div class="bg-brandBlue/5 p-4 rounded-2xl border border-brandBlue/10 sm:w-1/3 text-center sm:text-left">
                                        <span class="text-brandBlue/60 text-[10px] font-bold uppercase tracking-wider">Kalori</span>
                                        <p class="text-3xl font-black text-brandBlue mt-1">{{ session('result')['calories'] }}</p>
                                    </div>
                                    <div class="flex-1 flex flex-wrap gap-2 items-center justify-center sm:justify-start">
                                        <span class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold px-4 py-2 rounded-xl flex-1 text-center sm:flex-none">P: {{ session('result')['protein'] ?? 0 }}g</span>
                                        <span class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold px-4 py-2 rounded-xl flex-1 text-center sm:flex-none">K: {{ session('result')['carbo'] ?? 0 }}g</span>
                                        <span class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold px-4 py-2 rounded-xl flex-1 text-center sm:flex-none">L: {{ session('result')['fat'] ?? 0 }}g</span>
                                    </div>
                                </div>
                                
                                <div class="bg-orange-50 p-5 rounded-2xl border border-orange-100">
                                    <span class="text-brandOrange text-sm font-bold tracking-wider mb-2 block">{{ session('result')['evaluation'] }}</span>
                                    <p class="text-gray-700 text-sm leading-relaxed font-medium">"{{ session('result')['impact_analysis'] }}"</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Kanan: Image / Logo Showcase -->
                    <div class="relative flex justify-center items-center animate-fade-in-up delay-200 hidden md:flex">
                        <div class="absolute w-[350px] h-[350px] lg:w-[450px] lg:h-[450px] rounded-full border-2 border-brandBlue/5 animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite]"></div>
                        <div class="absolute w-[250px] h-[250px] lg:w-[350px] lg:h-[350px] rounded-full border border-brandOrange/20"></div>
                        
                        <div class="relative bg-white p-6 rounded-full shadow-2xl shadow-brandBlue/20 animate-float z-10">
                            <img src="{{ asset('images/logo.png') }}" alt="Calorcator Logo" class="w-48 h-48 lg:w-64 lg:h-64 object-contain" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48Y2lyY2xlIGN4PSI1MCIgY3k9IjUwIiByPSI0MCIgZmlsbD0iIzAyM2U4YSIvPjwvc3ZnPg=='">
                        </div>
                    </div>
                </div>
            @endauth
        </main>

        @if(!auth()->check())
        <!-- Simple Features Section untuk Guest -->
        <section id="how-it-works" class="bg-white py-20 border-t border-gray-50 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 animate-fade-in-up">
                    <h2 class="text-3xl font-bold text-brandBlue mb-4">Mengapa Calorcator?</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Kami menggabungkan kecanggihan AI dengan antarmuka yang sangat mudah digunakan.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 bg-brandBlue/10 text-brandBlue rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Input Bahasa Sehari-hari</h3>
                        <p class="text-gray-600">Tidak perlu mencari menu satu per satu di database. Ketik saja "Saya baru makan soto ayam" dan biarkan AI kami bekerja.</p>
                    </div>
                    
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 bg-brandOrange/10 text-brandOrange rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Evaluasi Akurat</h3>
                        <p class="text-gray-600">Sistem akan menghitung kebutuhan harian Anda secara spesifik berdasarkan umur, tinggi, dan berat badan.</p>
                    </div>
                    
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Insight Kesehatan</h3>
                        <p class="text-gray-600">Dapatkan saran atau peringatan instan tentang nutrisi makanan yang baru saja Anda konsumsi.</p>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div></div>
    
    <!-- Alpine JS for Dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
</body>
</html>
