# Documentazione Completa del Progetto (Basata sul Codice Fornito)

Questo documento descrive l'architettura del sistema di gestione campagne pubblicitarie, utilizzando il modello C4 per la visualizzazione dell'architettura software e una descrizione della struttura del database (precedentemente rappresentata da un diagramma Entità-Relazione).

## Struttura del Database (Descrizione Entità-Relazione)

Il database è composto dalle seguenti entità principali con le loro relazioni:

* **USERS (Utenti):** Memorizza le informazioni degli inserzionisti registrati.
    * **Attributi Chiave:** `id` (PK), `first_name`, `last_name`, `email` (Univoca), `password` (Hashed), `dob`, `company_name`, `address`, `country`, `city`, `zip_code`, `balance`, `email_verified_at`, `remember_token`, `two_factor_recovery_codes`, `two_factor_secret`, `profile_photo_url` (attributo appeso generato da Jetstream).
    * **Relazioni:**
        * Un `User` può possedere molte `Creatives`.
        * Un `User` può creare e gestire molte `Campaigns`.
        * Un `User` può effettuare molti `Fundings`.

* **GUEST_USERS (Utenti Ospiti):** Traccia informazioni anonime sui visitatori che interagiscono con gli annunci.
    * **Attributi Chiave:** `id` (PK), `ip`, `user_agent`, `country`, `city`, `isp`, `device_os` (enum), `device_type` (enum), `device_browser`, `device_language`, `keywords` (JSON).

* **CREATIVES (Creatività):** Rappresenta gli asset grafici (immagini) utilizzati nelle campagne.
    * **Attributi Chiave:** `id` (PK), `name`, `path` (percorso del file), `user_id` (FK a `USERS`).
    * **Relazioni:**
        * Appartiene a un `User`.
        * Può essere utilizzata (opzionalmente) da una o più `Campaigns`.

* **CAMPAIGNS (Campagne):** Contiene i dettagli delle campagne pubblicitarie create dagli utenti.
    * **Attributi Chiave:** `id` (PK), `name`, `status` (enum), `ad_title`, `ad_description`, `device` (enum per targeting), `ad_category` (enum), `geo_targeting`, `isp_targeting`, `os_targeting` (enum), `browser_targeting` (enum), `browser_language_targeting`, `keyword_targeting` (JSON di array), `max_bid`, `start_date`, `end_date`, `daily_budget`, `target_url`, `user_id` (FK a `USERS`), `creative_id` (FK a `CREATIVES`, nullable).
    * **Relazioni:**
        * Appartiene a un `User`.
        * Può utilizzare (opzionalmente) una `Creative`.
        * Registra molte `DailyPerformance`.

* **DAILY_PERFORMANCE (Performance Giornaliere):** Memorizza le statistiche giornaliere di ogni campagna.
    * **Attributi Chiave:** `id` (PK), `date`, `impressions`, `clicks`, `conversions`, `cost`, `campaign_id` (FK a `CAMPAIGNS`).
    * **Relazioni:**
        * Appartiene a una `Campaign`.

* **FUNDINGS (Finanziamenti):** Registra le transazioni di finanziamento degli account utente.
    * **Attributi Chiave:** `id` (PK), `amount`, `user_id` (FK a `USERS`), `status` (enum: 'unpaid', 'paid', 'failed'), `session_id` (es. per Stripe).
    * **Relazioni:**
        * Appartiene a un `User`.

## Documentazione C4

### Livello 1: Contesto del Sistema (System Context)

Il "Sistema di Gestione Campagne Pubblicitarie Laravel" opera all'interno di un ecosistema che include diversi attori e sistemi esterni.

**Descrizione degli Elementi di Contesto:**

* **Utente Inserzionista (Advertiser):** L'attore principale. È una persona o un'azienda che si registra sulla piattaforma per creare, finanziare, gestire e monitorare le proprie campagne pubblicitarie. Interagisce con il sistema principalmente tramite un'interfaccia web fornita dall'applicazione Laravel, utilizzando il protocollo HTTPS per comunicazioni sicure.

