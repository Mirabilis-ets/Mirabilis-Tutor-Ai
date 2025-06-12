<?php
/**
 * Plugin Name: Mirabilis AI Tutor
 * Plugin URI:  https://www.mirabilis.app
 * Description: Un tutor AI per le procedure amministrative, integrato con OpenRouter e un design ispirato a Gemini, con login Google.
 * Version:     1.1.0
 * Author:      Mirabilis
 * Author URI:  https://www.mirabilis.app
 * License:     GPL2
 */

// Evita l'accesso diretto ai file per sicurezza
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Funzione per l'attivazione del plugin.
 * Crea le opzioni predefinite per le impostazioni.
 */
function mirabilis_ai_tutor_activate() {
    add_option( 'mirabilis_ai_tutor_openrouter_api_key', '' );
    add_option( 'mirabilis_ai_tutor_openrouter_model', 'mistralai/mistral-7b-instruct-v0.2' ); // Modello predefinito

    // Nuove opzioni per Firebase
    add_option( 'mirabilis_ai_tutor_firebase_api_key', '' );
    add_option( 'mirabilis_ai_tutor_firebase_auth_domain', '' );
    add_option( 'mirabilis_ai_tutor_firebase_project_id', '' );
    add_option( 'mirabilis_ai_tutor_firebase_storage_bucket', '' );
    add_option( 'mirabilis_ai_tutor_firebase_messaging_sender_id', '' );
    add_option( 'mirabilis_ai_tutor_firebase_app_id', '' );
}
register_activation_hook( __FILE__, 'mirabilis_ai_tutor_activate' );

/**
 * Aggiunge la pagina delle impostazioni del plugin al menu di amministrazione.
 */
function mirabilis_ai_tutor_add_admin_menu() {
    add_options_page(
        'Mirabilis AI Tutor Settings', // Titolo della pagina
        'Mirabilis AI Tutor',        // Titolo nel menu
        'manage_options',             // Capacità richiesta per accedere
        'mirabilis-ai-tutor',         // Slug della pagina
        'mirabilis_ai_tutor_settings_page_html' // Funzione che renderizza la pagina
    );
}
add_action( 'admin_menu', 'mirabilis_ai_tutor_add_admin_menu' );

/**
 * Registra le impostazioni del plugin.
 */
