<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestione Autisti') }}
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
                    <!-- Pulsante Aggiungi Nuovo -->
                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('autisti.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded inline-flex items-center">
                            <x-heroicon-s-plus-circle />
                            <span class='space-from-element'>{{ __('Aggiungi Nuovo Autista') }}</span>
                        </a>
                    </div>

                    <!-- Tabella Veicoli -->
                    <div class="flex flex-col justify-center">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Autista
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Email
                                                </th>
                                                <th scope="col" class="flex justify-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Modifica
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ ucwords($user->name) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $user->email }}
                                                    </td>
                                                    <td class="flex justify-center px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('autisti.edit', $user->id) }}" class="p-1 text-indigo-600 hover:text-indigo-900"><x-fas-edit  class="h-6 w-5 mb-2" /></a>
                                                    </td>
                                                </tr>
                                            @endforeach
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
