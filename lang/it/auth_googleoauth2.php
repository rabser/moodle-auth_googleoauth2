<?php
// This file is part of Oauth2 authentication plugin for Moodle.
//
// Oauth2 authentication plugin for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Oauth2 authentication plugin for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Oauth2 authentication plugin for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for language 'it'
 *
 * @package    auth
 * @subpackage googleoauth2
 * @author     Sergio Rabellino  sergio.rabellino@unito.it
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Oauth2';
$string['auth_dropboxclientid'] = 'Le vostre Key/Secret per le app sono generate nella Console <a href="https://www.dropbox.com/developers/apps" target="_blank">Dropbox app</a>.
Inserire le seguenti impostazioni al momento di creare un\' applicazione (si noti che Dropbox supporta solo url https, quindi il vostro sito Moodle deve supportare https):
<br/>Sito web per l\'app: {$a->siteurl}
<br/>Redirect URIs: {$a->callbackurl}';
$string['auth_dropboxclientid_key'] = 'Key per Dropbox App';
$string['auth_dropboxclientsecret'] = '';
$string['auth_dropboxclientsecret_key'] = 'Secret per Dropbox App';
$string['auth_facebookclientid'] = 'La vostra App ID/Secret può essere generata nella vostra <a href="https://developers.facebook.com/apps/" target="_blank">pagina di sviluppo Facebook</a>:
<br/>Aggiungi una nuova Applicazione > Sito Web > Inserire il nome del sito come nome dell\'applicazione > Crea ID App > Inserire la URL del sito  - non è necessario inserire una URL Mobile  >
Nella pagina di conferma, cercare il link "Skip to Developer Dashboard" > sulla app dashboard dovreste trovare il id/secret > Impostazioni > Avanzate > inserire le URI di redirezione OAuth Valide
<br/>URL del sito: {$a->siteurl}
<br/>Domini dell\'App: {$a->sitedomain}
<br/>URI di redirezione OAuth Valide: {$a->callbackurl}.';
$string['auth_facebookclientid_key'] = 'Facebook App ID';
$string['auth_facebookclientsecret'] = '';
$string['auth_facebookclientsecret_key'] = 'Facebook App secret';
$string['auth_githubclientid'] = 'Il vostro client ID/Secret può essere generato nella vostra <a href="https://github.com/settings/applications/new" target="_blank">pagina di registrazione delle applicazioni Github</a>:
<br/>Homepage URL: {$a->siteurl}
<br/>URL della callback di autorizzazione: {$a->callbackurl}';
$string['auth_githubclientid_key'] = 'ID del client Github';
$string['auth_githubclientsecret'] = '';
$string['auth_githubclientsecret_key'] = 'Secret del client Github';
$string['auth_googleclientid'] = 'Il vostro client ID/Secret può essere generato nel vostro <a href="https://code.google.com/apis/console" target="_blank">Gestore API</a>:
<br/>
Dashboard > Google Identity and Access Management (IAM) API > Abilita > Credenziali > Crea Credenziali > ID Client OAuth > Configurazione Schermata consenso OAuth > scegliere \'Applicazione Web\'
<br/>
Origini JavaScript autorizzate: {$a->jsorigins}
<br/>
URI di reindirizzamento autorizzati: {$a->redirecturls}';
$string['auth_googleclientid_key'] = 'ID del Client Google';
$string['auth_googleclientsecret'] = '';
$string['auth_googleclientsecret_key'] = 'Secret del Client Google';
$string['auth_googleipinfodbkey'] = 'IPinfoDB è un servizio che vi permette di trovare la nazione e la città del visitatore. 
Questa impostazione è opzionale. Potete iscrivervi a <a 
href="http://www.ipinfodb.com/register.php">IPinfoDB</a> per ottenere una chiave gratis.<br/>
Sitoweb: {$a->website}';
$string['auth_googleipinfodbkey_key'] = 'Chiave IPinfoDB';
$string['auth_oauth2userprefixdesc'] = 'Il nome utente creato dell\'utente comincerà con questo prefisso. In un sito Moodle di base non è necessario cambiarlo.';
$string['auth_oauth2userprefix'] = 'Prefisso del nome utente';
$string['auth_googleoauth2description'] = 'Consente ad un utente di connettersi al sito con un provider di autenticazione esterno tra: Google/Facebook/Windows Live/DropBox/Github/Linkedin.
La prima volta che l\'utente si collega verrà creato un nuovo account.
È importante che l\'impostazione "Prevenire la creazione di un account" (impostazione <b>authpreventaccountcreation</b>) sia non abilitata o gli utenti proventienti da social network non potranno entrare in moodle.';
$string['auth_linkedinclientid'] = 'Le vostre chiavi API/Secret possono essere generate nella vostra <a href="https://www.linkedin.com/secure/developer" target="_blank"> pagina di registrazione applicazione Linkedin</a>:
<br/>URL del Sito Web: {$a->siteurl}
<br/>URL di Redirezione per l\'Accept OAuth 2.0 (callback): {$a->callbackurl}';
$string['auth_linkedinclientid_key'] = 'Chiave API Linkedin';
$string['auth_linkedinclientsecret'] = '';
$string['auth_linkedinclientsecret_key'] = 'Chiave Secret Linkedin';
$string['auth_microsoftclientid'] = 'Il vostro ID/Secret per client può essere generato al <a href="https://apps.dev.microsoft.com/" target="_blank">Portale di Registrazione delle Applicazioni Microsoft</a>:
<br />URI di Redirezione: {$a->callbackurl}';
$string['auth_microsoftclientid_key'] = 'ID di Microsoft v2 Application';
$string['auth_microsoftclientsecret'] = '';
$string['auth_microsoftclientsecret_key'] = 'Secret di Microsoft v2 Application';
$string['microsoft_failure'] = 'Non ha ricevuto un codice di autorizzazione dai server di Microsoft.';
$string['auth_googlesettings'] = 'Impostazioni Provider';
$string['couldnotauthenticate'] = 'Autenticazione fallita - Si di riprovare.';
$string['couldnotgetgoogleaccesstoken'] = 'Il provider di autenticazione ci ha inviato un errore di comunicazione. Si prega di provare nuovamente ad autenticarsi.';
$string['couldnotauthenticateuserlogin'] = 'Errore nel metodo di autenticazione.<br/>
Si prega di provare nuovamente ad eseguire il login con i vostri nome utente e password.<br/>
<br/>
<a href="{$a->loginpage}">Riprova</a>.<br/>
<a href="{$a->forgotpass}">Ho dimenticato la password</a>?';
$string['displaybuttons'] = 'Mostra i pulsanti nella pagina di login';
$string['displaybuttonshelp'] = 'Mostra i pulsanti con i logo dei provider  Google/Facebook/... nella parte superiore della pagina di login.  Se si vogliono inserire autonomamente i pulsanti nella propria pagina di login, è possibile mantenere questa opzione disattivata e aggiungere il seguente codice: {$a}';
$string['emailaddressmustbeverified'] = 'L\'indirizzo di e-mail non è stato verificato con il metodo di autenticazione selezionato. Probabilmente ci si è dimenticati di cliccare su un link "verifica indirizzo di posta elettronica"
che Google o Facebook dovrebbero aver inviato durante la registrazione al loro servizio.';
$string['auth_sign-in_with'] = 'Registrarsi con {$a->providername}';
$string['faileduserdetails'] = 'Il sito è riuscito a connettersi al provider selezionato, ma non è riuscito a recuperare i dati utente.';
$string['moreproviderlink'] = 'Registrarsi con un servizio differente.';
$string['signinwithanaccount'] = 'Entra con {$a}';
$string['noaccountyet'] = 'Non hai ancora il permesso di utilizzare il sito. Si prega di contattare l\'amministratore e chiedergli di attivare il vostro account.';
$string['othermoodle'] = 'Autenticazione Moodle alternativa';
$string['stattitle'] = 'Le statistiche di Login per gli ultimi {$a->periodindays} giorni (partendo dal momento di installazione/aggiornamento del plugin)';
$string['supportmaintenance'] = 'Per supportare il mantenimento di questo plugin, autenticarsi alla <a target="_blank" href="https://moodle.org/plugins/view/auth_googleoauth2">pagina di login Moodle.org</a> e cliccare su \'Aggiungi ai miei preferiti\'. Grazie!';
$string['unknownfirstname'] = 'Nome sconosciuto';
$string['unknownlastname'] = 'Cognome sconosciuto';

