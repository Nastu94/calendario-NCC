{{-- resources/views/fogli-viaggio/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Fogli di Viaggio
        </h2>
        
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('fogli-viaggio.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded inline-flex items-center">
                            <x-heroicon-s-plus-circle />
                            <span class='space-from-element'>{{ __('Aggiungi Foglio di Viaggio') }}</span>
                        </a>
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ID Prenotazione - Cliente
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Numero Foglio
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Veicolo
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Km Iniziali
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Km Finali
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Azioni
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse ($fogliViaggio as $foglio)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $foglio->prenotazione_id }} - {{ ucwords($foglio->prenotazione->nome) }} {{ ucwords($foglio->prenotazione->cognome) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $foglio->numero }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ strtoupper($foglio->veicolo->targa) . ' - ' . ucwords($foglio->veicolo->modello) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $foglio->kmIniziali }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $foglio->kmFinali }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex items-center justify-center">
                                                        <a href="{{ route('fogli-viaggio.show', $foglio->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            <x-heroicon-s-eye class="w-6 h-6" />
                                                        </a>
                                                        @if( !$foglio->numero )
                                                            <a href="{{ route('fogli-viaggio.edit', $foglio->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">
                                                                <x-heroicon-s-pencil-square class="w-6 h-6" />
                                                            </a>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center">
                                                        Nessun foglio di viaggio trovato
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

    @php
    //dd($fogliViaggio);
    @endphp