# SorianoService - Gestionale per Noleggio con Conducente (NCC)

SorianoService è un gestionale dedicato alle aziende che offrono servizi di **noleggio con conducente** (NCC). Il sistema permette la gestione delle prenotazioni, flotte di veicoli, fogli di viaggio e ruoli degli utenti con un'interfaccia intuitiva e funzionalità avanzate.

## Caratteristiche principali

- **Gestione delle prenotazioni**: Crea, modifica e condividi prenotazioni per i servizi di trasporto.
- **Ruoli e permessi personalizzabili**: Ruoli specifici per amministratori, aziende e autisti con gestione granulare dei permessi.
- **Gestione delle aziende**: Ogni azienda può registrare informazioni sui propri veicoli e autisti.
- **Fogli di viaggio**: Tieni traccia dei viaggi, chilometri percorsi e dettagli delle corse.
- **Condivisione delle prenotazioni**: Prenotazioni condivisibili e accettabili da altri utenti nel sistema.
- **Integrazione con calendari**: Visualizzazione e gestione delle prenotazioni tramite FullCalendar.
- **Interfaccia moderna e responsiva**: Progettata per garantire un'esperienza fluida su dispositivi desktop e mobile.

## Requisiti di sistema

- **PHP >= 8.3.8**
- **Composer**
- **Laravel >= 11.x**
- **Node.js >= 22.x**
- **npm >= 10.x**
- **Database MySQL/MariaDB** o altro database compatibile con Laravel

## Installazione

1. Clona il repository:
   ```bash
   git clone https://github.com/Nastu94/SorianoService.git
   ```

2. Entra nella directory del progetto:
   ```bash
   cd SorianoService
   ```

3. Installa le dipendenze PHP utilizzando Composer:
   ```bash
   composer install
   ```

4. Installa le dipendenze JavaScript utilizzando npm:
   ```bash
   npm install
   ```

5. Crea il file `.env` copiando il file di esempio:
   ```bash
   cp .env.example .env
   ```

6. Configura il database nel file `.env`:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nome_database
   DB_USERNAME=nome_utente
   DB_PASSWORD=password
   ```

7. Genera la chiave dell'applicazione:
   ```bash
   php artisan key:generate
   ```

8. Esegui le migrazioni per creare le tabelle nel database:
   ```bash
   php artisan migrate
   ```

9. Avvia il server locale:
   ```bash
   php artisan serve
   ```

## Funzionalità future

- **Gestione della fatturazione e dei costi**: Sistema per la generazione di fatture e la gestione dei costi delle prenotazioni.
- **Integrazione con Google Maps**: Geolocalizzazione dei veicoli, dettagli sui percorsi e navigazione integrata per gli autisti.
- **Gestione dei pagamenti**: Integrazione con piattaforme di pagamento per la gestione delle tariffe.
- **Notifiche via email e SMS**: Avvisi automatici per i clienti e gli autisti.
- **Dashboard avanzata**: Visualizzazione di reportistica, analisi delle performance aziendali e statistiche sui viaggi.

## Contribuire

Attualmente il progetto non accetta contributi esterni, ma siamo aperti a suggerimenti e idee. Se desideri proporre una nuova funzionalità o miglioramento, sentiti libero di contattare l'autore tramite [GitHub](https://github.com/Nastu94).

## Licenza

Questo progetto è di proprietà dell'autore. La distribuzione, modifica e utilizzo del codice sono soggetti a specifiche condizioni imposte dall'autore. Per ulteriori dettagli, consulta il file [LICENSE](LICENSE) incluso nel progetto.


