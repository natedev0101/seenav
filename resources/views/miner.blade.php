<x-app-layout>
@section('content')
<div class="min-h-screen bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto bg-gray-800 rounded-xl shadow-2xl overflow-hidden">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 opacity-75"></div>
                <div class="relative z-10 p-8 text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
                        Bányász Útmutató
                    </h1>
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('images/ethan-profile.jpg') }}" alt="Ethan" class="w-32 h-32 rounded-full border-4 border-blue-500 object-cover">
                    </div>
                    <p class="text-xl md:text-2xl mb-6 text-gray-200">
                        Szia! Ethan vagyok és ezen az oldalon bemutatom nektek, hogyan kell bányászni!
                    </p>
                </div>
            </div>

            <div class="p-8 bg-gray-800">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gray-700 p-6 rounded-lg hover:bg-gray-600 transition-all">
                        <h2 class="text-2xl font-semibold mb-4 text-blue-400"> Alapok</h2>
                        <p class="text-gray-300">Minden, amit a bányászat kezdeteiről tudnod kell!</p>
                    </div>
                    <div class="bg-gray-700 p-6 rounded-lg hover:bg-gray-600 transition-all">
                        <h2 class="text-2xl font-semibold mb-4 text-green-400"> Tippek & Trükkök</h2>
                        <p class="text-gray-300">Profi stratégiák a hatékony bányászathoz!</p>
                    </div>
               
    <div class="bg-gray-700 p-6 rounded-lg hover:bg-gray-600 transition-all">
                        <h2 class="text-2xl font-semibold mb-4 text-blue-400"> Trükkök</h2>
                        <p class="text-gray-300">Megtanítom, hogyan NE bányásszatok, mert úgy jártok, mint én, és rátok omlik a bánya!</p>
                    </div>
 </div>
                <div class="mt-8 text-center">
                    <a href="https://youtu.be/dQw4w9WgXcQ?si=1R1ObKC5Q_NZ22GF" target="_blank" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-all">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>
                        </svg>
                        YouTube Csatorna
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('minerPage', () => ({
            // Interaktív elemek Alpine.js-sel, ha szükséges
        }))
    })
</script>
@endpush
</x-app-layout>