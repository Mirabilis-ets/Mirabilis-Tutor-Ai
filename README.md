Mirabilis AI Tutor Plugin per WordPress
Mirabilis AI Tutor è un plugin rivoluzionario per il tuo sito WordPress che trasforma la complessità burocratica in un percorso semplice e accessibile. Progettato con l'integrazione di tecnologie AI avanzate (tramite OpenRouter) e ispirato all'intuitività di Gemini, Mirabilis AI Tutor funge da guida intelligente per gli utenti sulle procedure amministrative italiane, con un accesso protetto tramite login Google.

Indice
Descrizione del Plugin

Vantaggi per l'Utente

Installazione e Configurazione

1. Preparazione dei File del Plugin

2. Attivazione del Plugin su WordPress

3. Configurazione di Firebase per l'Autenticazione Google

4. Configurazione delle Impostazioni del Plugin in WordPress

5. Inserimento dello Shortcode nella Pagina

6. Verifica Finale

Descrizione del Plugin
Navigare tra le procedure della Pubblica Amministrazione può essere un'impresa ardua. Moduli complessi, scadenze, requisiti specifici e linguaggi tecnici spesso scoraggiano e confondono. Mirabilis AI Tutor nasce proprio per risolvere queste difficoltà.

Questo plugin integra un'intelligenza artificiale avanzata direttamente nel tuo sito, fungendo da tutor personale e sempre disponibile per gli utenti che cercano informazioni o assistenza su:

Richieste di Documenti: Come ottenere SPID, Carta d'Identità Elettronica (CIE), passaporto, ecc.

Servizi e Benefici: Requisiti per bonus, agevolazioni fiscali, iscrizioni a servizi pubblici.

Modulistica e Procedure: Guida passo-passo alla compilazione di moduli, invio di pratiche e scadenze importanti.

Informazioni Generali: Chiarimenti su normative, enti di riferimento e orari degli uffici.

L'AI è configurata per fornire risposte precise, basate su un set di conoscenze specializzate sulla PA italiana (e che potrà essere ulteriormente espansa con le tue fonti specifiche) e presenta le informazioni in un formato chiaro e comprensibile.

Vantaggi per l'Utente
L'adozione di Mirabilis AI Tutor sul tuo sito offre benefici tangibili a chiunque abbia bisogno di interagire con la Pubblica Amministrazione:

Accesso Immediato all'Informazione: Niente più lunghe ricerche su siti frammentati o attese telefoniche. Le risposte sono a portata di mano, 24 ore su 24, 7 giorni su 7.

Chiarimenti Semplici e Precisi: L'AI traduce il "burocratese" in un linguaggio comune, fornendo guide passo-passo facili da seguire. Addio dubbi e incertezze!

Riduzione dello Stress: Affrontare la burocrazia può essere frustrante. Avere un tutor AI che ti guida riduce lo stress e rende il processo più sereno.

Autonomia e Controllo: Gli utenti si sentono più autonomi e in controllo delle proprie pratiche, acquisendo la fiducia necessaria per portare a termine le procedure.

Risparmio di Tempo: Ottieni rapidamente le risposte che cerchi, evitando perdite di tempo in ricerche infruttuose o spostamenti inutili.

Punto di Riferimento Affidabile: Il tuo sito diventa un punto di riferimento autorevole e innovativo per la gestione delle pratiche amministrative, migliorando l'esperienza utente complessiva.

Esperienza Utente Migliorata: Con un design pulito e intuitivo ispirato a Gemini, l'interazione con l'AI è fluida e piacevole.

Accedi e Scopri la Semplicità! Per accedere a Mirabilis AI Tutor e iniziare subito a semplificare le tue procedure amministrative, ti basterà effettuare il login con il tuo account Google. È un processo rapido e sicuro, che ti aprirà le porte a un mondo di informazioni chiare e supporto immediato.

Preparati a trasformare la burocrazia in un'esperienza più chiara e gestibile, con Mirabilis AI Tutor al tuo fianco.

Installazione e Configurazione
Segui questi passaggi dettagliati per installare e configurare correttamente il plugin sul tuo sito WordPress.

1. Preparazione dei File del Plugin
Assicurati di avere tutti i file del plugin nella struttura corretta.

Crea la cartella principale: Nella directory wp-content/plugins/ del tuo sito WordPress, crea una nuova cartella e chiamala mirabilis-ai-tutor.

Crea le sottocartelle: All'interno di mirabilis-ai-tutor/, crea altre due cartelle: css e js.

Crea i file del plugin:

Nella cartella mirabilis-ai-tutor/, crea un file chiamato mirabilis-ai-tutor.php.

