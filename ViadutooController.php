<?php
require_once 'Viadutoo/MessageProxy.php';
require_once 'Viadutoo/transport/CurlTransport.php';
require_once 'Viadutoo/db/MysqlStorage.php';

class ViadutooController {
    /** @var CaliperConfig */
    private $config;
    /** @var AppLogger */
    private $appLogger;
    /** @var CDbMgr */
    private $dbMgr;

    public function __construct(CaliperConfig $config, AppLogger $appLogger, CDbMgr $dbMgr) {
        $this->config = $config;
        $this->appLogger = $appLogger;
        $this->dbMgr = $dbMgr;
    }

    public function run() {
        $validRequest = respondAndCloseConnection();

        if ($validRequest !== true) {
            exit;
        }

        $headers = getallheaders();
        $body = file_get_contents('php://input');

        $app_log = $this->appLogger;

        if (
            empty($this->config->getCaCertsPath()) ||
            empty($this->config->getHost()) ||
            empty($this->config->getOauthKey()) ||
            empty($this->config->getOauthSecret())
        ) {
            $app_log->msg("Some Viadutoo configuration values are missing.  Unable to send Caliper event. " .
                "caCertsPath = '$this->config->getCaCertsPath()'; " .
                "host = '$this->config->getHost()'; " .
                "oauthKey = '$this->config->getOauthKey()'; " .
                "oauthSecret = " . (empty($this->config->getOauthSecret()) ? 'NOT_SET' : 'SET_BUT_NOT_SHOWN'));
            exit;
        }

        $this->sendOrStore($headers, $body);
    }

    public function sendOrStore($headers, $body) {
        $dbmgr = $this->dbMgr;
        $app_log = $this->appLogger;

        $proxy = (new MessageProxy())
            ->setTransportInterface((new CurlTransport())
                ->setCACertPath($this->config->getCaCertsPath())
                ->setAuthZType(
                    CurlTransport::AUTHZ_TYPE_OAUTH1,
                    $this->config->getOauthKey(),
                    $this->config->getOauthSecret()))
            ->setEndpointUrl($this->config->getHost())
            ->setTimeoutSeconds(10)
            ->setAutostoreOnSendFailure(false)
            ->setStorageInterface(
                new MysqlStorage(
                    $dbmgr->m_host, $dbmgr->m_user, $dbmgr->m_pswd, $dbmgr->m_db, 'caliper_events'));

        $success = null;
        try {
            $success = $proxy
                ->setHeaders($headers)
                ->setBody($body)
                ->send();
        } catch (Exception $exception) {
            $app_log->msg(sprintf('Exception in %s (line %d): %s',
                __METHOD__, __LINE__, $exception->getMessage()));
        }

        if (($success !== true)) {
            $proxy->store();
            $app_log->msg(sprintf('Warning in %s (line %d): Failed to send message.  Storage in DB: %s',
                __METHOD__, __LINE__,
                (($proxy->getStorageInterface()->getLastSuccessFromStore() === true) ? 'success' : 'fail')));
        }
    }

    /**
     * Send response to remote client and close the connection.
     *
     * This is important since this program needs to accept input and respond as quickly as possible.
     * It may take time for the sending of data to complete and that shouldn't be allowed to get in
     * the way.
     *
     * @return bool Request is valid
     */
    protected function respondAndCloseConnection() {
        $app_log = $this->appLogger;
        ob_end_clean(); // Discard any previous output
        ob_start(); // Start output buffer so it can be flushed on demand

        $validRequestHost = (@$_SERVER['SERVER_NAME'] === @$_SERVER['REMOTE_ADDR']) &&
            (@$_SERVER['SERVER_NAME'] === '127.0.0.1');
        $validRequestMethod = (strtoupper(@$_SERVER['REQUEST_METHOD']) === 'POST');

        $validRequest = false;

        if ($validRequestHost !== true) {
            http_response_code(403);
            $app_log->msg(sprintf('Error in %s (line %d): ' .
                'Request to Viadutoo with invalid source address ("%s", required "127.0.0.1").',
                __METHOD__, __LINE__, @$_SERVER['REMOTE_ADDR']));
        } elseif ($validRequestMethod !== true) {
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
}