function mirabilis_ai_tutor_settings_init() {
    // Sezione OpenRouter
    register_setting( 'mirabilis_ai_tutor_settings_group', 'mirabilis_ai_tutor_openrouter_api_key', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '', ) );
    register_setting( 'mirabilis_ai_tutor_settings_group', 'mirabilis_ai_tutor_openrouter_model', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => 'mistralai/mistral-7b-instruct-v0.2', ) );

    add_settings_section( 'mirabilis_ai_tutor_openrouter_section', 'Impostazioni OpenRouter AI Tutor', 'mirabilis_ai_tutor_openrouter_section_callback', 'mirabilis-ai-tutor' );
    add_settings_field( 'mirabilis_ai_tutor_api_key_field', 'OpenRouter API Key', 'mirabilis_ai_tutor_api_key_callback', 'mirabilis-ai-tutor', 'mirabilis_ai_tutor_openrouter_section' );
    add_settings_field( 'mirabilis_ai_tutor_model_field', 'Modello OpenRouter', 'mirabilis_ai_tutor_model_callback', 'mirabilis-ai-tutor', 'mirabilis_ai_tutor_openrouter_section' );

    // Nuova sezione per Firebase
    register_setting( 'mirabilis_ai_tutor_settings_group', 'mirabilis_ai_tutor_firebase_api_key', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '', ) );
    register_setting( 'mirabilis_ai_tutor_settings_group', 'mirabilis_ai_tutor_firebase_auth_domain', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '', ) );
    register_setting( 'mirabilis_ai_tutor_settings_group', 'mirabilis_ai_tutor_firebase_project_id', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '', ) );
    register_setting( 'mirabilis_ai_tutor_settings_group', 'mirabilis_ai_tutor_firebase_storage_bucket', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '', ) );
    register_setting( 'mirabilis_ai_tutor_settings_group', 'mirabilis_ai_tutor_firebase_messaging_sender_id', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '', ) );
    register_setting( 'mirabilis_ai_tutor_settings_group', 'mirabilis_ai_tutor_firebase_app_id', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '', ) );

    add_settings_section( 'mirabilis_ai_tutor_firebase_section', 'Impostazioni Firebase per Google Login', 'mirabilis_ai_tutor_firebase_section_callback', 'mirabilis-ai-tutor' );
    add_settings_field( 'mirabilis_ai_tutor_firebase_api_key_field', 'API Key', 'mirabilis_ai_tutor_firebase_api_key_callback', 'mirabilis-ai-tutor', 'mirabilis_ai_tutor_firebase_section' );
    add_settings_field( 'mirabilis_ai_tutor_firebase_auth_domain_field', 'Auth Domain', 'mirabilis_ai_tutor_firebase_auth_domain_callback', 'mirabilis-ai-tutor', 'mirabilis_ai_tutor_firebase_section' );
    add_settings_field( 'mirabilis_ai_tutor_firebase_project_id_field', 'Project ID', 'mirabilis_ai_tutor_firebase_project_id_callback', 'mirabilis-ai-tutor', 'mirabilis_ai_tutor_firebase_section' );
    add_settings_field( 'mirabilis_ai_tutor_firebase_storage_bucket_field', 'Storage Bucket', 'mirabilis_ai_tutor_firebase_storage_bucket_callback', 'mirabilis-ai-tutor', 'mirabilis_ai_tutor_firebase_section' );
    add_settings_field( 'mirabilis_ai_tutor_firebase_messaging_sender_id_field', 'Messaging Sender ID', 'mirabilis_ai_tutor_firebase_messaging_sender_id_callback', 'mirabilis-ai-tutor', 'mirabilis_ai_tutor_firebase_section' );
    add_settings_field( 'mirabilis_ai_tutor_firebase_app_id_field', 'App ID', 'mirabilis_ai_tutor_firebase_app_id_callback', 'mirabilis-ai-tutor', 'mirabilis_ai_tutor_firebase_section' );
}
add_action( 'admin_init', 'mirabilis_ai_tutor_settings_init' );

/**
 * Descrizione della sezione OpenRouter delle impostazioni.
 */
function mirabilis_ai_tutor_openrouter_section_callback() {
    echo '<p>Inserisci la tua API Key di OpenRouter e scegli il modello AI da utilizzare per il tutor.</p>';
    echo '<p>Puoi ottenere una chiave API su <a href="https://openrouter.ai/keys" target="_blank">openrouter.ai/keys</a>.</p>';
}

/**
 * Callback per il campo input della API Key di OpenRouter.
 */
function mirabilis_ai_tutor_api_key_callback() {
    $api_key = get_option( 'mirabilis_ai_tutor_openrouter_api_key' );
    echo '<input type="text" id="mirabilis_ai_tutor_api_key_field" name="mirabilis_ai_tutor_openrouter_api_key" value="' . esc_attr( $api_key ) . '" class="regular-text" placeholder="La tua API Key di OpenRouter">';
}

/**
 * Callback per il campo input del modello di OpenRouter.
 */
