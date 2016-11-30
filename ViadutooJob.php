<?php
require_once 'ViadutooController.php';

/*
 * Class used to represent a Viadutoo job stored by Resque in a Redis queue.
 * Will contain properties such as the Caliper event and configuration values
 * and methods to process the event, either sending it to an endpoint or
 * storing it in a DB.
 */

class ViadutooJob {
    /** @var array Required: Resque will put values from queued job here.  Must be public. */
    public $args;
    /** @var array Configuration values for this job. */
    private $config;
    /** @var string JSON encoded Caliper event. */
    private $event;
    /** @var CaliperConfig Created from the values in the configuration array. */
    private $caliperConfig;
    /** @var ViadutooController */
    private $viadutoo;

    /**
     * Called first by Resque for each ViadutooJob in its queue.
     * This method is optional.  If it weren't defined, Resque would
     * go on to calling the perform() method.
     *
     * Intended to set up the environment before processing the queued
     * job.  This method is optional.
     */
    public function setUp() {
        $this->config = $this->args['config'];
        $this->event = $this->args['event'];

        $this->caliperConfig = CaliperConfig::fromArray($this->config);

        $this->viadutoo = new ViadutooController(
            $this->caliperConfig,
            $GLOBALS['app_log'],
            $GLOBALS['dbmgr']
        );
    }

    /**
     * Called second by Resque for each ViadutooJob in its queue.
     * This method is required.
     *
     * Intended to do something with each queued job.  In this case,
     * use Viadutoo to try sending the Caliper event to the LRS endpoint.
     */
    public function perform() {
        $headers = $this->makeHeaders();

        $this->viadutoo->sendOrStore($headers, $this->event);
    }

    /**
     * This is usually done by Caliper's HttpRequestor::send() method
     * @return array
     */
    private function makeHeaders() {
        $headers = array_merge($this->caliperConfig->getHttpHeaders(), [
            'Content-Type' => 'application/json',
            'Authorization' => $this->caliperConfig->getApiKey(),
            'User-Agent' => 'Resque worker class: ' . __CLASS__,
        ]);

        return $headers;
    }
}

