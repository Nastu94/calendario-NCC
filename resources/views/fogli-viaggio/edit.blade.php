<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Modifica Foglio di Viaggio
        </h2>
        <h4>
            Prenotazione n°{{ $foglioViaggio->prenotazione_id }} per {{ ucwords($foglioViaggio->prenotazione->nome . ' ' . $foglioViaggio->prenotazione->cognome) }}
        </h4>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('fogli-viaggio.update', $foglioViaggio->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <x-input-label for="numero" value="Numero Foglio:" />
                            <x-text-input id="numero" name="numero" type="text" value="{{ $nextNumber }}" required class="block mt-1 w-full" readonly />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="kmFinali" value="Kilometri Finali:" />
                            <x-text-input id="kmFinali" name="kmFinali" type="number" placeholder="I KM attuali della {{ $foglioViaggio->veicolo->modello . ' sono ' . $foglioViaggio->kmIniziali }}" min="{{ $foglioViaggio->kmIniziali }}" required class="block mt-1 w-full" />
                        </div>
                        <x-primary-button class="mt-4">
                            Aggiorna Foglio di Viaggio
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