function mirabilis_ai_tutor_model_callback() {
    $model = get_option( 'mirabilis_ai_tutor_openrouter_model' );
    $models = array(
        'mistralai/mistral-7b-instruct-v0.2' => 'Mistral 7B Instruct (Raccomandato per iniziare)',
        'openai/gpt-3.5-turbo' => 'OpenAI GPT-3.5 Turbo',
        'google/gemini-pro' => 'Google Gemini Pro (tramite OpenRouter)',
        'anthropic/claude-3-haiku' => 'Anthropic Claude 3 Haiku',
        // Puoi aggiungere altri modelli supportati da OpenRouter qui
    );
    echo '<select id="mirabilis_ai_tutor_model_field" name="mirabilis_ai_tutor_openrouter_model">';
    foreach ( $models as $value => $label ) {
        echo '<option value="' . esc_attr( $value ) . '"' . selected( $model, $value, false ) . '>' . esc_html( $label ) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">Scegli il modello AI che OpenRouter utilizzerà. Fai riferimento a <a href="https://openrouter.ai/docs#models" target="_blank">openrouter.ai/docs#models</a> per i costi e le prestazioni.</p>';
}

/**
 * Descrizione della sezione Firebase delle impostazioni.
 */
function mirabilis_ai_tutor_firebase_section_callback() {
    echo '<p>Inserisci qui i dettagli di configurazione della tua app web Firebase.</p>';
    echo '<p>Li trovi nella console Firebase, dopo aver creato un progetto e aggiunto una app web. Assicurati di abilitare il metodo di accesso "Google" nella sezione Authentication di Firebase.</p>';
}

// Callback per i campi Firebase
function mirabilis_ai_tutor_firebase_api_key_callback() { $val = get_option( 'mirabilis_ai_tutor_firebase_api_key' ); echo '<input type="text" name="mirabilis_ai_tutor_firebase_api_key" value="' . esc_attr( $val ) . '" class="regular-text">'; }
function mirabilis_ai_tutor_firebase_auth_domain_callback() { $val = get_option( 'mirabilis_ai_tutor_firebase_auth_domain' ); echo '<input type="text" name="mirabilis_ai_tutor_firebase_auth_domain" value="' . esc_attr( $val ) . '" class="regular-text">'; }
function mirabilis_ai_tutor_firebase_project_id_callback() { $val = get_option( 'mirabilis_ai_tutor_firebase_project_id' ); echo '<input type="text" name="mirabilis_ai_tutor_firebase_project_id" value="' . esc_attr( $val ) . '" class="regular-text">'; }
function mirabilis_ai_tutor_firebase_storage_bucket_callback() { $val = get_option( 'mirabilis_ai_tutor_firebase_storage_bucket' ); echo '<input type="text" name="mirabilis_ai_tutor_firebase_storage_bucket" value="' . esc_attr( $val ) . '" class="regular-text">'; }
function mirabilis_ai_tutor_firebase_messaging_sender_id_callback() { $val = get_option( 'mirabilis_ai_tutor_firebase_messaging_sender_id' ); echo '<input type="text" name="mirabilis_ai_tutor_firebase_messaging_sender_id" value="' . esc_attr( $val ) . '" class="regular-text">'; }
function mirabilis_ai_tutor_firebase_app_id_callback() { $val = get_option( 'mirabilis_ai_tutor_firebase_app_id' ); echo '<input type="text" name="mirabilis_ai_tutor_firebase_app_id" value="' . esc_attr( $val ) . '" class="regular-text">'; }


/**
 * Renderizza la pagina delle impostazioni del plugin.
 */
function mirabilis_ai_tutor_settings_page_html() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'mirabilis_ai_tutor_settings_group' );
            do_settings_sections( 'mirabilis-ai-tutor' );
            submit_button( 'Salva Modifiche' );
            ?>
        </form>
    </div>
    <?php
}

/**
 * Shortcode per visualizzare il chatbot AI.
 */
function mirabilis_ai_tutor_chatbot_shortcode() {
    // Enqueue script e stili solo quando lo shortcode è usato
    mirabilis_ai_tutor_enqueue_assets();

    // Ottieni la configurazione Firebase dalle opzioni
    $firebase_config = array(
        'apiKey'            => get_option( 'mirabilis_ai_tutor_firebase_api_key' ),
        'authDomain'        => get_option( 'mirabilis_ai_tutor_firebase_auth_domain' ),
        'projectId'         => get_option( 'mirabilis_ai_tutor_firebase_project_id' ),
        'storageBucket'     => get_option( 'mirabilis_ai_tutor_firebase_storage_bucket' ),
        'messagingSenderId' => get_option( 'mirabilis_ai_tutor_firebase_messaging_sender_id' ),
        'appId'             => get_option( 'mirabilis_ai_tutor_firebase_app_id' ),
    );

    // Passa la configurazione Firebase a JavaScript
    wp_localize_script(
        'mirabilis-ai-tutor-script',
        'mirabilis_ai_tutor_firebase_config',
        $firebase_config
    );

    // Restituisci il contenitore HTML che sarà popolato da JavaScript
    return '<div id="mirabilis-ai-tutor-chatbot-wrapper" class="mirabilis-ai-tutor-chatbot-wrapper"></div>';
}
add_shortcode( 'mirabilis_ai_tutor_chatbot', 'mirabilis_ai_tutor_chatbot_shortcode' );


/**
 * Inserisce gli script e gli stili del plugin.
 */
