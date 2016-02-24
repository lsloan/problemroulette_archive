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

    const TO = "To";

    const FROM = "From";

    public function __construct($config) {
        parent::__construct($config);
        if(!($config instanceof CaliperConfig)){
            global $app_log;
            $app_log->msg("In the class \"".__CLASS__."\" constructor, object expected was \"CaliperConfig\" but got: ".get_class($config));
            throw new InvalidArgumentException('Object expected was "CaliperConfig" but got '.get_class($config) );


        }
        $this->config=$config;
    }

    public function sendNavigationEvent() {
        $navigationEvent=new NavigationEvent();
        $navigationEvent->setActor($this->getPerson())
            ->setObject($this->getWebPage(self::TO))
            ->setNavigatedFrom($this->getWebPage(self::FROM))
            ->setEventTime(new DateTime())
            ->setEdApp(new SoftwareApplication($this->getUrl()))
            ->setGroup($this->getCourseOffering());

        $this->sendEvent($navigationEvent);

    }


    public function assessmentStart($selectedTopicList) {
        $this->sendAssessmentEvent(Action::STARTED, $selectedTopicList);
    }

    private function sendAssessmentEvent($action, $selectedTopicList) {
        $selected_topics = urlencode (implode(",", $selectedTopicList));

        $courseId = getCourseId();
        $assessment = new Assessment($this->getUrl() . "courses/" . urlencode($courseId) . "/topics?id=" . $selected_topics);
        $assessment->setName("Selections: Topics View for " . getCourseName($courseId));

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

    /* getting the application URL. eg., http://pr.local/
     * @return string
     */
    private function getUrl() {
        return $GLOBALS["DOMAIN"];
    }

    /**
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
     * $nav Navigation state
     * @return WebPage
     */
    private function getWebPage($nav) {
        $webPage=null;
        if(isInTopicsView()){
            if($nav == self::TO) {
                $webPage = new WebPage($this->getUrl() . 'views/selections/courses/' . urlencode(getCourseId()) . '/topics');
                $webPage->setName("Selections: ".getCourseName(getCourseId())." Topics");
            }
            if($nav == self::FROM) {
                $webPage=new WebPage($this->getUrl() . 'views/selections/courses');
                $webPage->setName('Selections: Course List');

            }
        }
        return $webPage;
    }

    /**
     * @return CourseOffering
     */
    private function getCourseOffering() {
        $courseId = getCourseId();
        $courseOffering = new CourseOffering($this->getUrl() . 'courses/' . urlencode($courseId));
        $courseOffering->setName(getCourseName($courseId));
        return $courseOffering;
    }

}