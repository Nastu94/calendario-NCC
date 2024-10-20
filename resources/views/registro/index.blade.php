<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Attività del giorno {{ $data }}
        </h2>
    </x-slot>
    <div class="mx-4 sm:mx-6 lg:mx-8 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between">
                <a href="{{ route('registro.index', ['data' => \Carbon\Carbon::parse($data)->subDay()->format('Y-m-d')]) }}" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                    Giorno Precedente
                </a>
                <a href="{{ route('registro.index', ['data' => \Carbon\Carbon::parse($data)->addDay()->format('Y-m-d')]) }}" class="ml-2 sm:ml-0 bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                    Giorno Successivo
                </a>
            </div>
            
            @if ($eventiRegistro->isEmpty())
                <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200 p-6">
                    <p class="text-gray-600">Non sono state registrate attività in questo giorno.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:mb-8">
                    @foreach ($eventiRegistro as $evento)
                        <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                            <div class="p-6">
                                <div class="flex flex-col space-y-4">
                                    <div>
                                        <strong>Tipo di Evento:</strong>
                                        <p>{{ $evento['tipo'] }}</p>
                                    </div>
                                    <div>
                                        <strong>Data:</strong>
                                        <p>{{ $evento['data']->format('Y-m-d H:i') }}</p>
                                    </div>
                                    <div>
                                        <strong>Dettagli:</strong>
                                        <p>{{ $evento['dettagli'] }}</p>
                                    </div>
                                    <div>
                                        <strong>Dati della Prenotazione:</strong>
                                        <p>Nome: {{ $evento['prenotazione']['nome'] }} {{ $evento['prenotazione']['cognome'] }}</p>
                                        <p>Partenza: {{ ucwords($evento['prenotazione']['partenza'] . ', ' . $evento['prenotazione']['cittaPartenza']) }} - Arrivo: {{ ucwords($evento['prenotazione']['arrivo'] . ', ' . $evento['prenotazione']['cittaArrivo']) }}</p>
                                        <p>Data: {{ ($evento['prenotazione']['dataPartenza']) }}</p>
                                        <p>Passeggeri: {{ $evento['prenotazione']['passeggeri'] }} - Bagagli: {{ $evento['prenotazione']['bagagli'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
