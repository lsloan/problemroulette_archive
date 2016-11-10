<?php
require_once realpath(dirname(__FILE__) . '/../vendor/autoload.php');
require_once 'Caliper/Options.php';

class CaliperConfig extends Options implements JsonSerializable {
    private $sensorId = false;
    private $caliperProxyUrl = false;
    private $caliperProxyEnabled = false;
    private $caCertsPath = false;
    private $oauthKey = false;
    private $oauthSecret = false;

    public function jsonSerialize() {
        $propertiesToSerialize = [];

        // Calling accessor methods allows getting private property values from parent class
        foreach (get_class_methods(get_class($this)) as $methodName) {
            if (substr($methodName, 0, 2) === 'is') {
                $propertyName = substr($methodName, 2);
            } elseif (substr($methodName, 0, 3) === 'get') {
                $propertyName = substr($methodName, 3);
            } else
                continue;

            $propertiesToSerialize[lcfirst($propertyName)] = $this->$methodName();
        }

        return $propertiesToSerialize;
    }

    public function getOauthKey() {
        return $this->oauthKey;
    }

    public function setOauthKey($oauthKey) {
        $this->oauthKey = strval($oauthKey);
        return $this;
    }

    public function getOauthSecret() {
        return $this->oauthSecret;
    }

    public function setOauthSecret($oauthSecret) {
        $this->oauthSecret = strval($oauthSecret);
        return $this;
    }

    public function getSensorId() {
        return $this->sensorId;
    }

    public function setSensorId($sensorId) {
        $this->sensorId = strval($sensorId);
        return $this;
    }

    public function getCaliperProxyUrl() {
        return $this->caliperProxyUrl;
    }

    public function setCaliperProxyUrl($caliperProxyUrl) {
        $this->caliperProxyUrl = strval($caliperProxyUrl);
        return $this;
    }

    public function getCaliperProxyEnabled() {
        return $this->caliperProxyEnabled;
    }

    public function setCaliperProxyEnabled($caliperProxyEnabled) {
        $this->caliperProxyEnabled = boolval($caliperProxyEnabled);
        return $this;
    }

    public function getCaCertsPath() {
        return $this->caCertsPath;
    }

    public function setCaCertsPath($caCertsPath) {
        $this->caCertsPath = strval($caCertsPath);
        return $this;
    }
}
