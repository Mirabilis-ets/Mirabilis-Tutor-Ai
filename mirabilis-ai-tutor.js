jQuery(document).ready(function($) {
    // Seleziona il wrapper principale, che conterrà il chatbot o l'interfaccia di login
    var chatbotWrapper = $('#mirabilis-ai-tutor-chatbot-wrapper');

    // Assicurati che il wrapper esista prima di procedere
    if (!chatbotWrapper.length) {
        console.error("Mirabilis AI Tutor: Elemento #mirabilis-ai-tutor-chatbot-wrapper non trovato. Assicurati che lo shortcode sia inserito correttamente.");
        return;
    }

    // Costruisce l'HTML per l'interfaccia di login
    var loginHtml = `
        <div id="mirabilis-ai-tutor-login-container" class="mirabilis-ai-tutor-login-area">
            <h2>Accedi per utilizzare il Tutor AI</h2>
            <button id="mirabilis-ai-tutor-google-login-button" class="mirabilis-ai-tutor-login-button">
                <svg viewBox="0 0 24 24" fill="currentColor" width="24px" height="24px" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.24 10.232c-.092-.728-.62-1.328-1.572-1.328-.868 0-1.748.568-1.748 1.572 0 .828.616 1.48 1.464 1.48.916 0 1.552-.616 1.552-1.572zm-1.572-4.144c1.196 0 2.392.512 2.392 2.392 0 1.956-1.284 2.392-2.392 2.392-1.196 0-2.392-.512-2.392-2.392 0-1.956 1.284-2.392 2.392-2.392zm-5.632 6.08c.516.516 1.032.516 1.032 0 0-.516-.516-.516-.516 0-.516 0-1.032 0-1.032 0z"/>
                    <path d="M21.758 12.01c0-1.15-.12-2.304-.344-3.416H12v6.416h5.816c-.236 1.172-.88 2.22-1.796 3.032l-3.92-3.92c-.524-.524-.524-1.372 0-1.896.524-.524 1.372-.524 1.896 0l2.368 2.368c.244-.756.404-1.544.404-2.388zm-9.758 10.99c-3.132 0-5.912-1.716-7.396-4.256L2.3 15.696c-1.468-1.636-2.3-3.692-2.3-5.88 0-2.224.84-4.268 2.3-5.892L4.624 1.304C6.112-1.248 8.892-3 12-3c3.132 0 5.912 1.716 7.396 4.256L21.7 8.304c1.468 1.636 2.3 3.692 2.3 5.88 0 2.224-.84 4.268-2.3 5.892L19.376 22.696C17.888 25.248 15.108 27 12 27z" fill="#4285F4"/>
                    <circle cx="12" cy="12" r="11" stroke="#4285F4" stroke-width="1.5" fill="none"/>
                </svg>
                Accedi con Google
            </button>
        </div>
    `;

    // Costruisce l'HTML per l'interfaccia del chatbot
    var chatHtml = `
        <div id="mirabilis-ai-tutor-chatbot-container" class="mirabilis-ai-tutor-chatbot-container">
            <div class="mirabilis-ai-tutor-header">
                <span>Tutor PA Mirabilis AI</span>
                <button class="logout-button" title="Disconnetti">Esci</button>
            </div>
            <div class="mirabilis-ai-tutor-messages">
                <div class="mirabilis-ai-tutor-message ai">Ciao! Sono il tuo tutor AI per la Pubblica Amministrazione italiana. Come posso aiutarti oggi?</div>
            </div>
            <div class="mirabilis-ai-tutor-input-area">
                <input type="text" id="mirabilis-ai-tutor-user-input" placeholder="Chiedi una procedura..." aria-label="Digita la tua domanda">
                <button id="mirabilis-ai-tutor-send-button" title="Invia messaggio">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send">
                        <path d="m22 2-7 20-4-9-9-4 20-7Z"/>
                        <path d="M22 2 11 13"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    // Renderizza inizialmente l'interfaccia di login E il chatbot (visibilità gestita da JS/CSS)
    chatbotWrapper.html(loginHtml + chatHtml);

    // Seleziona gli elementi dopo che sono stati aggiunti al DOM
    var loginContainer = $('#mirabilis-ai-tutor-login-container');
    var googleLoginButton = $('#mirabilis-ai-tutor-google-login-button');
    var chatbotContainer = $('#mirabilis-ai-tutor-chatbot-container');
    var messagesContainer = chatbotContainer.find('.mirabilis-ai-tutor-messages');
    var userInput = chatbotContainer.find('#mirabilis-ai-tutor-user-input');
    var sendButton = chatbotContainer.find('#mirabilis-ai-tutor-send-button');
    var logoutButton = chatbotContainer.find('.logout-button'); // Nuovo pulsante per il logout

    // --- Firebase Initialization ---
    // Assicurati che window.mirabilis_ai_tutor_firebase_config sia disponibile
    if (typeof mirabilis_ai_tutor_firebase_config === 'undefined' || !mirabilis_ai_tutor_firebase_config.apiKey) {
        console.error("Mirabilis AI Tutor: Firebase config non trovata o incompleta. Controlla le impostazioni del plugin.");
        loginContainer.html('<p style="color: red;">Errore: Configurazione Firebase mancante. Controlla le impostazioni del plugin.</p>');
        return;
    }

    try {
        const firebaseApp = firebase.initializeApp(mirabilis_ai_tutor_firebase_config);
        const auth = firebase.auth();
        const provider = new firebase.auth.GoogleAuthProvider();

        // --- Authentication State Listener ---
        auth.onAuthStateChanged(user => {
            if (user) {
                // Utente loggato: nascondi login, mostra chatbot
                loginContainer.removeClass('visible').addClass('hidden');
                chatbotContainer.removeClass('hidden').addClass('visible');
                userInput.focus(); // Metti il focus sull'input
                messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));
            } else {
                // Utente non loggato: mostra login, nascondi chatbot
                loginContainer.removeClass('hidden').addClass('visible');
                chatbotContainer.removeClass('visible').addClass('hidden');
            }
        });

        // --- Google Login Button Click Handler ---
        googleLoginButton.on('click', function() {
            auth.signInWithPopup(provider)
                .then((result) => {
                    // Login riuscito
                    console.log("Login con Google riuscito:", result.user.displayName);
                })
                .catch((error) => {
                    // Gestisci errori di login
                    console.error("Errore login Google:", error);
                    // Sostituisci alert con una UI custom per mostrare l'errore all'utente
                    addMessage('ai', 'Errore durante il login: ' + error.message);
                });
        });

        // --- Logout Button Click Handler ---
        logoutButton.on('click', function() {
            auth.signOut().then(() => {
                console.log("Logout riuscito.");
            }).catch((error) => {
                console.error("Errore durante il logout:", error);
                // Sostituisci alert con una UI custom
                addMessage('ai', 'Errore durante il logout: ' + error.message);
            });
        });

    } catch (e) {
        console.error("Mirabilis AI Tutor: Errore durante l'inizializzazione di Firebase:", e);
        loginContainer.html('<p style="color: red;">Errore di inizializzazione Firebase. Verifica la console per i dettagli.</p>');
        return; // Blocca l'esecuzione se Firebase non si inizializza
    }


    // Funzione per aggiungere un messaggio alla chat
    function addMessage(sender, text, isLoading = false) {
        var messageClass = (sender === 'user') ? 'user' : 'ai';
        var loadingClass = isLoading ? ' loading' : '';
        messagesContainer.append(`<div class="mirabilis-ai-tutor-message ${messageClass}${loadingClass}">${text}</div>`);
        messagesContainer.scrollTop(messagesContainer.prop("scrollHeight")); // Scorri in basso
    }

    // Funzione per mostrare/nascondere l'indicatore di caricamento
    function showLoadingIndicator() {
        if (!messagesContainer.find('.mirabilis-ai-tutor-message.loading').length) {
            messagesContainer.append(`
                <div class="mirabilis-ai-tutor-message ai loading" id="mirabilis-ai-tutor-loading-message">
                    <div class="mirabilis-ai-tutor-loading-dots">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            `);
            messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));
        }
    }

    function removeLoadingIndicator() {
        $('#mirabilis-ai-tutor-loading-message').remove();
    }

    // Funzione per inviare il messaggio
    function sendMessage() {
        var query = userInput.val().trim();
        if (query === '') return; // Non inviare messaggi vuoti

        addMessage('user', query);
        userInput.val(''); // Pulisci l'input
        sendButton.prop('disabled', true); // Disabilita il pulsante
        showLoadingIndicator(); // Mostra indicatore di caricamento

        // Effettua la chiamata AJAX al backend PHP
        $.ajax({
            url: mirabilis_ai_tutor_ajax_object.ajax_url, // URL definito da wp_localize_script
            type: 'POST',
            data: {
                action: 'mirabilis_ai_tutor_chat_request', // Azione gestita dal PHP
                query: query,
                nonce: mirabilis_ai_tutor_ajax_object.nonce // Nonce per sicurezza
            },
            success: function(response) {
                removeLoadingIndicator();
                sendButton.prop('disabled', false); // Riabilita il pulsante
                if (response.success) {
                    addMessage('ai', response.data); // Aggiungi la risposta dell'AI
                } else {
                    // Mostra un messaggio di errore all'utente
                    addMessage('ai', 'Si è verificato un problema: ' + (response.data || 'Errore sconosciuto.'));
                    console.error('Errore AI Tutor:', response.data);
                }
            },
            error: function(xhr, status, error) {
                removeLoadingIndicator();
                sendButton.prop('disabled', false); // Riabilita il pulsante
                addMessage('ai', 'Errore di comunicazione. Si prega di riprovare più tardi.');
                console.error('AJAX Error:', status, error, xhr);
            }
        });
    }

    // Event listener per il pulsante Invia
    sendButton.on('click', sendMessage);

    // Event listener per il tasto Invio nell'input
    userInput.on('keypress', function(e) {
        if (e.which === 13) { // Codice del tasto Invio
            sendMessage();
        }
    });

    // Inizialmente, imposta entrambi i contenitori come "hidden"
    // Lo stato corretto (login o chat) sarà gestito dall'onAuthStateChanged di Firebase
    loginContainer.addClass('hidden');
    chatbotContainer.addClass('hidden');
});
