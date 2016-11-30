<?php
error_reporting(E_ALL);
// Set display_errors in development to view them in browser
// ini_set('display_errors', '1');

// All the global stuff goes here and gets included on every page

// Set to true for a bit more debugging info like query counts
$GLOBALS["DEBUG"] = false;

// Set MySQL server and credentials
$GLOBALS["SQL_SERVER"]   = "localhost";
$GLOBALS["SQL_USER"]     = "";
$GLOBALS["SQL_PASSWORD"] = "";
$GLOBALS["SQL_DATABASE"] = 'pr';
$GLOBALS["SQL_PORT"]     = 3306;

// Set the URL that will be used to access PR, when not called from CLI
if (php_sapi_name() !== 'cli') {
    // Recommended setting:
    //$GLOBALS['DOMAIN'] = sprintf(
    //    '%s://%s/',
    //    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    //    $_SERVER['SERVER_NAME']
    //);
    $GLOBALS["DOMAIN"]       = "http://pr.local/";
}

// Set the absolute path to the application root directory; include trailing slash.
// Recommended setting:
//$GLOBALS["DIR"]          = __DIR__ . DIRECTORY_SEPARATOR;
$GLOBALS["DIR"]          = "/path/to/pr/directory/";

// The instance is just for logging and emails, no decisions are made based on its value
$GLOBALS["INSTANCE"]     = "Dev";

// Path to downloads directory; the default "downloads" exists
$GLOBALS["PATH_DOWNLOADS"] = "downloads/";

// Path to stats export directory; this should not be public so the app can protect access
// The default "stats" directory has an .htaccess file denying all direct access
$GLOBALS["DIR_STATS"]      = "stats/";

// Log under the application directory by default; ensure that it exists and has appropriate permissions
// The default "log/Dev" directory has an .htaccess file denying all direct accesss
$GLOBALS['DIR_LOGGER']     = $GLOBALS["DIR"]."log/".$GLOBALS["INSTANCE"]."/";

// Migrations can send a confirmation email if enabled
$GLOBALS["SEND_MIGRATION_MAIL"]  = false;
$GLOBALS["MIGRATION_EMAIL_FROM"] = "ltig-staff@umich.edu";
$GLOBALS["MIGRATION_EMAIL_TO"]   = "ltig-staff@umich.edu";


// These are just concatenations by default, but could be customized if
// images and other files were stored outside the application root.
if (php_sapi_name() !== 'cli') {
    $GLOBALS["DOMAIN_PICS"] = $DOMAIN . "pics/";
    $GLOBALS["DOMAIN_JS"] = $DOMAIN . "js/";
    $GLOBALS["DOMAIN_CSS"] = $DOMAIN . "css/";
    $GLOBALS["DOMAIN_LIB"] = $DOMAIN . "lib/";
}
$GLOBALS["DIR_PICS"]      = $GLOBALS["DIR"]."pics/";
$GLOBALS["DIR_JS"]        = $GLOBALS["DIR"]."js/";
$GLOBALS["DIR_CSS"]       = $GLOBALS["DIR"]."css/";
$GLOBALS["DIR_LIB"]       = $GLOBALS["DIR"]."lib/";
$GLOBALS["DIR_DOWNLOADS"] = $GLOBALS["DIR"].$GLOBALS["PATH_DOWNLOADS"];

/*
 * Caliper configuration values
 */
// Indicate whether Caliper support should be enabled (boolean)
$GLOBALS["CALIPER_ENABLED"]       = false;
// The API key that may be required by the endpoint to accept Caliper events (string)
$GLOBALS["CALIPER_API_KEY"]       = null;
// URL of the endpoint (string)
$GLOBALS["CALIPER_ENDPOINT_URL"]  = null; // E.g., 'http://lti.tools/caliper/event?key=problemroulette'
// An ID used to distinguish this app's events in the endpoint from those of other apps (string)
$GLOBALS["CALIPER_SENSOR_ID"]     = null; // E.g., 'problem_roulette'
// Indicate whether Caliper should send events using a proxy, like Viadutoo (boolean)
$GLOBALS["CALIPER_PROXY_ENABLED"] = false;
// URL of the endpoint proxy, usually an internal web service accessed via the loopback interface (string)
$GLOBALS["CALIPER_PROXY_ENDPOINT_URL"]=null; // E.g., 'http://127.0.0.1:8888/problemroulette/caliper_proxy.php'
// Full pathname of directory containing CA certificates for verifying HTTPS connections (string)
$GLOBALS["CA_CERTS_PATH"]=null; // E.g., '/path/to/CA/Cert/directory'
// OAuth key that may be required by the endpoint to accept Caliper events (string)
$GLOBALS["VIADUTOO_REMOTE_ENDPOINT_OAUTH_KEY"]=null;
// OAuth secret that may be required by the endpoint to accept Caliper events (string)
$GLOBALS["VIADUTOO_REMOTE_ENDPOINT_OAUTH_SECRET"]=null;
// Indicate whether Viadutoo should use Redis support (boolean)
// (When Redis support is enabled, CALIPER_PROXY_ENDPOINT_URL will not be used.)
$GLOBALS['VIADUTOO_REDIS_ENABLED'] = false;
// Redis host name/address and port number, colon-delimited (string, optional)
$GLOBALS['VIADUTOO_REDIS_HOST_PORT'] = '127.0.0.1:6379';
// Name of the queue in Redis to be used by Viadutoo (string)
$GLOBALS['VIADUTOO_REDIS_QUEUE_NAME'] = 'default';
