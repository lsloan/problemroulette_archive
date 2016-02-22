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
require_once 'Caliper/events/AssessmentEvent.php';
require_once 'Caliper/entities/lis/Group.php';
require_once 'Caliper/entities/lis/CourseOffering.php';
require_once 'Caliper/entities/assessment/Assessment.php';


class CaliperService extends BaseCaliperService
{
    var $config;
    var $STARTED = Action::STARTED;
    var $ENDED = Action::ENDED;

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

    public function sendNavigationEvent() {
        //getting the couse_id from MUser object
        $courseId = $this->getCourseId();
        $navigationEvent=new NavigationEvent();
        $navigationEvent->setActor($this->getPerson())
            ->setObject($this->getWebPage($this->getCourseName($courseId), $courseId))
            ->setNavigatedFrom($this->getWebPage())
            ->setEventTime(new DateTime())
            ->setEdApp(new SoftwareApplication($this->getUrl()))
            ->setGroup($this->getCourseOffering());

        $this->sendEvent($navigationEvent);

    }


    public function sendAssessmentEvent($action, $selectedTopicList) {
        $selected_topics = urlencode (implode(",", $selectedTopicList));

        $courseId = $this->getCourseId();
        $assessment = new Assessment(self::BLANK_NODE . "problemroulette/courses/" . urlencode($courseId) . "/topics?id=" . $selected_topics);
        $assessment->setName("Selections: Topics View for " . $this->getCourseName($courseId));

        $assessmentEvent = new AssessmentEvent();
        $assessmentEvent->setActor($this->getPerson())
            ->setEventTime(new DateTime())
            ->setAction(new Action($action))
            ->setEdApp(new SoftwareApplication($this->getUrl()))
            ->setGroup($this->getCourseOffering())
            ->setObject($assessment);

        $this->sendEvent($assessmentEvent);
    }
    /*
     * sending the caliper event to the eventstore
     */
    private function sendEvent($event) {
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
    private function getUrl() {
        return $GLOBALS["DOMAIN"];
    }

    /**
     * @param $usrmgr
     * @return Person
     */
    private function getPerson() {
        global $usrmgr;
        $userName = urlencode(strval($usrmgr->m_user->username));
        $person = new Person('https://mcommunity.umich.edu/#profile:' . $userName);
        $person->setName($userName);
        return $person;
    }

    /**
     * @param $courseName
     * @param $courseId
     * @return WebPage
     */
    private function getWebPage($courseName=null, $courseId=null) {
        $webPage=null;
        if(($courseName && $courseId)) {
            $webPage = new WebPage(self::BLANK_NODE . 'problemroulette/views/selections/courses/' .urlencode($courseId). '/topics');
            $webPage->setName("Selections: $courseName Topics");
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
    private function getCourseOffering() {
        $courseId = $this->getCourseId();
        $courseOffering = new CourseOffering(self::BLANK_NODE . 'problemroulette/courses/' . urlencode($courseId));
        $courseOffering->setName($this->getCourseName($courseId));
        return $courseOffering;
    }

    /*
     *Get the selected course_id for staring the Quiz engine on a particular course topics. This information is from Database
     */
    private function getCourseId() {
        global $usrmgr;
        $course_id = $usrmgr->m_user->selected_course_id;
        return $course_id;
    }

    private function getCourseName($courseId) {
        $course=  MCourse::get_course_by_id($courseId);
        return $course->m_name;
    }

}