function mirabilis_ai_tutor_enqueue_assets() {
    // Carica gli stili CSS
    wp_enqueue_style(
        'mirabilis-ai-tutor-style',
        plugin_dir_url( __FILE__ ) . 'css/mirabilis-ai-tutor.css',
        array(),
        '1.1.0'
    );

    // Carica Firebase SDK
    wp_enqueue_script( 'firebase-app', 'https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js', array(), '9.6.1', true );
    wp_enqueue_script( 'firebase-auth', 'https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js', array('firebase-app'), '9.6.1', true );


    // Carica lo script JavaScript principale del plugin
    wp_enqueue_script(
        'mirabilis-ai-tutor-script',
        plugin_dir_url( __FILE__ ) . 'js/mirabilis-ai-tutor.js',
        array( 'jquery', 'firebase-auth' ), // Dipendenze da jQuery e Firebase Auth
        '1.1.0',
        true // Inserisce lo script nel footer
    );

    // Passa dati PHP allo script JavaScript
    wp_localize_script(
        'mirabilis-ai-tutor-script',
        'mirabilis_ai_tutor_ajax_object',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ), // URL per le richieste AJAX di WordPress
            'nonce'    => wp_create_nonce( 'mirabilis_ai_tutor_chat_nonce' ) // Nonce per sicurezza
        )
    );
}

/**
 * Gestisce le richieste AJAX dal frontend al backend AI.
 */
function mirabilis_ai_tutor_handle_chat_request() {
    // Verifica il nonce per la sicurezza
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mirabilis_ai_tutor_chat_nonce' ) ) {
        wp_send_json_error( 'Errore di sicurezza: nonce non valido.' );
        wp_die();
    }

    $user_query = sanitize_text_field( $_POST['query'] );
    $openrouter_api_key = get_option( 'mirabilis_ai_tutor_openrouter_api_key' );
    $openrouter_model = get_option( 'mirabilis_ai_tutor_openrouter_model' );

    if ( empty( $openrouter_api_key ) ) {
        wp_send_json_error( 'API Key di OpenRouter non configurata. Si prega di configurarla nelle impostazioni del plugin.' );
        wp_die();
    }

    // Definizione del ruolo del sistema per l'AI
    $system_prompt = "Sei un tutor AI altamente specializzato nelle procedure amministrative della Pubblica Amministrazione italiana. Il tuo obiettivo è fornire risposte chiare, precise, complete e passo-passo basate sulle normative e le prassi vigenti. Utilizza un linguaggio semplice e diretto. In caso di incertezza, chiedi ulteriori dettagli o suggerisci di consultare la documentazione ufficiale o un professionista. Non inventare informazioni.";

    $request_body = json_encode( array(
        'model'      => $openrouter_model,
        'messages'   => array(
            array( 'role' => 'system', 'content' => $system_prompt ),
            array( 'role' => 'user', 'content' => $user_query )
        ),
        'temperature' => 0.7, // Controlla la creatività (0.0 = meno creativo, 1.0 = più creativo)
        'max_tokens'  => 800,  // Lunghezza massima della risposta in token
    ) );

    $response = wp_remote_post( 'https://openrouter.ai/api/v1/chat/completions', array(
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $openrouter_api_key,
        ),
        'body'    => $request_body,
        'timeout' => 45, // Tempo massimo per la risposta (secondi)
    ) );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        wp_send_json_error( 'Errore di connessione all\'AI: ' . $error_message . '. Controlla la tua connessione o l\'API Key.' );
    } else {
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );

        if ( isset( $data->choices[0]->message->content ) ) {
            wp_send_json_success( $data->choices[0]->message->content );
        } elseif ( isset( $data->error->message ) ) {
            wp_send_json_error( 'Errore dall\'API di OpenRouter: ' . $data->error->message );
        } else {
            wp_send_json_error( 'La risposta dell\'AI non è nel formato atteso. Dettagli: ' . print_r( $data, true ) );
        }
    }

    wp_die(); // Termina correttamente la richiesta AJAX
}
add_action( 'wp_ajax_mirabilis_ai_tutor_chat_request', 'mirabilis_ai_tutor_handle_chat_request' );
add_action( 'wp_ajax_nopriv_mirabilis_ai_tutor_chat_request', 'mirabilis_ai_tutor_handle_chat_request' ); // Permette anche agli utenti non loggati
