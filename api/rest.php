<?php
require_once(dirname(__FILE__).'/../setup.php');

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
        global $dbmgr, $usrmgr;
        $this->db = $dbmgr;
        $this->usrmgr = $usrmgr;
        $this->current_user = $usrmgr->m_user;
        $this->init();
    }

    function init() {
        return;
    }

    function get($path, $params) {
        $this->error(500);
    }

    function post($path, $params) {
        $this->error(500);
    }

    function collectParams($params, $fields) {
        $return = array();
        foreach ($fields as $field) {
            $return[] = $params[$field];
        }
        return $return;
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

    function error($code = 500, $details = "", $message = "", $headers = array()) {
        $message = $this->response_message($code, $message);
        $this->set_response_code($code, $message);
        foreach ($headers as $header) {
            header($header);
        }
        $error = array('error' => array('code' => $code, 'message' => $message, 'details' => $details));
        $this->render($error);
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

    function set_response_code($code, $message = "") {
        $message = $this->response_message($code, $message);
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            http_response_code($code);
        } else {
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            header($protocol . ' ' . $code . ' ' . $message);
        }
    }

    function pathInfo() {
        $pathInfo = (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');
        $path = explode('/', $pathInfo);
        return array_values(array_filter($path));
    }

    function expose() {
        $path = $this->pathInfo();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $params = $_GET;
            $result = $this->get($path, $params);
        } else {
            $params = $_POST;
            $result = $this->post($path, $params);
        }

        $this->render($result);
    }
}

