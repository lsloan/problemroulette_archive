<?php

/*
 * Using the Dependency injection pattern we enable/disable the Caliper feature to Problem Roulette. This class provide the actual implementation for a
 * particular caliper event and will log the call to the event store where all the event are captured.
 */


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
    var $config;
    public function __construct($config)
    {
        parent::__construct($config);
        if(!($config instanceof CaliperConfig)){
            global $app_log;
            $app_log->msg("In the class \"".__CLASS__."\" constructor, object expected was \"CaliperConfig\" but got: ".get_class($config));
            throw new InvalidArgumentException('Object expected was "CaliperConfig" but got '.get_class($config) );


        }
        $this->config=$config;
    }

    /* we are using the blank node notation instead of unique identifier sometimes since we don't have a
       unique url associated with the views. More info on blank node refer https://www.w3.org/TR/json-ld/#identifying-blank-nodes */

    const BLANK_NODE = '_:';

    public function sendNavigationEvent($course_name, $course_id){
        $course_name_val=strval($course_name);
        $course_id_val=strval($course_id);
        $navigationEvent=new NavigationEvent();
        $navigationEvent->setActor($this->getPerson())
            ->setObject($this->webPage($course_name_val, $course_id_val))
            ->setNavigatedFrom($this->webPage())
            ->setEventTime(new DateTime())
            ->setEdApp(new SoftwareApplication($this->getUrl()))
            ->setGroup($this->courseOffering($course_name_val, $course_id_val));

        $this->sendEvent($navigationEvent);

    }
    /*
     * sending the caliper event to the eventstore
     */
    private function sendEvent($event){
        global $app_log;
        $sensorId = $this->config->getSensorId();
        $endpointUrl = $this->config->getHost();
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
            ->setDebug($this->config->isDebug())
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

    /**
     * @param $usrmgr
     * @return Person
     */
    private function getPerson()
    {
        global $usrmgr;
        $userName = strval($usrmgr->m_user->username);
        $person = new Person('https://mcommunity.umich.edu/#profile:' . urlencode($userName));
        $person->setName($userName);
        return $person;
    }

    /**
     * @param $course_name
     * @param $course_id
     * @return WebPage
     */
    private function webPage($course_name=null, $course_id=null)
    {
        $webPage=null;
        if(($course_name && $course_id)) {
            $webPage = new WebPage(self::BLANK_NODE . 'problemroulette/views/selections/courses/' . urlencode($course_id). '/topics');
            $webPage->setName("Selections: $course_name Topics");
        }else{
            $webPage=new WebPage(self::BLANK_NODE .'problemroulette/views/selections/courses');
            $webPage->setName('Selections: Course List');
        }
        return $webPage;
    }

    /**
     * @param $course_name
     * @param $course_id
     * @return CourseOffering
     */
    private function courseOffering($course_name, $course_id)
    {
        $courseOffering = new CourseOffering(self::BLANK_NODE . 'problemroulette/courses/' . urlencode($course_id));
        $courseOffering->setName($course_name);
        return $courseOffering;
    }

}