<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Prenotazioni Condivise
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($prenotazioniCondivise->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">Non ci sono prenotazioni condivise disponibili al momento.</p>
                </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($prenotazioniCondivise as $prenotazione)
                <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                    <div class="p-6">
                        <div class="flex flex-col space-y-4">
                            <div>
                                <strong>Dati Azienda e Utente:</strong>
                                <p>{{ $prenotazione->prenotazione->utente->name }}</p>
                                <p>{{ $prenotazione->prenotazione->utente->aziende->first()->nome ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <strong>Dati della Prenotazione:</strong>
                                <p>Nome: {{ $prenotazione->prenotazione->nome }} {{ $prenotazione->prenotazione->cognome }}</p>
                                <p>Partenza: {{ $prenotazione->prenotazione->partenza }} - Arrivo: {{ $prenotazione->prenotazione->arrivo }}</p>
                                <p>Data: {{ $prenotazione->prenotazione->dataPartenza }}</p>
                                <p>Passeggeri: {{ $prenotazione->prenotazione->passeggeri }} - Bagagli: {{ $prenotazione->prenotazione->bagagli }}</p>
                            </div>
                        </div>
                        <!-- Form per accettare la prenotazione -->
                        <form method="POST" action="{{ route('prenotazioni.update-condivise', $prenotazione->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Accetta
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
