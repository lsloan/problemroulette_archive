<?php
require_once(__DIR__.'/../setup.php');

abstract class Resource {

    protected $ERROR_CODES = array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error',
        501 => 'Unimplemented'
    );

    var $db;

    function __construct() {
        global $dbmgr;
        $this->db = $dbmgr;
    }

    function get($params) {
        $this->error(500);
    }

    function post($params) {
        $this->error(500);
    }

    function encode($result) {
        $encoded = $result;
        if (is_array($result)) {
            $encoded = json_encode($result);
        }
        return $encoded;
    }

    function render($result) {
        header('Content-Type: application/json');
        echo $this->encode($result);
    }

    function error($code = 500, $headers = array(), $body = "", $message = "") {
        $this->set_response_code($code, $message);
        foreach ($headers as $header) {
            header($header);
        }
        $this->render($body);
        exit;
    }

    function response_message($code, $message) {
        if ($message == "") {
            if (isset($this->ERROR_CODES[$code])) {
                $message = $this->ERROR_CODES[$code];
            } else {
                $message = 'Internal Server Error';
            }
        }
        return $message;
    }

    function set_response_code($code, $message) {
        $message = $this->response_message($code, $message);
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            http_response_code($code);
        } else {
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            header($protocol . ' ' . $code . ' ' . $message);
        }
    }

    function expose() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $params = $_GET;
            $result = $this->get($params);
        } else {
            $params = $_POST;
            $result = $this->post($params);
        }

        $this->render($result);
    }
}

