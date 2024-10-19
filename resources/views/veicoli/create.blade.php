<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Aggiungi Nuovo Veicolo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('veicoli.store') }}">
                        @csrf

                        <!-- Targa -->
                        <div>
                            <x-input-label for="targa" :value="__('Targa')" />
                            <x-text-input id="targa" class="block mt-1 w-full" type="text" name="targa" :value="old('targa')" required autofocus />
                            <x-input-error :messages="$errors->get('targa')" class="mt-2" />
                        </div>

                        <!-- Modello Veicolo -->
                        <div class="mt-4">
                            <x-input-label for="modello" :value="__('Modello Veicolo')" />
                            <x-text-input id="modello" class="block mt-1 w-full" type="text" name="modello" :value="old('modello')" required />
                            <x-input-error :messages="$errors->get('modello')" class="mt-2" />
                        </div>

                        <!-- Km Percorsi -->
                        <div class="mt-4">
                            <x-input-label for="kmPercorsi" :value="__('Km Percorsi')" />
                            <x-text-input id="kmPercorsi" class="block mt-1 w-full" type="number" name="kmPercorsi" :value="old('kmPercorsi')" required />
                            <x-input-error :messages="$errors->get('kmPercorsi')" class="mt-2" />
                        </div>

                        <!-- Pulsante di Invio -->
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Aggiungi Veicolo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
