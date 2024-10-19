    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Informazioni Profilo e Aziendali') }}
            </h2>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-2 gap-2">
                <!-- Colonna Sinistra -->
                <div>
                    <!-- Indirizzo -->
                    <div>
                        <x-input-label for="indirizzo" :value="__('Indirizzo')" />
                        <x-text-input id="indirizzo" type="text" name="indirizzo" :value="old('indirizzo', $datiAzienda->indirizzo)" required class="w-full" />
                        <x-input-error :messages="$errors->get('indirizzo')" />
                    </div>

                    <!-- CAP -->
                    <div>
                        <x-input-label for="cap" :value="__('CAP')" />
                        <x-text-input id="cap" type="text" name="cap" :value="old('cap', $datiAzienda->cap)" required class="w-full" />
                        <x-input-error :messages="$errors->get('cap')" />
                    </div>

                    <!-- Città -->
                    <div>
                        <x-input-label for="citta" :value="__('Città')" />
                        <x-text-input id="citta" type="text" name="citta" :value="old('citta', $datiAzienda->citta)" required class="w-full" />
                        <x-input-error :messages="$errors->get('citta')" />
                    </div>

                    <!-- Provincia -->
                    <div>
                        <x-input-label for="provincia" :value="__('Provincia')" />
                        <x-text-input id="provincia" type="text" name="provincia" :value="old('provincia', $datiAzienda->provincia)" required class="w-full" />
                        <x-input-error :messages="$errors->get('provincia')" />
                    </div>
                </div>

                <!-- Colonna Destra -->
                <div>
                    <!-- Partita IVA -->
                    <div>
                        <x-input-label for="partita_iva" :value="__('Partita IVA')" />
                        <x-text-input id="partita_iva" type="text" name="partita_iva" :value="old('partita_iva', $datiAzienda->partita_iva)" required class="w-full" />
                        <x-input-error :messages="$errors->get('partita_iva')" />
                    </div>

                    <!-- Codice SDI -->
                    <div>
                        <x-input-label for="codice_sdi" :value="__('Codice SDI')" />
                        <x-text-input id="codice_sdi" type="text" name="codice_sdi" :value="old('codice_sdi', $datiAzienda->codice_sdi)" required class="w-full" />
                        <x-input-error :messages="$errors->get('codice_sdi')" />
                    </div>

                    <!-- Codice Fiscale -->
                    <div>
                        <x-input-label for="codice_fiscale" :value="__('Codice Fiscale')" />
                        <x-text-input id="codice_fiscale" type="text" name="codice_fiscale" :value="old('codice_fiscale', $datiAzienda->codice_fiscale)" required class="w-full" />
                        <x-input-error :messages="$errors->get('codice_fiscale')" />
                    </div>

                    <!-- Email Aziendale -->
                    <div>
                        <x-input-label for="email" :value="__('Email Aziendale')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email', $datiAzienda->email)" required class="w-full" />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <!-- Cellulare -->
                    <div>
                        <x-input-label for="cellulare" :value="__('Cellulare')" />
                        <x-text-input id="cellulare" type="text" name="cellulare" :value="old('cellulare', $datiAzienda->cellulare)" required class="w-full" />
                        <x-input-error :messages="$errors->get('cellulare')" />
                    </div>
                </div>
            </div>

            <!-- Pulsante Salva -->
            <div class="flex items-center justify-start mt-6">
                <x-primary-button>
                    {{ __('Salva Modifiche') }}
                </x-primary-button>
            </div>
        </form>
    </section>