Nella cartella mirabilis-ai-tutor/css/, crea un file chiamato mirabilis-ai-tutor.css.

Nella cartella mirabilis-ai-tutor/js/, crea un file chiamato mirabilis-ai-tutor.js.

Copia il codice: Copia i codici forniti in precedenza per ciascuno di questi tre file e incollali nei rispettivi file appena creati. Assicurati di usare le versioni più aggiornate dei codici forniti per la configurazione "senza RAG" (con il plugin che comunica direttamente con OpenRouter).

2. Attivazione del Plugin su WordPress
Ora che i file sono al loro posto, puoi attivare il plugin.

Accedi alla Bacheca di WordPress: Vai su il-tuo-sito.it/wp-admin.

Vai alla sezione Plugin: Nel menu laterale sinistro, clicca su Plugin > Plugin installati.

Trova "Mirabilis AI Tutor": Scorri l'elenco dei plugin installati e dovresti trovare "Mirabilis AI Tutor".

Attiva il Plugin: Clicca sul link Attiva sotto il nome del plugin.

3. Configurazione di Firebase per l'Autenticazione Google
Il login tramite Google è gestito da Firebase Authentication. Devi configurare il tuo progetto Firebase e poi inserire i dettagli nel plugin.

Crea un Progetto Firebase:

Vai su Firebase Console e accedi con il tuo account Google.

Clicca su Aggiungi progetto o Crea un progetto.

Segui le istruzioni per dare un nome al tuo progetto e completare la creazione.

Aggiungi un'App Web:

Nella dashboard del tuo nuovo progetto Firebase, clicca sull'icona web application (il simbolo </>).

Dai un "Nickname dell'app" (es. "Tutor AI Frontend") e clicca su Registra app.

Molto importante: Firebase ti mostrerà un oggetto firebaseConfig contenente i dettagli di configurazione (es. apiKey, authDomain, projectId, appId, ecc.). Copia tutti questi valori, ti serviranno nel prossimo passaggio.

Abilita l'Autenticazione Google:

Nel menu laterale sinistro di Firebase Console, sotto "Build", clicca su Authentication.

Vai alla scheda Metodo di accesso (Sign-in method).

Trova Google nell'elenco dei provider, clicca sulla matita per modificarlo e abilita l'interruttore.

Seleziona un Indirizzo email di supporto del progetto e clicca su Salva.

4. Configurazione delle Impostazioni del Plugin in WordPress
Ora devi inserire le credenziali di Firebase e OpenRouter direttamente nel plugin.

Nella Bacheca di WordPress: Vai su Impostazioni > Mirabilis AI Tutor.

Sezione "Impostazioni Firebase per Google Login":

Inserisci i valori che hai copiato dall'oggetto firebaseConfig di Firebase nei rispettivi campi: API Key, Auth Domain, Project ID, Storage Bucket, Messaging Sender ID, App ID.

Sezione "Impostazioni OpenRouter AI Tutor":

OpenRouter API Key: Incolla qui la tua API Key di OpenRouter. Se non ne hai una, puoi crearla su openrouter.ai/keys.

Modello OpenRouter: Scegli il modello di Large Language Model che desideri utilizzare dall'elenco a discesa (es. mistralai/mistral-7b-instruct-v0.2).

Salva le Modifiche: Clicca sul pulsante Salva Modifiche in fondo alla pagina.

5. Inserimento dello Shortcode nella Pagina
Il tuo chatbot non apparirà automaticamente ovunque. Dovrai posizionarlo in una pagina specifica.

Crea o Modifica una Pagina: Vai su Pagine > Tutte le pagine e crea una nuova pagina o modifica una esistente dove vuoi che il tutor AI sia visibile.

Inserisci lo Shortcode:

Se usi l'editor a blocchi (Gutenberg): Clicca sul simbolo + per aggiungere un nuovo blocco, cerca "Shortcode" e inseriscilo. Poi digita [mirabilis_ai_tutor_chatbot] all'interno del blocco.

Se usi l'editor classico: Passa alla modalità "Testo" (non "Visuale") e incolla semplicemente [mirabilis_ai_tutor_chatbot].

Aggiorna/Pubblica la Pagina.

6. Verifica Finale
Visita la Pagina: Apri la pagina del tuo sito dove hai inserito lo shortcode.

Login Richiesto: Dovresti vedere una schermata che ti invita ad accedere con Google.

Interagisci con il Chatbot: Dopo aver effettuato l'accesso, il chatbot dovrebbe apparire e tu potrai iniziare a porre domande sulle procedure amministrative.
