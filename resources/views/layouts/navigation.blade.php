<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 custom-lg:-my-px custom-lg:ms-10 custom-lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <!-- Utente Azienda e Azienda -->
                    @if(Auth::user()->permessi->contains('nome', 'Amministratore'))
                        <x-nav-link :href="route('admin.create-user')" :active="request()->routeIs('autisti.index')">
                            {{ __('Utenti Azienda') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.create-azienda')" :active="request()->routeIs('veicoli.index')">
                            {{ __('Aziende') }}
                        </x-nav-link>
                    @endif
                    <!-- Autisti e Veicoli per Azienda -->
                    @if(Auth::user()->permessi->contains('nome', 'Azienda'))
                        <x-nav-link :href="route('autisti.index')" :active="request()->routeIs('autisti.index')">
                            {{ __('Autisti') }}
                        </x-nav-link>
                        <x-nav-link :href="route('veicoli.index')" :active="request()->routeIs('veicoli.index')">
                            {{ __('Veicoli') }}
                        </x-nav-link>
                    @endif
                    <!-- Prenotazioni Dropdown -->
                    @if(!Auth::user()->permessi->contains('nome', 'Amministratore'))
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-sm custom-leading font-medium rounded-md text-gray-500 dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                Prenotazioni
                                <svg class="ms-2 -me-1 h-5 w-5" fill="none" stroke="currentColor" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none" style="display: none;">
                                <div class="py-1">
                                    <x-dropdown-link :href="route('prenotazioni.create')">Gestisci</x-dropdown-link>
                                    <x-dropdown-link :href="route('prenotazioni.index-personali')">Personali</x-dropdown-link>
                                    <x-dropdown-link :href="route('prenotazioni.index-condivise')">Condivise</x-dropdown-link>
                                    <x-dropdown-link :href="route('prenotazioni.index-aziendali')">Aziendali</x-dropdown-link>
                                    <x-dropdown-link :href="route('prenotazioni.index-globali')">Globali</x-dropdown-link>
                                </div>
                            </div>
                        </div>                        
                        <x-nav-link :href="route('registro.index', ['data' => \Carbon\Carbon::now()->format('Y-m-d')])">
                            {{ __('Registro') }}
                        </x-nav-link>
                        <x-nav-link :href="route('fogli-viaggio.index')">
                            {{ __('Fogli di Viaggio') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden custom-lg:flex custom-lg:items-center custom-lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profilo') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center custom-lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden custom-lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <!-- Dropdown for Prenotazioni -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex justify-between w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-400 focus:outline-none">
                    Prenotazioni
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" class="space-y-1">
                    <x-responsive-nav-link :href="route('prenotazioni.create')">
                        Gestisci
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('prenotazioni.index-personali')">
                        Personali
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('prenotazioni.index-condivise')">
                        Condivise
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('prenotazioni.index-aziendali')">
                        Aziendali
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('prenotazioni.index-globali')">
                        Globali
                    </x-responsive-nav-link>
                    <hr>
                </div>
            </div>
            <!-- Autisti and Veicoli Links -->
            @if(Auth::user()->permessi->contains('nome', 'Azienda'))
                <x-responsive-nav-link :href="route('autisti.index')" :active="request()->routeIs('autisti.index')">
                    {{ __('Autisti') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('veicoli.index')" :active="request()->routeIs('veicoli.index')">
                    {{ __('Veicoli') }}
                </x-responsive-nav-link>
            @endif
            <!-- Utente Azienda e Azienda -->
            @if(Auth::user()->permessi->contains('nome', 'Amministratore'))
                <x-nav-link :href="route('admin.create-user')" :active="request()->routeIs('autisti.index')">
                    {{ __('Utenti Azienda') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.create-azienda')" :active="request()->routeIs('veicoli.index')">
                    {{ __('Aziende') }}
                </x-nav-link>
            @endif
            <x-responsive-nav-link :href="route('registro.index', ['data' => \Carbon\Carbon::now()->format('Y-m-d')])">
                {{ __('Registro') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('fogli-viaggio.index')">
                {{ __('Fogli di Viaggio') }}
            </x-responsive-nav-link>
        </div>
        
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profilo') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
