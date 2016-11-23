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

    public static function fromArray($configValues) {
        $caliperConfig = new CaliperConfig();

        if (array_key_exists('apiKey', $configValues)) {
            $caliperConfig->setApiKey($configValues['apiKey']);
        }
        if (array_key_exists('caCertsPath', $configValues)) {
            $caliperConfig->setCaCertsPath($configValues['caCertsPath']);
        }
        if (array_key_exists('caliperProxyEnabled', $configValues)) {
            $caliperConfig->setCaliperProxyEnabled($configValues['caliperProxyEnabled']);
        }
        if (array_key_exists('caliperProxyUrl', $configValues)) {
            $caliperConfig->setCaliperProxyUrl($configValues['caliperProxyUrl']);
        }
        if (array_key_exists('connectionRequestTimeout', $configValues)) {
            $caliperConfig->setConnectionRequestTimeout(intval($configValues['connectionRequestTimeout']));
        }
        if (array_key_exists('connectionTimeout', $configValues)) {
            $caliperConfig->setConnectionTimeout($configValues['connectionTimeout']);
        }
        if (array_key_exists('debug', $configValues)) {
            $caliperConfig->setDebug($configValues['debug']);
        }
        if (array_key_exists('host', $configValues)) {
            $caliperConfig->setHost($configValues['host']);
        }
        if (array_key_exists('httpHeaders', $configValues)) {
            $caliperConfig->setHttpHeaders($configValues['httpHeaders']);
        }
        if (array_key_exists('jsonEncodeOptions', $configValues)) {
            $caliperConfig->setJsonEncodeOptions($configValues['jsonEncodeOptions']);
        }
        if (array_key_exists('oauthKey', $configValues)) {
            $caliperConfig->setOauthKey($configValues['oauthKey']);
        }
        if (array_key_exists('oauthSecret', $configValues)) {
            $caliperConfig->setOauthSecret($configValues['oauthSecret']);
        }
        if (array_key_exists('sensorId', $configValues)) {
            $caliperConfig->setSensorId($configValues['sensorId']);
        }
        if (array_key_exists('socketTimeout', $configValues)) {
            $caliperConfig->setSocketTimeout(intval($configValues['socketTimeout']));
        }

        return $caliperConfig;
    }

    public function getOauthKey() {
        return $this->oauthKey;
    }

    /**
     * @param $oauthKey
     * @return $this|CaliperConfig
     */
    public function setOauthKey($oauthKey) {
        $this->oauthKey = strval($oauthKey);
        return $this;
    }

    public function getOauthSecret() {
        return $this->oauthSecret;
    }

    /**
     * @param $oauthSecret
     * @return $this|CaliperConfig
     */
    public function setOauthSecret($oauthSecret) {
        $this->oauthSecret = strval($oauthSecret);
        return $this;
    }

    public function getSensorId() {
        return $this->sensorId;
    }

    /**
     * @param $sensorId
     * @return $this|CaliperConfig
     */
    public function setSensorId($sensorId) {
        $this->sensorId = strval($sensorId);
        return $this;
    }

    public function getCaliperProxyUrl() {
        return $this->caliperProxyUrl;
    }

    /**
     * @param $caliperProxyUrl
     * @return $this|CaliperConfig
     */
    public function setCaliperProxyUrl($caliperProxyUrl) {
        $this->caliperProxyUrl = strval($caliperProxyUrl);
        return $this;
    }

    public function getCaliperProxyEnabled() {
        return $this->caliperProxyEnabled;
    }

    /**
     * @param $caliperProxyEnabled
     * @return $this|CaliperConfig
     */
    public function setCaliperProxyEnabled($caliperProxyEnabled) {
        $this->caliperProxyEnabled = boolval($caliperProxyEnabled);
        return $this;
    }

    public function getCaCertsPath() {
        return $this->caCertsPath;
    }

    /**
     * @param $caCertsPath
     * @return $this|CaliperConfig
     */
    public function setCaCertsPath($caCertsPath) {
        $this->caCertsPath = strval($caCertsPath);
        return $this;
    }
}