* **Sistema di Gestione Campagne Pubblicitarie Laravel (Questo Sistema):** È l'applicazione software al centro di questa documentazione, costruita utilizzando il framework Laravel. Le sue responsabilità principali includono fornire l'interfaccia agli inserzionisti, gestire la logica di business delle campagne, l'autenticazione, l'autorizzazione, e orchestrare le interazioni con il database e i servizi esterni.

* **Utente Visitatore (Guest User):** L'utente finale che visualizza gli annunci pubblicitari erogati dalla piattaforma su vari siti o applicazioni. Il sistema traccia informazioni anonime su questi utenti (come IP, user agent, dispositivo, ecc., memorizzate nell'entità `GuestUser`) per scopi di targeting degli annunci e per l'analisi delle performance delle campagne. L'interazione avviene tramite protocollo HTTP/HTTPS, a seconda di come gli annunci vengono serviti.

* **Sistema di Pagamento Esterno (es. Stripe):** Un servizio di terze parti (come Stripe, dedotto dalla presenza del file `stripe_checkout.blade.php` e del campo `session_id` nella tabella `Fundings`) che gestisce in modo sicuro le transazioni finanziarie. L'applicazione Laravel interagisce con questo sistema tramite API (tipicamente HTTPS/JSON) per processare i pagamenti quando gli inserzionisti aggiungono fondi ai loro account.

