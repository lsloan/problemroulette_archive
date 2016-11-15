<?php

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

    public function setUp() {
        $this->config = $this->args['config'];
        $this->event = $this->args['event'];
    }

    public function perform() {
        echo 'sensorId: ' . $this->config['sensorId'];
        echo "\n\n";
    }

//    public function tearDown() {
//        // ... Remove environment for this job
//    }
}
