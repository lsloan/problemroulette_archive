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
require_once 'Caliper/entities/assessment/AssessmentItem.php';
require_once 'Caliper/entities/assignable/Attempt.php';
require_once 'Caliper/entities/response/MultipleChoiceResponse.php';
require_once 'Caliper/events/AssessmentItemEvent.php';
require_once 'Caliper/events/SessionEvent.php';
require_once 'Caliper/entities/session/Session.php';
require_once 'Caliper/events/AnnotationEvent.php';


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

    public function navigateToSelections() {
        $navigationEvent = (new NavigationEvent())
                ->setActor($this->getPerson())
                ->setEventTime(new DateTime())
                ->setEdApp(new SoftwareApplication($this->getUrl()));
        if (!is_null(getCourseId())) {
            $navigationEvent->setGroup(($this->getCourseOffering()))
                    ->setObject($this->getWebPageWithACourse());
        } else {
            $navigationEvent->setObject($this->getWebPageWithCourses());
        }
        if (isInTopicsView()) {
            $navigationEvent->setNavigatedFrom($this->getWebPageWithCourses());
        }

        $this->sendEvent($navigationEvent);

    }


    public function assessmentStart($selectedTopicList) {
        $this->sendAssessmentEvent(Action::STARTED, $selectedTopicList);
    }

    public function assessmentSubmit() {
        $this->sendAssessmentEvent(Action::SUBMITTED, getSelectedTopicList());
    }


    private function sendAssessmentEvent($action, $selectedTopicList) {
        $selected_topics = urlencode (implode(",", $selectedTopicList));

        $courseId = getCourseId();
        $assessment = $this->getAssessment($this->getUrl() . "courses/" . urlencode($courseId) . "/topics?id=" . $selected_topics);
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

    public function assessmentItemStart(MProblem $problem, $topicId) {

        $this->sendAssessmentItemEvent(Action::STARTED, new DateTime(), $problem, $topicId);
    }

    public function assessmentItemComplete(MResponse $response, MProblem $problem) {

        $attempt = $this->getAttempt($problem, $response->m_start_time, $response->m_end_time);

        $isStudentAnswerCorrect = ($response->m_student_answer_correct) ? "true" : "false";
        $extensions = array("isStudentAnswerCorrect"=>$isStudentAnswerCorrect);
        if($problem->get_ok_to_show_soln(getUserId())){
           $extensions+=array("correctAnswer" => strval($problem->m_prob_correct));
        }

        $mcResponse = new MultipleChoiceResponse($problem->m_prob_url . "/response");
        $mcResponse->setAttempt($attempt)
            ->setExtensions($extensions)
            ->setValue($response->m_student_answer);

        $this->sendAssessmentItemEvent(Action::COMPLETED, $attempt->getEndedAtTime(), $problem, $response->m_topic_id, $mcResponse);
    }

    public function assessmentItemSkip(MResponse $response, MProblem $problem) {

        $attempt = $this->getAttempt($problem, $response->m_start_time, $response->m_end_time);

        $mcResponse = new MultipleChoiceResponse($problem->m_prob_url . "/response");
        $mcResponse->setAttempt($attempt);

        $this->sendAssessmentItemEvent(Action::SKIPPED, $attempt->getEndedAtTime(), $problem, $response->m_topic_id, $mcResponse);

    }

    private function sendAssessmentItemEvent($action, $eventTime, $problem, $topicId, $response=null) {
        $assessmentItemEvent = $this->getAssessmentItemEvent();
        $assessmentItemEvent->setEventTime($eventTime)
            ->setAction(new Action($action))
            ->setEdApp(new SoftwareApplication($this->getUrl()))
            ->setGroup($this->getCourseOffering())
            ->setActor($this->getPerson())
            ->setObject($this->getAssessmentItem($problem, $topicId));
        if (!is_null($response)) {
            $assessmentItemEvent->setGenerated($response);
                }

        $this->sendEvent($assessmentItemEvent);
    }

    public function rateProblem($problemId, $rating) {
        $problem = getProblem($problemId);
        $response = new MultipleChoiceResponse($problem->m_prob_url . "/response");
        $response->setValue($rating);
        $annotationEvent = new AnnotationEvent();
        $annotationEvent->setEventTime(new DateTime())
                ->setActor($this->getPerson())
                ->setAction(new Action(Action::RANKED))
                ->setEdApp(new SoftwareApplication($this->getUrl()))
                ->setGroup($this->getCourseOffering())
                ->setGenerated($response)
                ->setObject($this->getAssessmentItem($problem));

        $this->sendEvent($annotationEvent);

    }

    public function sessionStart() {
        $this->sendSessionEvent(Action::LOGGED_IN, $_SESSION['START_TIME']);
    }

    public function sessionTimeout() {
        $startedDateTime = $this->timeConvert($_SESSION['START_TIME']);
        $endedDateTime = new DateTime();
        $duration = strval($endedDateTime->getTimestamp() - $startedDateTime->getTimestamp());
        $this->sendSessionEvent(Action::TIMED_OUT, $_SESSION['START_TIME'], $endedDateTime, $duration);
    }

    private function sendSessionEvent($action, $startTime, $endTime = null, $duration = null) {

        $startedDateTime = $this->timeConvert($_SESSION['START_TIME']);

        $session = new Session($this->getUrl() . "session/" . urlencode($startTime));
        $session->setName("session - " . $startTime)
            ->setStartedAtTime($startedDateTime);
        if ($action === Action::TIMED_OUT) {
            $session->setEndedAtTime($endTime)
                ->setActor($this->getPerson())
                ->setDuration($duration);
        }

        $sessionEvent = new SessionEvent();
        $sessionEvent->setEdApp(new SoftwareApplication($this->getUrl()))
            ->setAction(new Action($action));
        if (!is_null(getCourseId())) {
            $sessionEvent->setGroup($this->getCourseOffering());
        }
        if ($action === Action::LOGGED_IN) {
            $sessionEvent->setEventTime($session->getStartedAtTime());
            $sessionEvent->setGenerated($session);
            $sessionEvent->setObject(new SoftwareApplication($this->getUrl()));
            $sessionEvent->setActor($this->getPerson());
        }
        if ($action === Action::TIMED_OUT) {
            $sessionEvent->setEventTime($session->getEndedAtTime());
            $sessionEvent->setObject($session);
            $sessionEvent->setActor(new SoftwareApplication($this->getUrl()));
        }

        $this->sendEvent($sessionEvent);
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
        $caliperProxyUrl = $this->config->getCaliperProxyUrl();
        $caliperProxyEnabled = $this->config->getCaliperProxyEnabled();

        // caliper variable should not be empty or null
        if((empty($sensorId) || empty($endpointUrl) || empty($apiKey) || empty($caliperHttpId) || empty($caliperClientId))){
            $app_log->msg("Some caliper configurations are missing, unable to send Caliper Event. " .
                "sensorId = '$sensorId'; endpointUrl = '$endpointUrl'; apiKey = '$apiKey'
                ; caliperHttpId = '$caliperHttpId'; caliperClientId = '$caliperClientId'");
            return;
        }
        $sensor = new Sensor($sensorId);
        $options = (new Options())
            ->setApiKey($apiKey)
            ->setDebug($this->config->isDebug());

        if ( $caliperProxyEnabled === true ) {
            if(empty($caliperProxyUrl)){
                $app_log->msg("Caliper proxy URL is missing.");
                return;
            }
            $options->setHost($caliperProxyUrl);
        } else {
            $options->setHost($endpointUrl);
        }

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
        $userName = urlencode(getUserName());
        $person = new Person('https://mcommunity.umich.edu/#profile:' . $userName);
        $person->setName($userName);
        return $person;
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

    private function getAssessment($id) {
        return new Assessment($id);
    }

    private function getAssessmentItemEvent() {
        return new AssessmentItemEvent();
    }

    private function getIsPartOf($topicId) {
        $isPartOf = $this->getAssessment($this->getUrl() . "courses/" . urlencode(getCourseId()) . "/topics/" . urlencode($topicId));
        $isPartOf->setName(getTopicName($topicId));
        return $isPartOf;
    }

    private function getAssessmentItem(MProblem $problem, $topicId=null) {
        $assessmentItem = new AssessmentItem($problem->m_prob_url);
        $assessmentItem->setName($problem->m_prob_name);
        if (!is_null($topicId)) {
            $assessmentItem->setIsPartOf($this->getIsPartOf($topicId));
        }
        return $assessmentItem;
    }

    private function getAttempt(MProblem $problem, $startTime, $endTime) {
        $startDataTime = $this->timeConvert($startTime);
        $endDataTime = $this->timeConvert($endTime);
        $durationSeconds = strval($endDataTime->getTimestamp() - $startDataTime->getTimestamp());
        $attempt = (new Attempt($problem->m_prob_url ."/attempt"))
            ->setCount(getAttemptCount($problem->m_prob_id))
            ->setStartedAtTime($startDataTime)
            ->setEndedAtTime($endDataTime)
            ->setDuration($durationSeconds);
        return $attempt;
    }

    private function timeConvert($time){
         return date_create((is_numeric($time) ? '@' : null) . strval($time));
    }

    private function getWebPageWithACourse() {
        $webPage = new WebPage($this->getUrl() . 'views/selections/courses/' . urlencode(getCourseId()) . '/topics');
        $webPage->setName("Selections: " . getCourseName(getCourseId()) . " Topics");
        return $webPage;
    }

    private function getWebPageWithCourses() {
        $webPage = new WebPage($this->getUrl() . 'views/selections/courses');
        $webPage->setName('Selections: Course List');
        return $webPage;
    }

}
