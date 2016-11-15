<?php
require_once '../ViadutooController.php';

/*
 * Class used to represent a Viadutoo job stored by Resque in a Redis queue.
 * Will contain properties such as the Caliper event and configuration values
 * and methods to process the event, either sending it to an endpoint or
 * storing it in a DB.
 */

class ViadutooJob {
    /** @var array */
    private $config;
    /** @var array */
    private $event;
    /** @var ViadutooController */
    private $viadutoo;

    public function setUp() {
        $this->config = $this->args['config'];
        $this->event = $this->args['event'];

        // TODO: figure out how to get and pass along logger and DB objects
        $this->viadutoo = new ViadutooController($this->config, null, null);
    }

    public function perform() {
        echo 'sensorId: ' . $this->config['sensorId'];
        echo "\n\n";

        // TODO: figure out which headers to use
        $headers = null;

        $this->viadutoo->sendOrStore($headers, $this->event);
    }

//    public function tearDown() {
//        // ... Remove environment for this job
//    }
}
