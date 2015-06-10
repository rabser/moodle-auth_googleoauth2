<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'auth_google', language 'en'
 *
 * @package   auth_google
 * @author Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Oauth2';
$string['auth_facebookclientid'] = 'Tu App ID/Secreto puede ser generado en tu <a href="https://developers.facebook.com/apps/">página de desarrollador de Facebook</a>:
<br/>Sitio URL: {$a->siteurl}
<br/>Sitio del dominio: {$a->sitedomain}';
$string['auth_facebookclientid_key'] = 'Facebook App ID';
$string['auth_facebookclientsecret'] = 'Ver arriba.';
$string['auth_facebookclientsecret_key'] = 'Facebook App secreto';
$string['auth_githubclientid'] = 'Tu ID cliente/Secreto puede ser generado en tu <a href="https://github.com/settings/applications/new">página de solicitud de registro en Github</a>:
<br/>Página de inicio URL: {$a->siteurl}
<br/>Callback URL de autorización: {$a->callbackurl}';
$string['auth_githubclientid_key'] = 'Github ID ciente';
$string['auth_githubclientsecret'] = 'Ver arriba.';
$string['auth_githubclientsecret_key'] = 'Github secreto cliente';
$string['auth_googleclientid'] = 'Tu ID cliente/Secreto puede ser generado en tu <a href="https://code.google.com/apis/console">consola de Google API</a>:
<br/>
Google consola API > Acceso API > Crear otro ID cliente...
<br/>
URLs de redireccionamiento: {$a->redirecturls}
<br/>
Orígenes de Javascript: {$a->jsorigins}';
$string['auth_googleclientid_key'] = 'Cliente ID de Google';
$string['auth_googleclientsecret'] = 'Ver arriba.';
$string['auth_googleclientsecret_key'] = 'Secreto cliente de Google';
$string['auth_googleipinfodbkey'] = 'IPinfoDB es un servicio que te permite averiguar el país y ciudad del visitante. Este ajuste es opcional. Puedes subscribir a <a href="http://www.ipinfodb.com/register.php">IPinfoDB</a> para conseguir una clave.<br/>
Website: {$a->website}';
$string['auth_googleipinfodbkey_key'] = 'Clave IPinfoDB';
$string['auth_googleuserprefix'] = 'El nombre de usuario creado empezará con este prefijo. En un sitio Moodle sencillo, no hace falta cambiarlo.';
$string['auth_googleuserprefix_key'] = 'Prefijo del nombre de usuario';
$string['auth_googleoauth2description'] = 'Permite que el usuario pueda conectar al sitio web con un servicio externo: Google/Facebook/Windows Live. La primera vez que un usuario conecta con un servicio externo, se crea una cuenta nueva. <a href="'.$CFG->wwwroot.'/admin/search.php?query=authpreventaccountcreation">Evitar la creación de cuentas al identificarse</a> <b>debe</b> estar deshabilitado.
<br/><br/>
<i>Aviso acerca de Windows Live: Microsoft no informa sobre la extensión si la dirección de correo ha sido verificado. Más información en el <a href="https://github.com/mouneyrac/auth_googleoauth2/wiki/FAQ">FAQ</a>.</i>';
$string['auth_linkedinclientid'] = 'Tus claves secretas/API pueden ser generados en tu <a href="https://www.linkedin.com/secure/developer">página de registro de Linkedin</a>:
<br/>URL de página web: {$a->siteurl}
<br/>OAuth 1.0 Acceptar URL de redirección: {$a->callbackurl}';
$string['auth_linkedinclientid_key'] = 'Clave API de Linkedin';
$string['auth_linkedinclientsecret'] = 'Ver arriba.';
$string['auth_linkedinclientsecret_key'] = 'Clave secreto de Linkedin';
$string['auth_messengerclientid'] = 'Tu ID cliente/secreto puede ser generado en tu <a href="https://account.live.com/developers/applications">página de Windows Live</a>:
<br/>Redirect domain: {$a->domain}';
$string['auth_messengerclientid_key'] = 'ID Cliente Messenger';
$string['auth_messengerclientsecret'] = 'Ver arriba.';
$string['auth_messengerclientsecret_key'] = 'Secreto cliente Messenger';

$string['auth_googlesettings'] = 'Configuración';
$string['couldnotgetgoogleaccesstoken'] = 'The authentication provider sent us a communication error. Please try to sign-in again.';
$string['oauth2displaybuttons'] = 'Display buttons on login page';
$string['oauth2displaybuttonshelp'] = 'Mostrar los botones con el logo de Google, Facebook, etc. en la parte superior de la página de inicio de sesión. Si quieres posicionar los botones tu mismo en la página de inicio de sesión, deshabilitar esta opción y añade el siguiente código:
{$a}';
$string['emailaddressmustbeverified'] = 'Tu dirección de correo electrónico no ha sido verificado por el método de autentificación que has seleccionado. Puede ser que te hayas olvidado de hacer click en el enlace "verificar dirección de correo" que Google o Facebook debía haberte mandado durante la subscripción a su servicio.';
$string['auth_sign-in_with'] = 'Iniciar sesión con {$a->providername}';
$string['moreproviderlink'] = 'Iniciar sesión con otro servicio.';
$string['signinwithanaccount'] = 'Iniciar sesión con:';
$string['noaccountyet'] = 'Todavía no tienes permiso para utilizar este sitio. Contacte con tu administrador y pidele que active tu cuenta.';
