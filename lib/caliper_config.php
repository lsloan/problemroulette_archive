<?php
// vendor is the directory where the caliper library is placed. Refer to README.md on importance of the vendor/
require_once realpath(dirname(__FILE__) . '/../vendor/autoload.php');
require_once 'Caliper/Options.php';

class CaliperConfig extends Options{
    private $sensor_id=false;
    private $caliper_http_id=false;
    private $caliper_client_id=false;
    private $caliper_proxy_url=false;
    private $caliper_proxy_enabled=false;
    private $ca_certs_path=false;


    public function getSensorId()
    {
        return $this->sensor_id;
    }
    public function setSensorId($sensor_id)
    {
        if(!is_string($sensor_id)){
            $sensor_id=strval($sensor_id);
        }
        $this->sensor_id = $sensor_id;
        return $this;
    }

    public function getCaliperHttpId()
    {
        return $this->caliper_http_id;
    }

    public function setCaliperHttpId($caliper_http_id)
    {
        if(!is_string($caliper_http_id)){
            $caliper_http_id=strval($caliper_http_id);
        }
        $this->caliper_http_id = $caliper_http_id;
        return $this;
    }

    public function getCaliperClientId()
    {
        return $this->caliper_client_id;
    }

    public function setCaliperClientId($caliper_client_id)
    {
        if(!is_string($caliper_client_id)){
            $caliper_client_id=strval($caliper_client_id);
        }
        $this->caliper_client_id = $caliper_client_id;
        return $this;
    }

    public function getCaliperProxyUrl()
    {
        return $this->caliper_proxy_url;
    }

    public function setCaliperProxyUrl($caliper_proxy_url)
    {
        if(!is_string($caliper_proxy_url)){
            $caliper_proxy_url=strval($caliper_proxy_url);
        }
        $this->caliper_proxy_url = $caliper_proxy_url;
        return $this;
    }

    public function getCaliperProxyEnabled()
    {
        return $this->caliper_proxy_enabled;
    }

    public function setCaliperProxyEnabled($caliper_proxy_enabled)
    {
        if(!is_bool($caliper_proxy_enabled)){
            $caliper_proxy_enabled=boolval($caliper_proxy_enabled);
        }
        $this->caliper_proxy_enabled = $caliper_proxy_enabled;
        return $this;
    }

    public function getCaCertsPath()
    {
        return $this->ca_certs_path;
    }

    public function setCaCertsPath($ca_certs_path)
    {
        if(!is_string($ca_certs_path)){
            $ca_certs_path=strval($ca_certs_path);
        }
        $this->ca_certs_path = $ca_certs_path;
        return $this;
    }


}
