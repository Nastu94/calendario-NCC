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
    
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Nuovo Foglio di Viaggio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('fogli-viaggio.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <x-input-label for="prenotazione_id" value="Prenotazione:" />
                            <select id="prenotazione_id" name="prenotazione_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">Seleziona una prenotazione</option>
                                @foreach ($prenotazioni as $prenotazione)
                                    <option value="{{ $prenotazione->id }}">
                                        {{ $prenotazione->dataPartenza }} - {{ $prenotazione->nome }} {{ $prenotazione->cognome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <x-input-label for="veicolo_id" value="Veicolo:" />
                            <select id="veicolo_id" name="veicolo_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">Seleziona un veicolo</option>    
                                @foreach ($veicoli as $veicolo)
                                    <option value="{{ $veicolo->id }}" data-km="{{ $veicolo->kmPercorsi }}">
                                        {{ $veicolo->modello }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <x-input-label for="kmIniziali" value="Kilometri Iniziali:" />
                            <x-text-input id="kmIniziali" name="kmIniziali" type="number" class="block w-full mt-1" required />
                        </div>
                        <x-primary-button>
                            Crea Foglio di Viaggio
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('veicolo_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var kmIniziali = selectedOption.getAttribute('data-km');
            document.getElementById('kmIniziali').value = kmIniziali;
        });
    </script>
</x-app-layout>
    @php 
     //dd($veicoli);
     @endphp