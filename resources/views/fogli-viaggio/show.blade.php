<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Foglio di Viaggio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div id="foglio-viaggio" class="border p-2 mb-2">
                    <div class="grid grid-cols-2 mb-6">
                        <!-- Dati aziendali -->
                        <div class="text-center">
                            <p class="text-2xl font-bold mb-1">{{ $foglioViaggio->azienda->nome }}</p>
                            <p class="text-xs">
                                {{ ucwords($foglioViaggio->azienda->dati->indirizzo) }},
                                {{ ucwords($foglioViaggio->azienda->dati->cap) }} - {{ ucwords($foglioViaggio->azienda->dati->citta) }},
                                {{ ucwords($foglioViaggio->azienda->dati->provincia) }}
                            </p>
                            <p class="text-xs">
                                Cod. Fisc.: {{ strtoupper($foglioViaggio->azienda->dati->codice_fiscale) }} / P. IVA: {{ strotoupper($foglioViaggio->azienda->dati->partita_iva) }}
                            </p>
                            <p class="text-xs">
                                Tel.: {{ $foglioViaggio->azienda->dati->cellulare }} - Email: {{ $foglioViaggio->azienda->dati->email }}
                            </p>
                        </div>

                        <!-- Data e numero del foglio -->
                        <div class="flex space-x-4 justify-center items-center border p-4">
                            <div class="text-right">
                                <p>Data: {{ $foglioViaggio->updated_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-left">
                                <p>Foglio di viaggio N°: {{ $foglioViaggio->numero }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informazioni della prenotazione -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Dettagli Prenotazione</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Colonna di sinistra -->
                            <div>
                                <table class="w-full text-sm text-left text-gray-500">
                                    <tbody>
                                        <tr>
                                            <td>Cliente:</td>
                                            <td>{{ ucwords($foglioViaggio->prenotazione->nome) }} {{ ucwords($foglioViaggio->prenotazione->cognome) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Città:</td>
                                            <td>{{ ucwords($foglioViaggio->prenotazione->cittaResidenza) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Indirizzo:</td>
                                            <td>{{ ucwords($foglioViaggio->prenotazione->residenza) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td>{{ $foglioViaggio->prenotazione->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Telefono:</td>
                                            <td>{{ $foglioViaggio->prenotazione->cellulare }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Colonna di destra -->
                            <div>
                                <table class="w-full text-sm text-left text-gray-500">
                                    <tbody>
                                        <tr>
                                            <td>Luogo di partenza:</td>
                                            <td>{{ ucwords($foglioViaggio->prenotazione->partenza) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Data di partenza:</td>
                                            <td>{{ $foglioViaggio->prenotazione->dataPartenza }}</td>
                                        </tr>
                                        <tr>
                                            <td>Luogo di arrivo:</td>
                                            <td>{{ ucwords($foglioViaggio->prenotazione->arrivo) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Passeggeri:</td>
                                            <td>{{ $foglioViaggio->prenotazione->passeggeri }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Dettagli del viaggio e del veicolo -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold mb-2">Dettagli del Viaggio e Veicolo</h3>
                        <table class="w-full text-sm text-left text-gray-500">
                            <tbody>
                                <tr>
                                    <td>Veicolo:</td>
                                    <td>{{ ucwords($foglioViaggio->veicolo->modello) }}</td>
                                    <td>Targa:</td>
                                    <td>{{ strtoupper($foglioViaggio->veicolo->targa) }}</td>
                                </tr>
                                <tr>
                                    <td>Autista:</td>
                                    <td>{{ ucwords($foglioViaggio->prenotazione->utente->name) }}</td>
                                    <td>Km iniziali:</td>
                                    <td>{{ $foglioViaggio->kmIniziali }}</td>
                                </tr>
                                <tr>
                                    <td>Km finali:</td>
                                    <td>{{ $foglioViaggio->kmFinali }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            <div class="text-center m-4">
                <button onclick="window.print();" class="p-2 bg-gray-300 hover:bg-gray-400 rounded">Stampa questo foglio</button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #foglio-viaggio, #foglio-viaggio * {
                visibility: visible;
            }
            #foglio-viaggio {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</x-app-layout>

