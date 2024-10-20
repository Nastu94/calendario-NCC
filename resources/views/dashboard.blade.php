<x-app-layout>
    
    <x-slot name="header">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Successo!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Errore!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        @if(Auth::user()->permessi->contains('nome', 'Azienda') || Auth::user()->permessi->contains('nome', 'Autista'))
                            <!-- Icona Gestisci Prenotazioni -->
                            <a href="{{ route('prenotazioni.create') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-document-text class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Gestisci Prenotazioni</h3>
                            </a>

                            <!-- Icona Le Mie Prenotazioni -->
                            <a href="{{ route('prenotazioni.index-personali') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-calendar-days class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Le mie Prenotazioni</h3>
                            </a>

                            <!-- Icona Prenotazioni Globali -->
                            <a href="{{ route('prenotazioni.index-globali') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-uiw-global class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Prenotazioni Globali</h3>
                            </a>

                            <!-- Icona Prenotazioni Aziendali -->
                            <a href="{{ route('prenotazioni.index-aziendali') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-building-office class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Prenotazioni Aziendali</h3>
                            </a>
                            
                            <!-- Icona Prenotazioni Condivise -->
                            <a href="{{ route('prenotazioni.index-condivise') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-share class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Prenotazioni Condivise</h3>
                            </a>

                            <!-- Icona Registro Prenotazioni -->
                            <a href="{{ route('registro.index', ['data' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-clipboard-document-list class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Registro Prenotazioni</h3>
                            </a>

                            <!-- Icona Foglio di Viaggio -->
                            <a href="{{ route('fogli-viaggio.index') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-newspaper class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Fogli di Viaggio</h3>
                            </a>                        
                        @endif

                        @if(Auth::user()->permessi->contains('nome', 'Azienda'))
                            <!-- Icona Gestione Autisti -->
                            <a href="{{ route('autisti.index') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-user-group class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Gestione Autisti</h3>
                            </a>

                            <!-- Icona Gestione Veicoli -->
                            <a href="{{ route('veicoli.index') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-fas-car-side class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Gestione Veicoli</h3>
                            </a>
                        @endif

                        @if(Auth::user()->permessi->contains('nome', 'Amministratore'))
                            <!-- Icona Gestione Aziende -->
                            <a href="{{ route('admin.create-azienda') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-building-office class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Gestione Aziende</h3>
                            </a>

                            <!-- Icona Gestione Utenti Azienda -->
                            <a href="{{ route('admin.create-user') }}" class="flex flex-col items-center p-4 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <x-heroicon-s-user-group class="h-20 w-20 mb-2"/>
                                <h3 class="text-lg font-semibold">Gestione Utenti Azienda</h3>
                            </a>
                        @endif

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
