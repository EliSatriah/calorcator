<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Konsumsi') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-soft min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Makanan Anda</h3>
                    
                    @if($histories->isEmpty())
                        <div class="text-center py-16 bg-gray-50 rounded-xl border-dashed border-2 border-gray-200">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500 text-lg">Belum ada riwayat makanan. Yuk mulai hitung kalori di Dashboard!</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($histories as $history)
                                <div class="p-5 border rounded-xl border-gray-100 hover:border-primary-200 hover:shadow-md transition-all bg-white">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="text-lg font-bold text-gray-800">{{ $history->food_name }}</h4>
                                        <span class="bg-primary-100 text-primary-800 text-sm font-bold px-3 py-1 rounded-full">{{ $history->calories }} kkal</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">{{ $history->evaluation }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed italic border-l-4 border-primary-300 pl-3">"{{ $history->impact_analysis }}"</p>
                                    <p class="text-xs text-gray-400 mt-4 text-right">{{ $history->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $histories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
