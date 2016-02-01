<?php
class CaliperConfig{
    private $api_key;
    private $sensor_id;
    private $endpoint_url;
    private $debug;
    private $caliper_http_id;
    private $caliper_client_id;

    public function __construct(){
        $this->setDebug(false);
    }

    public function getEndpointUrl()
    {
        return $this->endpoint_url;
    }

    public function setEndpointUrl($endpoint_url)
    {
        $this->endpoint_url = $endpoint_url;
        return $this;
    }

    public function getDebug()
    {
        return $this->debug;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }
    public function getApiKey()
    {
        return $this->api_key;
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    public function getSensorId()
    {
        return $this->sensor_id;
    }

    public function getCaliperHttpId()
    {
        return $this->caliper_http_id;
    }
    public function setCaliperHttpId($caliper_http_id)
    {
        $this->caliper_http_id = $caliper_http_id;
        return $this;
    }

    public function getCaliperClientId()
    {
        return $this->caliper_client_id;
    }

    public function setCaliperClientId($caliper_client_id)
    {
        $this->caliper_client_id = $caliper_client_id;
        return $this;
    }

    public function setSensorId($sensor_id)
    {
        $this->sensor_id = $sensor_id;
        return $this;
    }
}