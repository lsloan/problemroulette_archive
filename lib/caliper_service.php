<?php

/*
 * Using the Dependency injection pattern we enable/disable the Caliper feature to Problem Roulette. This class provide the actual implementation for a
 * particular caliper event and will log the call to the event store where all the event are captured.
 */

// vendor is the directory where the caliper library is placed. Refer to README.md on importance of the vendor/
require_once 'vendor/autoload.php';
require_once 'Caliper/Options.php';
require_once 'Caliper/actions/Action.php';
require_once 'Caliper/entities/session/Session.php';
require_once 'Caliper/entities/agent/SoftwareApplication.php';
require_once 'Caliper/entities/agent/Person.php';
require_once 'Caliper/events/SessionEvent.php';
require_once 'Caliper/entities/reading/WebPage.php';
require_once 'Caliper/events/NavigationEvent.php';
require_once 'Caliper/entities/lis/Group.php';
require_once 'Caliper/entities/lis/CourseOffering.php';

class CaliperService extends BaseCaliperService
{
    const BLANK_NODE = '_:';

    public function captureNavigationEventFromCourseToTopicView($course_name, $course_id){
        global $usrmgr;
        $person = new Person('https://mcommunity.umich.edu/#profile:' . $usrmgr->m_user->username);
        $person->setName($usrmgr->m_user->username);

         /* we are using the blank node notation instead of unique identifier navigating from Course view -> Topic view since we don't have a
         unique url associated with the views. More info on blank node refer https://www.w3.org/TR/json-ld/#identifying-blank-nodes */

        $eventObj = new WebPage(self::BLANK_NODE.'problemroulette/views/selections/courses/'.$course_id.'/topics');
        $eventObj->setName("Selections: $course_name Topics");

        $navigatedFrom = new WebPage(self::BLANK_NODE .'problemroulette/views/selections/courses');
        $navigatedFrom->setName('Selections: Course List');

        $group = new CourseOffering(self::BLANK_NODE . 'problemroulette/courses/'.$course_id);
        $group->setName($course_name);

        $navigationEvent=new NavigationEvent();
        $navigationEvent->setActor($person)
            ->setObject($eventObj)
            ->setNavigatedFrom($navigatedFrom)
            ->setEventTime(new DateTime())
            ->setEdApp(new SoftwareApplication($this->getUrl()))
            ->setGroup($group);

        $this->sendEvent($navigationEvent);

    }
    /*
     * sending the caliper event to the eventstore
     */
    private function sendEvent($event){
        global $app_log;
        $sensorId = $this->config->getSensorId();
        $endpointUrl = $this->config->getEndpointUrl();
        $apiKey = $this->config->getApiKey();
        $caliperHttpId = $this->config->getCaliperHttpId();
        $caliperClientId = $this->config->getCaliperClientId();

        // caliper variable should not be empty or null
        if(!($sensorId && $endpointUrl && $apiKey && $caliperHttpId && $caliperClientId )){
            $app_log->msg("Some caliper configurations are missing, unable to send Caliper Event. " .
                "sensorId = '$sensorId'; endpointUrl = '$endpointUrl'; apiKey = '$apiKey'
                ; caliperHttpId = '$caliperHttpId'; caliperClientId = '$caliperClientId'");
            return;
        }
        $sensor = new Sensor($sensorId);
        $options = (new Options())
            ->setApiKey($apiKey)
            ->setDebug($this->config->getDebug())
            ->setHost($endpointUrl);

        $sensor->registerClient($caliperHttpId, new Client($caliperClientId, $options));
        $sensor->send($sensor, $event);
    }

    /* getting the application URL
     * @return string
     */
    private function getUrl()
    {
        $protocol=stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'];
    }

}