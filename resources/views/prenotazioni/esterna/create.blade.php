<x-guest-layout>
    
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Successo!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @elseif (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Errore!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @else
        <h2 class="flex justify-center text-2xl font-semibold leading-tight text-gray-800">
            Inserisci la tua Prenotazione con l'autista {{ $user->name }}
        </h2>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-5">
                    <form method="POST" action="{{ route('prenotazioni.esterna.store', ['token' => request()->route('token')]) }}">
                        @csrf

                        <h3 class="flex justify-center text-xl font-semibold leading-tight text-gray-600 mt-2">Dati Cliente</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <!-- Nome e Cognome -->
                            <div>
                                <x-input-label for="nome" :value="__('Nome')" />
                                <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" required autofocus />
                                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="cognome" :value="__('Cognome')" />
                                <x-text-input id="cognome" class="block mt-1 w-full" type="text" name="cognome" required />
                                <x-input-error :messages="$errors->get('cognome')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <!-- Email e Cellulare -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="cellulare" :value="__('Cellulare')" />
                                <x-text-input id="cellulare" class="block mt-1 w-full" type="text" name="cellulare" pattern="[0-9]+" />
                                <x-input-error :messages="$errors->get('cellulare')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-2">
                            <!-- Residenza, Città di Residenza, CAP -->
                            <div>
                                <x-input-label for="residenza" :value="__('Residenza')" />
                                <x-text-input id="residenza" class="block mt-1 w-full" type="text" name="residenza" />
                                <x-input-error :messages="$errors->get('residenza')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="cittaResidenza" :value="__('Città di Residenza')" />
                                <x-text-input id="cittaResidenza" class="block mt-1 w-full" type="text" name="cittaResidenza" />
                                <x-input-error :messages="$errors->get('cittaResidenza')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="cap" :value="__('CAP')" />
                                <x-text-input id="cap" class="block mt-1 w-full" type="text" name="cap" />
                                <x-input-error :messages="$errors->get('cap')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Azienda Checkbox, Partita IVA, Codice SDI -->
                        <div class="grid grid-cols-3 gap-3 mb-2">
                            <div>
                                <x-input-label for="azienda" :value="__('Azienda')" />
                                <div id='azienda' class="mt-1 flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" id="azienda_si" name="azienda" value="1" class="text-indigo-600 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="toggleVisibility(true)">
                                        <span class="ml-2 mr-2 text-sm text-gray-600">{{ __('Si') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" id="azienda_no" name="azienda" value="0" class="text-indigo-600 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="toggleVisibility(false)">
                                        <span class="ml-2 mr-2 text-sm text-gray-600">{{ __('No') }}</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('azienda')" class="mt-2" />
                            </div>
                            <div id="partitaiva_field" class="hidden">
                                <x-input-label for="partitaiva" :value="__('Partita IVA')" />
                                <x-text-input id="partitaiva" class="block mt-1 w-full" type="text" name="partitaiva" />
                                <x-input-error :messages="$errors->get('partitaiva')" class="mt-2" />
                            </div>
                            <div id="codicesdi_field" class="hidden">
                                <x-input-label for="codicesdi" :value="__('Codice SDI')" />
                                <x-text-input id="codicesdi" class="block mt-1 w-full" type="text" name="codicesdi" />
                                <x-input-error :messages="$errors->get('codicesdi')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="flex justify-center text-xl font-semibold leading-tight text-gray-600 mt-4">Dati Prenotazione</h3>
                        
                        <!-- Partenza -->
                        <div class="mb-4">
                            <x-input-label for="partenza" :value="__('Indirizzo di Partenza')" />
                            <x-text-input id="partenza" class="block mt-1 w-full" type="text" name="partenza" required />
                            <x-input-error :messages="$errors->get('partenza')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="cittaPartenza" :value="__('Città di Partenza')" />
                                <x-text-input id="cittaPartenza" class="block mt-1 w-full" type="text" name="cittaPartenza" required />
                                <x-input-error :messages="$errors->get('cittaPartenza')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dataPartenza" :value="__('Data e Ora di Partenza')" />
                                <x-text-input id="dataPartenza" class="block mt-1 w-full" type="datetime-local" name="dataPartenza" required />
                                <x-input-error :messages="$errors->get('dataPartenza')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Destinazione -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="arrivo" :value="__('Indirizzo di arrivo')" />
                                <x-text-input id="arrivo" class="block mt-1 w-full" type="text" name="arrivo" required />
                                <x-input-error :messages="$errors->get('arrivo')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="cittaArrivo" :value="__('Città di arrivo')" />
                                <x-text-input id="cittaArrivo" class="block mt-1 w-full" type="text" name="cittaArrivo" required />
                                <x-input-error :messages="$errors->get('cittaArrivo')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Numero Passeggeri, Bagagli, Seggiolino -->
                        <div class="grid grid-cols-3 gap-3 mb-2">
                            <div>
                                <x-input-label for="passeggeri" :value="__('Nr Passeggeri')" />
                                <x-text-input id="passeggeri" class="block mt-1 w-full" type="number" min="0" name="passeggeri" />
                                <x-input-error :messages="$errors->get('passeggeri')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="bagagli" :value="__('Nr Bagagli')" />
                                <x-text-input id="bagagli" class="block mt-1 w-full" type="number" min="0" name="bagagli" />
                                <x-input-error :messages="$errors->get('bagagli')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="seggiolino" :value="__('Seggiolino')" />
                                <select id="seggiolino" name="seggiolino" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="">{{ __('Seleziona') }}</option>
                                    <option value="1">{{ __('Si') }}</option>
                                    <option value="0">{{ __('No') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('seggiolino')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Dati Passeggeri -->
                        <div class="md:col-span-2 mb-2">
                            <x-input-label for="datiPasseggeri" :value="__('Dati Passeggeri')" />
                            <textarea id="datiPasseggeri" name="datiPasseggeri" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3" placeholder="{{ __('Inserisci i dati degli altri passeggeri') }}"></textarea>
                            <x-input-error :messages="$errors->get('datiPasseggeri')" class="mt-2" />
                        </div>                 
                            
                        <!-- Dati Passeggeri -->
                        <div class="md:col-span-2 mb-2">
                            <x-input-label for="infoExtraNoleggio" :value="__('Informazioni aggiuntive')" />
                            <textarea id="infoExtraNoleggio" name="infoExtraNoleggio" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3" placeholder="{{ __('Inserisci eventuali informazioni aggiuntive') }}"></textarea>
                            <x-input-error :messages="$errors->get('infoExtraNoleggio')" class="mt-2" />
                        </div>

                        <!-- Pulsante di Invio -->
                        <div class="mt-4 md:col-span-2">
                            <x-primary-button class="w-full justify-center">
                                {{ 'Invia Prenotazione' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-guest-layout>

<script>
function toggleVisibility(isChecked) {
    const partitaIvaField = document.getElementById('partitaiva_field');
    const codiceSdiField = document.getElementById('codicesdi_field');
    if (isChecked) {
        partitaIvaField.style.display = 'block';
        codiceSdiField.style.display = 'block';
    } else {
        partitaIvaField.style.display = 'none';
        codiceSdiField.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const aziendaRadios = document.querySelectorAll('input[name="azienda"]');
    aziendaRadios.forEach(radio => radio.addEventListener('change', function() {
        toggleVisibility(this.value === '1');
    }));
    
    // Set initial visibility based on loaded value
    const checkedRadio = document.querySelector('input[name="azienda"]:checked');
    if (checkedRadio) {
        toggleVisibility(checkedRadio.value === '1');
    }
});
</script>