$string['auth_vkontakteclientid'] = 'I codici ID/Secret sono generati tramite la <a href="https://vk.com/apps?act=manage" target="_blank">VK My Applications console</a>.
Inserisci i seguenti dati durante la creazione della applicazione web:
<br/>Site address: {$a->siteurl}
<br/>Base Domain: {$a->sitedomain}
<br/>Authorized Redirect URI: {$a->callbackurl}';
$string['auth_vkontakteclientid_key'] = 'VK.com App ID';
$string['auth_vkontakteclientsecret'] = '';
$string['auth_vkontakteclientsecret_key'] = 'VK.com Secure Key';

$string['google'] = 'Google';
$string['facebook'] = 'Facebook';
$string['linkedin'] = 'LinkedIn';
$string['microsoft'] = 'Microsoft Live';
$string['dropbox'] = 'Dropbox';
$string['github'] = 'GitHub';
$string['vkontakte'] = 'VK.com';
$string['othersettings'] = 'Other Settings';
$string['providerlinkstext'] = 'Altri metodi di accesso:';

$string['couldnotgetuseremail'] = 'Il Social Network non fornito un <b>indirizzo email</b>. Moodle necessita di una email valida per consentire l\'accesso: verifica le preferenze del social network per consentire la condivisione del tuo indirizzo email.';
$string['saveaccesstoken'] = 'Salva i token di accesso?';
$string['saveaccesstokenhelp'] = 'Salva i token i accesso in una tabella interna del plugin. Prima di attivare, verifica che le politiche di utilizzo delle API dei provider oauth2 consentano il salvataggio locale dei token di accesso (Nella maggior parte dei casi è vietato...).';
