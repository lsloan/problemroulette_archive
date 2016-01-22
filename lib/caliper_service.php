<?php

/*
 * Using the Dependency injection pattern we enable/disable the Caliper feature to Problem Roulette. This class provide the actual implementation for a
 * particular caliper event and will log the call to the event store where all the event are captured.
 */

require_once 'setup.php';
// vendor is the directory where the caliper library is placed. Refer to README.md on importance of the vendor/
require_once 'vendor/autoload.php';

class CaliperService extends BaseCaliperService
{
    // implement  caliper specific code capturing a navigation event
    public function captureNavigationEvent()
    {

    }
}