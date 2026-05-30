<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBio;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return view('welcome');
        }

        $user = auth()->user();
        
        if (!$user->bio) {
            return view('bio.create');
        }

        $bio = $user->bio;
        // TDEE/BMR sederhana
        $bmr = 10 * $bio->weight + 6.25 * $bio->height - 5 * $bio->age;
        $bmr = $bio->gender === 'male' ? $bmr + 5 : $bmr - 161;
        $daily_calories = round($bmr * 1.55);

        // Ambil data hari ini
        $today_calories = $user->histories()->whereDate('created_at', now()->toDateString())->sum('calories');
        $today_histories = $user->histories()->whereDate('created_at', now()->toDateString())->latest()->get();

        // Statistik
        $top_food = $user->histories()
            ->select('food_name', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('food_name')
            ->orderByDesc('total')
            ->first();

        $highest_calorie_food = $user->histories()->orderByDesc('calories')->first();

        // Rekomendasi Menu Sehat
        $remaining = $daily_calories - $today_calories;
        $recommendation = [
            'title' => '',
            'desc' => '',
            'icon' => '💡'
        ];

        if ($remaining > 600) {
            $recommendation['title'] = 'Makan Besar Sehat';
            $recommendation['desc'] = 'Sisa kalori masih banyak. Boleh makan berat (Nasi Merah + Dada Ayam Panggang, Ikan Bakar).';
            $recommendation['icon'] = '🍱';
        } elseif ($remaining >= 300) {
            $recommendation['title'] = 'Porsi Sedang';
            $recommendation['desc'] = 'Kalori tersisa untuk porsi sedang. Coba Salad Buah, Sandwich Gandum, atau Sup Ayam.';
            $recommendation['icon'] = '🥗';
        } elseif ($remaining > 0) {
            $recommendation['title'] = 'Cemilan Ringan';
            $recommendation['desc'] = 'Hati-hati, kalori hampir habis! Ngemil buah potong, apel, atau yoghurt saja.';
            $recommendation['icon'] = '🍎';
        } else {
            $recommendation['title'] = 'Batas Maksimal!';
            $recommendation['desc'] = 'Anda sudah over kalori. Perbanyak minum air putih atau teh hijau tawar.';
            $recommendation['icon'] = '💧';
        }

        return view('welcome', compact('bio', 'daily_calories', 'today_calories', 'today_histories', 'top_food', 'highest_calorie_food', 'recommendation'));
    }

    public function storeBio(Request $request)
    {
        $request->validate([
            'age' => 'required|integer|min:10|max:100',
            'gender' => 'required|in:male,female',
            'weight' => 'required|integer|min:20|max:300',
            'height' => 'required|integer|min:50|max:250',
        ]);

        auth()->user()->bio()->create($request->all());

        return redirect()->route('dashboard')->with('success', 'Biodata berhasil disimpan!');
    }

    public function history()
    {
        $histories = auth()->user()->histories()->latest()->paginate(10);
        return view('history.index', compact('histories'));
    }

    public function analyzeFood(Request $request)
    {
        $rules = [
            'food_input' => 'required|string|max:255',
        ];

        // Jika guest dan belum ada session biodata, maka wajib input biodata
        if (!auth()->check() && !session()->has('guest_bio')) {
            $rules = array_merge($rules, [
                'age' => 'required|integer|min:10|max:100',
                'gender' => 'required|in:male,female',
                'weight' => 'required|integer|min:20|max:300',
                'height' => 'required|integer|min:50|max:250',
            ]);
        }

        $request->validate($rules);

        $bio = null;
        $isGuest = !auth()->check();

        if ($isGuest) {
            if (!session()->has('guest_bio')) {
                // Simpan biodata guest ke session
                $guestBio = $request->only(['age', 'gender', 'weight', 'height']);
                session(['guest_bio' => (object)$guestBio]);
            }
            $bio = session('guest_bio');
        } else {
            $user = auth()->user();
            $bio = $user->bio;
            if (!$bio) {
                return redirect()->route('dashboard')->with('error', 'Silakan lengkapi biodata terlebih dahulu.');
            }
        }

        // TDEE/BMR sederhana
        $bmr = 10 * $bio->weight + 6.25 * $bio->height - 5 * $bio->age;
        $bmr = $bio->gender === 'male' ? $bmr + 5 : $bmr - 161;
        $daily_calories = round($bmr * 1.55);

        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
        if (!$apiKey) {
            foreach ($_SERVER as $key => $value) {
                if (is_string($key) && str_contains(strtoupper($key), 'GEMINI')) {
                    $apiKey = $value;
                    break;
                }
            }
        }
        
        if (!$apiKey) {
            return redirect()->route('dashboard')->with('error', 'API Key belum disetting di .env atau server variables!');
        }

        $foodInput = $request->input('food_input');
        $prompt = "Kamu adalah ahli gizi. Profil pengguna: umur {$bio->age} tahun, berat {$bio->weight} kg, tinggi {$bio->height} cm, kelamin {$bio->gender}, butuh {$daily_calories} kkal/hari.
Pengguna baru memakan: '{$foodInput}'.
Tugas:
1. Hitung kalori estimasi.
2. Hitung perkiraan total KALORI (kkal).
3. Hitung perkiraan PROTEIN (gram), KARBOHIDRAT (gram), dan LEMAK (gram).
4. Beri evaluasi (Sesuai/Berlebih/Kurang Sehat) dan analisis dampak kesehatan (max 2 kalimat) serta saran singkat.
Balas HANYA dengan format JSON valid berikut (tanpa blok ```json):
{
    \"food_name\": \"Rangkuman makanan (cth: Nasi Kuning, Ayam Goreng, Es Teh & Donat)\",
    \"calories\": 123,
    \"protein\": 10,
    \"carbo\": 20,
    \"fat\": 5,
    \"evaluation\": \"Sesuai/Berlebih/Kurang Sehat\",
    \"impact_analysis\": \"Penjelasan kesehatan dan saran.\"
}";

        try {
            // Menggunakan withoutVerifying() untuk mengatasi masalah sertifikat SSL (cURL error 60) yang sering terjadi di Windows lokal.
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json' // Memaksa AI mengembalikan format JSON asli
                ]
            ]);

            if ($response->successful()) {
                $resultText = $response->json('candidates.0.content.parts.0.text');
                
                // Terkadang AI masih mengembalikan backticks meski disuruh JSON
                $resultText = str_replace(['```json', '```'], '', $resultText);
                $resultData = json_decode(trim($resultText), true);

                if ($resultData && isset($resultData['calories'])) {
                    if (!$isGuest) {
                        $user->histories()->create([
                            'food_name' => $resultData['food_name'],
                            'calories' => $resultData['calories'],
                            'protein' => $resultData['protein'] ?? 0,
                            'carbo' => $resultData['carbo'] ?? 0,
                            'fat' => $resultData['fat'] ?? 0,
                            'evaluation' => $resultData['evaluation'],
                            'impact_analysis' => $resultData['impact_analysis'],
                        ]);
                    }
                    return redirect()->back()->with('result', $resultData);
                } else {
                    return redirect()->back()->with('error', 'Format JSON dari AI tidak sesuai. Hasil asli: ' . substr($resultText, 0, 100));
                }
            }
            
            // Menampilkan pesan error asli dari API jika gagal (misal API key salah)
            return redirect()->back()->with('error', 'Gagal memanggil API: ' . $response->body());
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
