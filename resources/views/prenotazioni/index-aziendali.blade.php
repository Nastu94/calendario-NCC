<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ 'Prenotazioni Aziendali' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-gray-900 rounded-lg">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.calendarEvents = @json($prenotazioniPerGiorno);
    </script>

</x-app-layout>