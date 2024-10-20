<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profilo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($user->permessi->contains('nome', 'Azienda') && $datiAzienda)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xxl">
                        @include('profile.partials.update-profile-information-form', ['azienda' => $datiAzienda])
                    </div>
                </div>
            @endif

            @if(Auth::user()->permessi->contains('nome', 'Autista'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @if(empty(Auth::user()->public_token))
                            <form action="{{ route('profile.generate_token') }}" method="POST">
                                @csrf
                                <button type="submit" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700 focus:outline-none">
                                    Genera Link Personale
                                </button>
                            </form>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Il tuo Link Personale:</label>
                                <div class="flex mt-1">
                                    <input type="text" id="personalLink" readonly class="bg-gray-100 border border-gray-300 rounded-l-md shadow-sm p-2 flex-grow" value="{{ route('prenotazioni.esterna.create', ['token' => Auth::user()->public_token]) }}">
                                    <button onclick="copyToClipboard()" class="bg-blue-500 hover:bg-blue-600 rounded-r-md shadow-sm p-2 text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-2m-2 0a2 2 0 002 2H6a2 2 0 01-2-2V7a2 2 0 012-2h2m8 0a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2V7a2 2 0 012-2h2z"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <script>
                function copyToClipboard() {
                    var copyText = document.getElementById("personalLink");
                    copyText.select(); // Seleziona il testo
                    copyText.setSelectionRange(0, 99999); // Per dispositivi mobile
                    document.execCommand("copy"); // Copia il testo negli appunti

                    // Opzionale: Mostra un messaggio di conferma
                    alert("Link copiato negli appunti: " + copyText.value);
                }
                </script>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
