<?php
// vendor is the directory where the caliper library is placed. Refer to README.md on importance of the vendor/
require_once 'vendor/autoload.php';
require_once 'Caliper/Options.php';

class CaliperConfig extends Options{
    private $sensor_id;
    private $caliper_http_id;
    private $caliper_client_id;


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