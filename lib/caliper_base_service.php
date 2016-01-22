<?php

/*
 * Using the Dependency injection pattern we enable/disable the Caliper feature to Problem Roulette. This class acts as abstract implementation of the
 * pattern if $GLOBALS["CALIPER_ENABLED"] = false then generating a caliper event method call will do nothing.
 */

class BaseCaliperService {

    var $api_key;
    var $endpoint_url;
    var $sensor_id;
    var $debug;

    public function __construct($config, $debug = false) {
        $this->api_key      = $config["CALIPER_API_KEY"];
        $this->endpoint_url = $config["CALIPER_ENDPOINT_URL"];
        $this->sensor_id    = $config["CALIPER_SENSOR_ID"];
        $this->debug        = $debug;
    }

    /*
     * Navigation event from one page to another by user
     */
    public function captureNavigationEvent()
    {

    }

}
