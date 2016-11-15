<?php
/*
 * A proxy that receives a Caliper event (a JSON payload) to be sent to an endpoint.
 * If the sending fails, the event is stored in the local database.
 */
require_once 'setup.php';

require_once 'vendor/autoload.php';
require_once 'Viadutoo/MessageProxy.php';
require_once 'Viadutoo/transport/CurlTransport.php';
require_once 'Viadutoo/db/MysqlStorage.php';

/**
 * Send response to remote client and close the connection.
 *
 * This is important since this program needs to accept input and respond as quickly as possible.
 * It may take time for the sending of data to complete and that shouldn't be allowed to get in
 * the way.
 *
 * @return bool Request is valid
 */
function respondAndCloseConnection(){
    global $app_log;
    ob_end_clean(); // Discard any previous output
    ob_start(); // Start output buffer so it can be flushed on demand

    $validRequestHost = (@$_SERVER['SERVER_NAME'] === @$_SERVER['REMOTE_ADDR']) &&
        (@$_SERVER['SERVER_NAME'] === '127.0.0.1');
    $validRequestMethod = (strtoupper(@$_SERVER['REQUEST_METHOD']) === 'POST');

    $validRequest = false;

    if ( $validRequestHost !== true ) {
        http_response_code(403);
        $app_log->msg(sprintf('Error in %s (line %d): ' .
                'Request to Viadutoo with invalid source address ("%s", required "127.0.0.1").',
                __METHOD__, __LINE__, @$_SERVER['REMOTE_ADDR']));
    } elseif ( $validRequestMethod !== true ) {
        http_response_code(405);
        $app_log->msg(sprintf('Error in %s (line %d): ' .
                'Request to Viadutoo with invalid method ("%s", required "POST").',
                __METHOD__, __LINE__, @$_SERVER['REQUEST_METHOD']));
    } else {
        http_response_code(200); //OK
        $validRequest = true;
    }

    header('Content-Length: ' . ob_get_length());
    header('Connection: close');  // Tell client to close connection *now*

    ob_end_flush(); // End output buffer and flush it to client (part 1)
    flush(); // Part 2 of complete flush

    if (session_id()) {
        session_write_close(); // Closing session prevents blocking on later requests
    }

    return $validRequest;
}

$validRequest = respondAndCloseConnection();

if ($validRequest !== true) {
    exit;
}

$headers = getallheaders();
$body = file_get_contents('php://input');

global $dbmgr;
global $app_log;

$caCertPath = $caliper_config->getCaCertsPath();
$endpointUrl = $caliper_config->getHost();
$oauthKey = $caliper_config->getOauthKey();
$oauthSecret = $caliper_config->getOauthSecret();

if ((empty($caCertPath) || empty($endpointUrl)|| empty($oauthKey) || empty($oauthSecret))) {
    $app_log->msg("Some viadutoo configurations are missing, unable to send Caliper Event. " .
        "caCertPath = '$caCertPath'; endpointUrl = '$endpointUrl'; oauthkey = '$oauthKey'; oauthSecret =" . (empty($oauthSecret) ? "'$oauthSecret'" : 'NOT_SHOWN'));
    exit;
}
$proxy = (new MessageProxy())
    ->setTransportInterface((new CurlTransport())
            ->setCACertPath($caCertPath)
            ->setAuthZType(CurlTransport::AUTHZ_TYPE_OAUTH1, $oauthKey, $oauthSecret))
    ->setEndpointUrl($endpointUrl)
    ->setTimeoutSeconds(10)
    ->setAutostoreOnSendFailure(false)
    ->setStorageInterface(new MysqlStorage($dbmgr->m_host, $dbmgr->m_user, $dbmgr->m_pswd, $dbmgr->m_db, 'caliper_events'));

$success = null;
try {
    $success = $proxy
        ->setHeaders($headers)
        ->setBody($body)
        ->send();
} catch ( Exception $exception ) {
    $app_log->msg(sprintf('Exception in %s (line %d): %s',
            __METHOD__, __LINE__, $exception->getMessage()));
}

if (($success !== true)) {
    $proxy->store();
    $app_log->msg(sprintf('Warning in %s (line %d): Failed to send message.  Storage in DB: %s',
            __METHOD__, __LINE__,
            (($proxy->getStorageInterface()->getLastSuccessFromStore() === true) ? 'success' : 'fail')));
}
