<?php
class CaliperConfig{
    private $api_key;
    private $sensor_id;
    private $endpoint_url;
    private $debug;
    private $caliper_http;
    private $caliper_client;

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

    public function getCaliperHttp()
    {
        return $this->caliper_http;
    }
    public function setCaliperHttp($caliper_http)
    {
        $this->caliper_http = $caliper_http;
        return $this;
    }

    public function getCaliperClient()
    {
        return $this->caliper_client;
    }

    public function setCaliperClient($caliper_client)
    {
        $this->caliper_client = $caliper_client;
        return $this;
    }

    public function setSensorId($sensor_id)
    {
        $this->sensor_id = $sensor_id;
        return $this;
    }
}