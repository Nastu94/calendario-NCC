<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ 'Aggiorna Prenotazione' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="form-aggiorna_prenotazione" method="POST" action="{{ route('prenotazioni.update', $prenotazione->id) }}">
                        @csrf
                        @method('PUT')

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
                        
                        <h3 class="flex justify-center text-xl font-semibold leading-tight text-gray-600 mt-2">Dati Cliente</h3>
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <!-- Nome -->
                            <div>
                                <x-input-label for="nome" :value="__('Nome')" />
                                <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome', $prenotazione->nome)" required autofocus />
                                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                            </div>

                            <!-- Cognome -->
                            <div>
                                <x-input-label for="cognome" :value="__('Cognome')" />
                                <x-text-input id="cognome" class="block mt-1 w-full" type="text" name="cognome" :value="old('cognome', $prenotazione->cognome)" required />
                                <x-input-error :messages="$errors->get('cognome')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $prenotazione->email)"  />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Cellulare -->
                            <div>
                                <x-input-label for="cellulare" :value="__('Cellulare')" />
                                <x-text-input id="cellulare" class="block mt-1 w-full" type="text" name="cellulare" :value="old('cellulare', $prenotazione->cellulare)" pattern="[0-9]+" />
                                <x-input-error :messages="$errors->get('cellulare')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-2">
                            <!-- Residenza, Città, CAP -->
                            <div>
                                <x-input-label for="residenza" :value="__('Residenza')" />
                                <x-text-input id="residenza" type="text" name="residenza" :value="old('residenza', $prenotazione->residenza)" class="block mt-1 w-full"  />
                                <x-input-error :messages="$errors->get('residenza')" />
                            </div>
                            <div>
                                <x-input-label for="cittaResidenza" :value="__('Città di Residenza')" />
                                <x-text-input id="cittaResidenza" type="text" name="cittaResidenza" :value="old('cittaResidenza', $prenotazione->cittaResidenza)" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('cittaResidenza')" />
                            </div>
                            <div>
                                <x-input-label for="cap" :value="__('CAP')" />
                                <x-text-input id="cap" type="text" name="cap" :value="old('cap', $prenotazione->cap)" class="block mt-1 w-full"  />
                                <x-input-error :messages="$errors->get('cap')" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-3 mb-2">
                            <!-- Azienda Checkbox -->
                            <div>
                                <x-input-label for="azienda" :value="__('Azienda')" />
                                <div id='azienda' class="mt-1 flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" id="azienda_si" name="azienda" value="1" class="text-indigo-600 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @if($prenotazione->azienda == 1) checked @endif onchange="toggleVisibility(true)">
                                        <span class="ml-2 mr-2 text-sm text-gray-600">{{ __('Si') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" id="azienda_no" name="azienda" value="0" class="text-indigo-600 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @if($prenotazione->azienda == 0) checked @endif onchange="toggleVisibility(false)">
                                        <span class="ml-2 mr-2 text-sm text-gray-600">{{ __('No') }}</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('azienda')" class="mt-2" />
                            </div>

                            <!-- Partita IVA -->
                            <div id="partitaiva_field" class="hidden">
                                <x-input-label for="partitaiva" :value="__('Partita IVA')"/>
                                <x-text-input id="partitaiva" class="block mt-1 w-full" type="text" name="partitaiva" :value="old('partitaiva', $prenotazione->partitaiva)"  />
                                <x-input-error :messages="$errors->get('partitaiva')" class="mt-2" />
                            </div>
                            
                            <!-- Codice SDI -->
                            <div id="codicesdi_field" class="hidden">
                                <x-input-label for="codicesdi" :value="__('Codice SDI')" />
                                <x-text-input id="codicesdi" class="block mt-1 w-full" type="text" name="codicesdi" :value="old('codicesdi', $prenotazione->codicesdi)" />
                                <x-input-error :messages="$errors->get('codicesdi')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="flex justify-center text-xl font-semibold leading-tight text-gray-600 mt-4">Dati Prenotazione</h3>
                        <!-- Indirizzo di Partenza -->
                        <div class="mb-2">
                            <x-input-label for="partenza" :value="__('Indirizzo di Partenza')" />
                            <x-text-input id="partenza" class="block mt-1 w-full" type="text" name="partenza" :value="old('partenza', $prenotazione->partenza)" required />
                            <x-input-error :messages="$errors->get('partenza')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <!-- Città di Partenza -->
                            <div>
                                <x-input-label for="cittaPartenza" :value="__('Città di Partenza')" />
                                <x-text-input id="cittaPartenza" class="block mt-1 w-full" type="text" name="cittaPartenza" :value="old('cittaPartenza', $prenotazione->cittaPartenza)" required />
                                <x-input-error :messages="$errors->get('cittaPartenza')" class="mt-2" />
                            </div>

                            <!-- Data e Ora di Partenza -->
                            <div>
                                <x-input-label for="dataPartenza" :value="__('Data di Partenza')" />
                                <x-text-input id="dataPartenza" class="block mt-1 w-full" type="datetime-local" name="dataPartenza" :value="old('dataPartenza', $prenotazione->dataPartenza)" />
                                <x-input-error :messages="$errors->get('dataPartenza')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <!-- Indirizzo di Arrivo -->
                            <div>
                                <x-input-label for="arrivo" :value="__('Indirizzo di arrivo')" />
                                <x-text-input id="arrivo" class="block mt-1 w-full" type="text" name="arrivo" :value="old('arrivo', $prenotazione->arrivo)" required />
                                <x-input-error :messages="$errors->get('arrivo')" class="mt-2" />
                            </div>

                            <!-- Città di Arrivo -->
                            <div>
                                <x-input-label for="cittaArrivo" :value="__('Città di arrivo')" />
                                <x-text-input id="cittaArrivo" class="block mt-1 w-full" type="text" name="cittaArrivo" :value="old('cittaArrivo', $prenotazione->cittaArrivo)" required />
                                <x-input-error :messages="$errors->get('cittaArrivo')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="flex justify-center text-xl font-semibold leading-tight text-gray-600 mt-4">Info Prenotazione</h3>
                        <div class="grid grid-cols-3 gap-3 mb-2">
                            <!-- Numero Passeggeri -->
                            <div>
                                <x-input-label for="passeggeri" :value="__('Nr Passeggeri')" />
                                <x-text-input id="passeggeri" class="block mt-1 w-full" type="text" name="passeggeri" :value="old('passeggeri', $prenotazione->passeggeri)" />
                                <x-input-error :messages="$errors->get('passeggeri')" class="mt-2" />
                            </div>

                            <!-- Numero Bagagli -->
                            <div>
                                <x-input-label for="bagagli" :value="__('Nr Bagagli')" />
                                <x-text-input id="bagagli" class="block mt-1 w-full" type="text" name="bagagli" :value="old('bagagli', $prenotazione->bagagli)" />
                                <x-input-error :messages="$errors->get('bagagli')" class="mt-2" />
                            </div>

                            <!-- Seggiolino -->
                            <div>
                                <x-input-label for="seggiolino" :value="__('Seggiolino')" />
                                <select id="seggiolino" name="seggiolino" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="">{{ __('Seleziona') }}</option>
                                    <option value="1" @if($prenotazione->seggiolino == '1') selected @endif>{{ __('Si') }}</option>
                                    <option value="0" @if($prenotazione->seggiolino == '0') selected @endif>{{ __('No') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('seggiolino')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Dati Passeggeri -->
                        <div class="md:col-span-2 mb-2">
                            <x-input-label for="datiPasseggeri" :value="__('Dati Passeggeri')" />
                            <textarea id="datiPasseggeri" name="datiPasseggeri" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3" placeholder="{{ __('Inserisci i dati degli altri passeggeri') }}" >{{ old('datiPasseggeri', $prenotazione->datiPasseggeri) }}</textarea>
                            <x-input-error :messages="$errors->get('datiPasseggeri')" class="mt-2" />
                        </div>

                        <!-- Codice Prenotazione Esterno, Gestione Prenotazione -->
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <div>
                                <x-input-label for="codiceEsterno" :value="__('Codice Prenotazione Esterno')" />
                                <x-text-input id="codiceEsterno" class="block mt-1 w-full" type="text" name="codiceEsterno" :value="old('codiceEsterno', $prenotazione->codiceEsterno)" />
                                <x-input-error :messages="$errors->get('codiceEsterno')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="gestionePrenotazione" :value="__('Gestione Prenotazione')" />
                                <select id="gestionePrenotazione" name="gestionePrenotazione" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="personale" @if($prenotazione->gestionePrenotazione == 'personale') selected @endif>{{ __('Gestisci prenotazione') }}</option>
                                    <option value="condivisa" @if($prenotazione->gestionePrenotazione == 'condivisa') selected @endif>{{ __('Condividi prenotazione') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('gestionePrenotazione')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Informazioni aggiuntive -->
                        <div class="md:col-span-2 mb-2">
                            <x-input-label for="infoExtraNoleggio" :value="__('Informazioni aggiuntive')" />
                            <textarea id="infoExtraNoleggio" name="infoExtraNoleggio" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3" placeholder="{{ __('Inserisci eventuali informazioni aggiuntive') }}" >{{ old('infoExtraNoleggio', $prenotazione->infoExtraNoleggio) }}</textarea>
                            <x-input-error :messages="$errors->get('infoExtraNoleggio')" class="mt-2" />
                        </div>

                        <!-- Pulsante di Invio -->
                        <div class="mt-4 md:col-span-2">
                            <x-primary-button class="w-full justify-center">
                                {{ 'Aggiorna Prenotazione' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const aziendaRadios = document.querySelectorAll('input[name="azienda"]');
    aziendaRadios.forEach(radio => radio.addEventListener('change', function() {
        toggleVisibility(this.value === '1');
    }));
    
    // Set initial visibility based on loaded value
    const checkedRadio = document.querySelector('input[name="azienda"]:checked');
    toggleVisibility(checkedRadio && checkedRadio.value === '1');
});

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

</script>