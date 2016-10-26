<?php
// paths
require_once(dirname(__FILE__)."/paths.inc.php");
// error
require_once($GLOBALS["DIR_LIB"]."errors.php");

// logger
if (isset($GLOBALS['DIR_LOGGER']))
{
  # TODO: Verify that php_uname('n') gives server name in production.
  #       For php v5.3+ could use gethostname().
  $hostname = php_uname('n');
  $log_file = $GLOBALS['DIR_LOGGER']."problem_roulette_".$hostname.".log";
} else {
  $log_file = "/var/tmp/problem_roulette.log";
}
require_once( $GLOBALS["DIR_LIB"]."logger.php" );
$GLOBALS['app_log'] = new AppLogger($log_file);
global $app_log;

$GLOBALS['DEBUG'] = (isset($GLOBALS['DEBUG']) ? ((bool) $GLOBALS['DEBUG']) : false);
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
$GLOBALS["dbmgr"] = new CDbMgr();
// user manager
require_once( $DIR_LIB."usrmgr.php" );
$GLOBALS["usrmgr"] = new UserManager();
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
$args = GrabAllArgs();
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");

//caliper setup
require_once($GLOBALS["DIR_LIB"] . "caliper_base_service.php");
$caliper_config=null;

$caliper_defaults = array(
        'CALIPER_SENSOR_ID' => '',
        'CALIPER_CLIENT_ID' => '',
        'CALIPER_HTTP_ID' => '',
        'CALIPER_ENDPOINT_URL' => '',
        'CALIPER_API_KEY' => '',
        'CALIPER_PROXY_ENABLED' => true,
        'CALIPER_PROXY_ENDPOINT_URL' => '',
        'CA_CERTS_PATH' => '',
        'VIADUTOO_REMOTE_ENDPOINT_OAUTH_KEY' => '',
        'VIADUTOO_REMOTE_ENDPOINT_OAUTH_SECRET' => '',
        'DEBUG' => false,
);
$caliper_options = array_merge($caliper_defaults, $GLOBALS);

if (isset($GLOBALS["CALIPER_ENABLED"]) && $GLOBALS["CALIPER_ENABLED"] === true) {
    require_once($GLOBALS["DIR_LIB"] . "caliper_config.php");
    $caliper_config = new CaliperConfig();
    $caliper_config
        ->setSensorId($caliper_options['CALIPER_SENSOR_ID'])
        ->setCaliperClientId($caliper_options['CALIPER_CLIENT_ID'])
        ->setCaliperHttpId($caliper_options['CALIPER_HTTP_ID'])
        ->setHost($caliper_options['CALIPER_ENDPOINT_URL'])
        ->setApiKey($caliper_options['CALIPER_API_KEY'])
        ->setCaliperProxyEnabled($caliper_options['CALIPER_PROXY_ENABLED'])
        ->setCaliperProxyUrl($caliper_options['CALIPER_PROXY_ENDPOINT_URL'])
        ->setCaCertsPath($caliper_options['CA_CERTS_PATH'])
        ->setOauthKey($caliper_options['VIADUTOO_REMOTE_ENDPOINT_OAUTH_KEY'])
        ->setOauthSecret($caliper_options['VIADUTOO_REMOTE_ENDPOINT_OAUTH_SECRET'])
        ->setDebug($caliper_options['DEBUG']);
    require_once( $GLOBALS["DIR_LIB"]."caliper_service.php" );
    $GLOBALS["caliper"] = new CaliperService($caliper_config);
} else {
    $GLOBALS["caliper"] = new BaseCaliperService($caliper_config);
}


if (!extension_loaded('json')) {
    dl('json.so');
}

global $dbmgr;
global $usrmgr;

session_start();

// Handle session timeouts
if (isset($GLOBALS['timeout']) || (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600))) {
    if (isset($_SESSION['START_TIME'])) {
        $caliper->sessionTimeout();
    }
    session_unset();
    session_destroy();

    // When loading anything other than timeout.php, redirect there.
    if (!isset($GLOBALS['timeout'])) {
        header('Location: ' . $GLOBALS["DOMAIN"] . "timeout.php");
        exit;
    }
}
$_SESSION['LAST_ACTIVITY'] = time();
// Only let a session ID last two hours
if (!isset($_SESSION['SID_TIME'])) {
    $_SESSION['START_TIME'] = time();
    $_SESSION['SID_TIME'] = time();

    //In case of loopback call to the server when viadutoo enabled this call may create a brand new session but we don't
    //want to send the caliper session#loggedIN events during that time and once the loopback call is complete the session
    //created will not be used and the previous session that triggered the loopback call resumes.In case when timeout page
    //is called the previous session variables are unset we don't want session#loggedIN events sent.
    if (!(checkForLoopBackCall() || checkIfTimeOutCall())) {
        $caliper->sessionStart();
    }
} else if (time() - $_SESSION['SID_TIME'] > 7200) {
    session_regenerate_id(true);
    $_SESSION['SID_TIME'] = time();
}

function checkForLoopBackCall() {
    return isStringInURI('caliper_proxy.php');
}

function checkIfTimeOutCall() {
    return isStringInURI('timeout.php');
}

function isStringInURI($chunk) {
    return (basename($_SERVER['REQUEST_URI'], $chunk) === $chunk);
}

$_SESSION['sesstest'] = 1;

?>