* **Google Places API (Servizio Esterno SaaS):** Un servizio fornito da Google, utilizzato dall'applicazione per funzionalità di geolocalizzazione, come l'autocompletamento degli indirizzi o la selezione di aree geografiche per il targeting delle campagne (come indicato dalla variabile d'ambiente `PLACES_API_KEY` e dal codice JavaScript nei file Blade). L'interazione avviene tramite API HTTPS/JSON.

* **Utente Amministratore (Ipotetico):** Sebbene non esplicitamente definito nel codice fornito, un sistema di questa natura di solito include un ruolo amministrativo. Questo utente sarebbe responsabile della gestione generale della piattaforma, della supervisione degli utenti, delle configurazioni di sistema, e potrebbe interagire tramite un pannello di amministrazione separato (non visibile nel codice attuale).

### Livello 2: Contenitori (Containers)

Il "Sistema di Gestione Campagne Pubblicitarie Laravel" è composto da diversi contenitori principali, che sono unità deployabili o eseguibili in modo indipendente.

**Descrizione dei Contenitori:**

1.  **Applicazione Web (WebApp):**
    * **Descrizione:** È il componente server-side principale, un'applicazione web monolitica sviluppata con il framework Laravel. Gira su un web server come Nginx o Apache.
    * **Tecnologie Chiave:** PHP (versione 8.x come da type hinting moderni), Laravel Framework, Blade (template engine), Eloquent (ORM).
    * **Responsabilità Principali:**
        * Fornire l'interfaccia utente (UI) dinamica agli Inserzionisti attraverso pagine HTML generate da Blade, arricchite con CSS e JavaScript per l'interattività.
        * Gestire l'autenticazione e l'autorizzazione degli utenti (sfruttando i componenti di Laravel come Fortify e Jetstream, come si evince dal modello `User`).
        * Implementare tutta la logica di business relativa alla creazione, modifica, targeting, avvio/pausa e monitoraggio delle campagne pubblicitarie.
        * Orchestrare le interazioni con gli altri contenitori (Database, File Storage) e con i sistemi esterni (Google Places API, Stripe API).
        * Esporre eventuali API interne necessarie per funzionalità client-side avanzate o per future estensioni.
        * Gestire il tracciamento dei dati dei `GuestUser`.

2.  **Database Relazionale (Database):**
    * **Descrizione:** Un server di database che ospita un database SQL, responsabile della persistenza di tutti i dati strutturati dell'applicazione.
    * **Tecnologie Chiave:** Un sistema di gestione di database relazionale (RDBMS) come MySQL, PostgreSQL, o SQLite (quest'ultimo spesso usato come default da Laravel per lo sviluppo).
    * **Responsabilità Principali:**
        * Memorizzare in modo sicuro, strutturato e persistente tutte le informazioni dell'applicazione, incluse le tabelle `users`, `guest_users`, `creatives`, `campaigns`, `daily_performance`, e `fundings`.
        * Garantire l'integrità dei dati attraverso l'uso di chiavi primarie, chiavi esterne, vincoli di unicità e altri vincoli definiti nelle migration.
        * Eseguire query complesse richieste dall'Applicazione Web tramite l'ORM Eloquent.

3.  **File Storage (Storage):**
    * **Descrizione:** Un sistema dedicato alla memorizzazione dei file binari, in questo caso principalmente le immagini delle creatività pubblicitarie caricate dagli Inserzionisti.
    * **Tecnologie Chiave:** Basandosi sul codice (`asset('storage/...')` e l'uso di `enctype="multipart/form-data"` nei form di upload), è molto probabile che si tratti del filesystem locale del server su cui gira l'Applicazione Web. Specificamente, la directory `storage/app/public` di Laravel, che viene resa accessibile pubblicamente tramite un link simbolico a `public/storage`. In un ambiente di produzione scalabile, questo potrebbe essere sostituito o affiancato da un servizio di cloud storage (es. AWS S3, Google Cloud Storage), ma il codice attuale punta a una soluzione basata su filesystem locale.
    * **Responsabilità Principali:**
        * Memorizzare in modo sicuro i file delle creatività (immagini).
        * Permettere all'Applicazione Web di scrivere nuovi file (upload) e di leggerli per servirli come parte degli annunci visualizzati agli Utenti Visitatori.

### Livello 3: Componenti (Components)

All'interno del contenitore "Applicazione Web", possiamo identificare diversi componenti logici principali, che collaborano per fornire le funzionalità del sistema. Questi sono tipicamente moduli o insiemi di classi con responsabilità specifiche, seguendo i pattern comuni di Laravel.

**Descrizione dei Componenti (all'interno dell'Applicazione Web):**

* **Browser dell'Inserzionista (Logica Client-side):**
    * **Tecnologia:** HTML, CSS, JavaScript (eseguito nel browser dell'utente).
    * **Responsabilità:** Non è un componente server-side, ma rappresenta la logica eseguita sul client. Interagisce con i componenti server-side. Renderizza l'interfaccia utente ricevuta dal server, gestisce le interazioni dell'utente (click, input nei form), esegue validazioni client-side per migliorare l'esperienza utente (es. controllo della dimensione dei file immagine prima dell'upload, validazione del formato delle date), e gestisce chiamate JavaScript a API esterne (come Google Places Autocomplete API per il campo `geo_targeting`).

* **Viste Blade (`resources/views/...`):**
    * **Tecnologia:** Template engine Blade di Laravel.
    * **Responsabilità:** Componenti responsabili della presentazione. Generano dinamicamente l'HTML che viene inviato al browser dell'Inserzionista. Ricevono dati dai Controller e li visualizzano in modo strutturato, utilizzando la sintassi di Blade per includere logica di visualizzazione, cicli, condizionali e layout. Definiscono i form HTML per la raccolta dell'input utente.

* **Gestore Routing (`routes/web.php`, `routes/api.php`):**
    * **Tecnologia:** Sistema di routing di Laravel.
    * **Responsabilità:** Mappare le richieste HTTP in entrata (definite da URL e metodo HTTP) ai metodi appropriati dei Controller. Definisce gli endpoint dell'applicazione.

* **Middleware (`App/Http/Middleware/*`, `App/Http/Kernel.php`):**
    * **Tecnologia:** Classi Middleware di Laravel.
    * **Responsabilità:** Agiscono come filtri per le richieste HTTP. Vengono eseguiti prima o dopo che una richiesta raggiunga il Controller designato. Utilizzati per compiti trasversali come l'autenticazione (verificare se un utente è loggato), la protezione CSRF (Cross-Site Request Forgery), la gestione delle sessioni, la verifica dei ruoli utente, e la manipolazione degli header della richiesta/risposta.

* **Controller (`App/Http/Controllers/...`):**
    * **Tecnologia:** Classi Controller PHP di Laravel.
    * **Responsabilità:** Ricevono l'input dalle richieste HTTP (inoltrate dal Gestore Routing e processate dai Middleware). Orchestrano le azioni da compiere: interagiscono con i Modelli Eloquent per recuperare o persistere dati nel Database, possono invocare Servizi Applicativi per logica di business più complessa, e infine passano i dati necessari alle Viste Blade per il rendering della risposta. Basandosi sui file forniti, si possono identificare gruppi logici di controller:
        * `AuthController` (implicito dall'uso di Laravel Fortify/Jetstream): gestisce la registrazione, il login, il logout, la gestione del profilo utente, e la funzionalità di autenticazione a due fattori.
        * `CampaignController`: gestisce le operazioni CRUD (Create, Read, Update, Delete) per le campagne pubblicitarie, inclusa la gestione dello stato (attiva, in pausa), l'associazione delle creatività e la configurazione del targeting.
        * `PaymentController` (o `FundingController`): gestisce la visualizzazione dello storico dei finanziamenti, l'inizializzazione del processo di checkout con il sistema di pagamento esterno (Stripe), e la gestione delle relative callback o webhook.
        * `SettingsController`: permette agli utenti di aggiornare le proprie informazioni personali e aziendali.
        * `DashboardController`: raccoglie e prepara i dati aggregati (statistiche sulle campagne, saldo dell'account) per la visualizzazione nella dashboard principale dell'inserzionista.

* **Modelli Eloquent (`App/Models/...`):**
    * **Tecnologia:** ORM (Object-Relational Mapper) Eloquent di Laravel.
    * **Responsabilità:** Rappresentano le tabelle del Database come oggetti PHP, fornendo un'interfaccia orientata agli oggetti per interagire con i dati. Gestiscono le query al database, definiscono le relazioni tra le diverse entità (es. un `User` "ha molte" `Campaigns`), e possono includere logica per la manipolazione degli attributi (mutators, accessors, casting di tipi, gestione degli attributi `fillable` e `hidden`). I modelli identificati sono: `User`, `GuestUser`, `Creative`, `Campaign`, `DailyPerformance`, `Funding`.

* **Servizi Applicativi / Logica di Business Ausiliaria:**
    * **Tecnologia:** Classi PHP standard, Traits, Event Listeners, Jobs, ecc.
    * **Responsabilità:** Incapsulano logica di business specifica, complessa o riutilizzabile che non appartiene strettamente a un singolo Controller o Modello, promuovendo un design più pulito e testabile. Esempi basati sul codice:
        * **Integrazione Google Places API:** Potrebbe esistere una classe servizio dedicata a gestire le chiamate all'API di Google Places, l'interpretazione delle risposte e la formattazione dei dati per il targeting geografico.
        * **Integrazione Stripe API:** Similmente, un servizio potrebbe gestire la creazione di sessioni di checkout Stripe, la gestione sicura delle chiavi API, e il processamento dei webhook inviati da Stripe per confermare pagamenti riusciti o falliti, aggiornando di conseguenza i record `Fundings` e il `balance` dell'utente.
        * Altra logica di dominio, come calcoli di statistiche avanzate (oltre al semplice CTR), gestione di notifiche, ecc.

### Livello 4: Codice (Code)

Questo livello si riferisce all'implementazione concreta e ai dettagli delle classi, metodi, funzioni e template che costituiscono i componenti descritti al Livello 3. I file PHP, le migration e i file Blade forniti sono gli artefatti principali di questo livello. Una descrizione esaustiva a questo livello equivarrebbe a una documentazione a livello di codice (es. generata con PHPDoc) e all'analisi dettagliata di ogni file.

**Esempi di Elementi Chiave del Codice Fornito a questo Livello:**

* **Models (`App/Models/...`):**
    * `User.php`: Definisce l'entità `User` estendendo `Illuminate\Foundation\Auth\User` (quindi è `Authenticatable`). Include traits da Laravel Sanctum (`HasApiTokens`), Laravel Jetstream (`HasProfilePhoto`, `TwoFactorAuthenticatable`), e Eloquent (`HasFactory`, `Notifiable`). Definisce gli attributi `fillable` (per l'assegnazione di massa sicura), `hidden` (per escludere campi sensibili dalla serializzazione JSON, come `password` e token), `casts` (per la conversione automatica dei tipi di dato, es. `email_verified_at` a `datetime`, `password` a `hashed`), e `appends` (per includere attributi calcolati come `profile_photo_url` nell'array/JSON del modello).
    * `GuestUser.php`: Un modello Eloquent semplice per la tabella `guest_users`, con i suoi campi `fillable`.
    * Altri modelli (`Creative.php`, `Campaign.php`, `DailyPerformance.php`, `Funding.php`) definiscono la loro struttura e le relazioni con altri modelli attraverso metodi come `belongsTo`, `hasMany`, ecc. (anche se non esplicitamente mostrati, sono standard in Laravel per le FK definite nelle migration).

* **Migrations (nella directory `database/migrations/`):**
    * Ogni file di migration (es. `...create_campaigns_table.php`) contiene una classe che estende `Illuminate\Database\Migrations\Migration`. Il metodo `up()` definisce lo schema della tabella usando `Illuminate\Support\Facades\Schema` e il `Blueprint` per specificare colonne (es. `$table->id()`, `$table->string('name')`, `$table->enum('status', [...])`, `$table->foreignId('user_id')->constrained()->onDelete('cascade')`). Il metodo `down()` definisce come annullare la migrazione (tipicamente `Schema::dropIfExists('nome_tabella')`). Queste migration sono fondamentali per la gestione versionata dello schema del database.

* **Blade Views (`resources/views/...`):**
    * `advertisers/campaigns/create.blade.php`: Un esempio di vista complessa. Utilizza `@extends` per l'ereditarietà del layout, `@section` per definire contenuti specifici. Mostra form HTML con direttive Blade come `@csrf` (per la protezione CSRF), `old('nome_campo', $valore_default)` (per ripopolare i form dopo validazioni fallite), `@error('nome_campo') ... @enderror` (per visualizzare messaggi di errore di validazione). Include l'attributo `enctype="multipart/form-data"` nel tag `<form>` per abilitare l'upload di file. Contiene sezioni `@push('styles')` e `@push('scripts')` per aggiungere CSS e JavaScript specifici per la pagina, inclusa l'integrazione con librerie esterne (Leaflet, Google Maps API) e script custom per validazioni client-side (controllo dimensione file, confronto date) e interazioni dinamiche.
    * `advertisers/payment/index.blade.php`: Mostra dati utente (`Auth::user()->balance`), itera su collezioni (`@forelse($fundings as $funding) ... @empty ... @endforelse`), formatta numeri e date, e include componenti modali di Bootstrap per funzionalità interattive come l'aggiunta di fondi.
    * Altri file Blade (`advertisers/index.blade.php`, `advertisers/settings/index.blade.php`, `payment/stripe_checkout.blade.php`) seguono pattern simili per presentare dati e raccogliere input.

* **JavaScript (incorporato nei file Blade tramite tag `<script>` o sezioni `@push('scripts')`):**
    * Il codice fornito mostra un uso significativo di JavaScript per:
        * **Validazione Client-Side:** Controllo della data di fine rispetto alla data di inizio, verifica della dimensione massima dei file immagine.
        * **Integrazione API Esterne:** Utilizzo della Google Places Autocomplete API per il campo di input del targeting geografico (`countryInput`).
        * **Manipolazione DOM e UX:** Gestione di modali (Bootstrap), aggiornamenti dinamici dell'interfaccia.
        * **Librerie Grafiche:** (Non nel codice fornito ma spesso presenti in dashboard) Chart.js o simili per visualizzare grafici di performance, e Leaflet per mappe (come suggerito dai `@push` in alcune viste, anche se lo script specifico della mappa di Cuneo era un esempio statico).

Questa analisi testuale fornisce una comprensione dell'architettura e dei dettagli implementativi del sistema, basandosi esclusivamente sul codice sorgente che hai condiviso.
