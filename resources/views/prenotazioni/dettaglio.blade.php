<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Prenotazioni {{ ucfirst($tipo) }} per il giorno {{ $data }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center">
                <!-- Pulsanti per navigare tra i giorni -->
                <a href="{{ route('prenotazioni.dettaglio', ['data' => \Carbon\Carbon::parse($data)->subDay()->format('Y-m-d'), 'tipo' => $tipo]) }}" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                    Giorno Precedente
                </a>
                
                <a href="{{ route('prenotazioni.dettaglio', ['data' => \Carbon\Carbon::parse($data)->addDay()->format('Y-m-d'), 'tipo' => $tipo]) }}" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                    Giorno Successivo
                </a>
            </div>
                
            <div class="mb-4 flex justify-center items-center">
                    @if($tipo == 'aziendali' || $tipo == 'globali' || $tipo == 'condivise')
                    <a href="{{ route('prenotazioni.dettaglio', ['data' => $data, 'tipo' => 'personali']) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Personali
                    </a>
                    @endif
                    @if($tipo == 'personali' || $tipo == 'globali' || $tipo == 'condivise')
                    <a href="{{ route('prenotazioni.dettaglio', ['data' => $data, 'tipo' => 'aziendali']) }}" class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Aziendali
                    </a>
                    @endif
                    @if($tipo == 'personali' || $tipo == 'aziendali' || $tipo == 'condivise')
                    <a href="{{ route('prenotazioni.dettaglio', ['data' => $data, 'tipo' => 'globali']) }}" class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Globali
                    </a>
                    @endif
                    @if($tipo == 'personali' || $tipo == 'aziendali' || $tipo == 'globali')
                    <a href="{{ route('prenotazioni.dettaglio', ['data' => $data, 'tipo' => 'condivise']) }}" class="ml-2 bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                        Condivise
                    </a>
                    @endif
                </div>
            
            @if ($prenotazioni->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">Non ci sono prenotazioni {{ $tipo }} disponibili per questo giorno.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($prenotazioni as $prenotazione)
                        <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                            <div class="p-6">
                                <div class="flex flex-col space-y-4">
                                    <div>
                                        <strong>Dati Azienda e Utente:</strong>
                                        <p>{{ $prenotazione->utente->name }}</p>
                                        <p>{{ $prenotazione->utente->aziende->first()->nome ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <strong>Dati della Prenotazione:</strong>
                                        <p>Nome: {{ $prenotazione->nome }} {{ $prenotazione->cognome }}</p>
                                        <p>Partenza: {{ $prenotazione->partenza }} - Arrivo: {{ $prenotazione->arrivo }}</p>
                                        <p>Data: {{ $prenotazione->dataPartenza }}</p>
                                        <p>Passeggeri: {{ $prenotazione->passeggeri }} - Bagagli: {{ $prenotazione->bagagli }}</p>
                                    </div>
                                </div>
                                @if($tipo == 'condivise')
                                <!-- Form per accettare la prenotazione -->
                                <form method="POST" action="{{ route('prenotazioni.update-condivise', $prenotazione->condivisioni->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Accetta
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
