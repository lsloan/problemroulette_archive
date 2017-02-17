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
require_once realpath(__DIR__ . '/../ViadutooJob.php');

class CaliperService extends BaseCaliperService {
    var $actionReset;
    var $config;

    public function __construct($config) {
        parent::__construct($config);
        if (!($config instanceof CaliperConfig)) {
            global $app_log;
            $app_log->msg("In the class \"" . __CLASS__ . "\" constructor, object expected was \"CaliperConfig\" but got: " . get_class($config));
            throw new InvalidArgumentException('Object expected was "CaliperConfig" but got ' . get_class($config));
        }
        $this->config = $config;

        // Caliper 1.0 doesn't define the reset action.  It will be added in release 1.1.
        // If it's not defined, do it locally.
        $this->actionReset = defined('Action::RESET') ? Action::RESET : 'http://purl.imsglobal.org/vocab/caliper/v1/action#Reset';
    }

    public function navigateToSelections() {
        try {
            $navigationEvent = new NavigationEvent();
            if (is_null(getCourseId())) {
                $navigationEvent->setObject($this->getWebPageWithCourses());
            } else {
                $navigationEvent->setObject($this->getWebPageWithACourse());
            }
            if (isInTopicsView()) {
                $navigationEvent->setNavigatedFrom($this->getWebPageWithCourses());
            }
            $this->sendEvent($navigationEvent);
        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__);
        }
    }

    public function assessmentStart($selectedTopicList) {
        try {
            $this->sendAssessmentEvent(Action::STARTED, $selectedTopicList);

        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__);
        }
    }

    public function assessmentSubmit() {
        try {
            $this->sendAssessmentEvent(Action::SUBMITTED, getSelectedTopicList());
        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__);
        }
    }

    public function assessmentReset($selectedTopicList) {
        try {
            $this->sendAssessmentEvent($this->actionReset, $selectedTopicList);
        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__);
        }
    }

    private function sendAssessmentEvent($action, $selectedTopicList) {
        $selected_topics = urlencode(implode(",", $selectedTopicList));

        $courseId = getCourseId();
        $assessment = $this->getAssessment($this->getUrl() . "courses/" . urlencode($courseId) . "/topics?id=" . $selected_topics)
                ->setName("Selections: Topics View for " . getCourseName($courseId));

        $assessmentEvent = (new AssessmentEvent())
                ->setAction(new Action($action))
                ->setObject($assessment);

        $this->sendEvent($assessmentEvent);
    }

    public function assessmentItemStart(MProblem $problem, $topicId) {
        try {
            $this->sendAssessmentItemEvent(Action::STARTED, $problem, $topicId);
        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__, $problem->m_prob_id);
        }
    }

    public function assessmentItemComplete(MResponse $response, MProblem $problem) {
        try {
            $attempt = $this->getAttempt($problem, $response->m_start_time, $response->m_end_time);

            $isStudentAnswerCorrect = ($response->m_student_answer_correct) ? "true" : "false";
            $extensions = array("isStudentAnswerCorrect" => $isStudentAnswerCorrect);
            $extensions += array("correctAnswer" => strval($problem->m_prob_correct));

            $mcResponse = (new MultipleChoiceResponse($problem->m_prob_url . "/response"))
                    ->setAttempt($attempt)
                    ->setExtensions($extensions)
                    ->setValue($response->m_student_answer);

            $this->sendAssessmentItemEvent(Action::COMPLETED, $problem, $response->m_topic_id, $mcResponse);
        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__, $problem->m_prob_id);
        }
    }

    public function assessmentItemSkip(MResponse $response, MProblem $problem) {
        try {
            $attempt = $this->getAttempt($problem, $response->m_start_time, $response->m_end_time);
            $mcResponse = (new MultipleChoiceResponse($problem->m_prob_url . "/response"))
                    ->setAttempt($attempt);

            $this->sendAssessmentItemEvent(Action::SKIPPED, $problem, $response->m_topic_id, $mcResponse);
        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__, $problem->m_prob_id);
        }
    }

    private function sendAssessmentItemEvent($action, $problem, $topicId, $response = null) {
        $assessmentItemEvent = $this->getAssessmentItemEvent()
                ->setAction(new Action($action))
                ->setObject($this->getAssessmentItem($problem, $topicId));
        if (!is_null($response)) {
            $assessmentItemEvent->setGenerated($response);
        }

        $this->sendEvent($assessmentItemEvent);
    }

    public function rateProblem($problemId, $rating) {
        try {
            $problem = getProblem($problemId);
            $response = (new MultipleChoiceResponse($problem->m_prob_url . "/response"))
                    ->setValue($rating);
            $annotationEvent = (new AnnotationEvent())
                    ->setAction(new Action(Action::RANKED))
                    ->setGenerated($response)
                    ->setObject($this->getAssessmentItem($problem));

            $this->sendEvent($annotationEvent);
        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__, $problemId);
        }
    }

    public function sessionStart() {
        try {
            $this->sendSessionEvent(Action::LOGGED_IN, $_SESSION['START_TIME']);

        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__);
        }
    }

    public function sessionTimeout() {
        try {
            $startedDateTime = $this->timeConvert($_SESSION['START_TIME']);
            $endedDateTime = new DateTime();
            $duration = strval($endedDateTime->getTimestamp() - $startedDateTime->getTimestamp());
            $this->sendSessionEvent(Action::TIMED_OUT, $_SESSION['START_TIME'], $endedDateTime, $duration);
        } catch (Exception $e) {
            $this->logEventException($e, __FUNCTION__);
        }
    }

    private function sendSessionEvent($action, $startTime, $endTime = null, $duration = null) {
        $startedDateTime = $this->timeConvert($_SESSION['START_TIME']);

        $session = (new Session($this->getUrl() . "session/" . urlencode($startTime)))
                ->setName("session - " . $startTime)
                ->setStartedAtTime($startedDateTime);
        if ($action === Action::TIMED_OUT) {
            $session->setEndedAtTime($endTime)
                    ->setDuration($duration);
        }

        $sessionEvent = (new SessionEvent())
                ->setAction(new Action($action));
        if ($action === Action::LOGGED_IN) {
            $sessionEvent->setGenerated($session);
            $sessionEvent->setObject(new SoftwareApplication($this->getUrl()));
        }
        if ($action === Action::TIMED_OUT) {
            $sessionEvent->setObject($session);
            $sessionEvent->setActor(new SoftwareApplication($this->getUrl()));
        }

        $this->sendEvent($sessionEvent);
    }

    /*
     * sending the caliper event to the eventstore
     */
    private function sendEvent(Event $event) {
        global $app_log;
        $sensorId = $this->config->getSensorId();
        $endpointUrl = $this->config->getHost();
        $apiKey = $this->config->getApiKey();
        $caliperProxyUrl = $this->config->getCaliperProxyUrl();
        $caliperProxyEnabled = $this->config->getCaliperProxyEnabled();

        // Caliper configuration values must not be empty or null
        if ((empty($sensorId) || empty($endpointUrl) || empty($apiKey))) {
            $app_log->msg('Unable to send Caliper event: Some Caliper configuration values are missing. ' .
                "sensorId = '$sensorId'; endpointUrl = '$endpointUrl'; apiKey = '$apiKey'");
            return;
        }

        if ($caliperProxyEnabled === true) {
            if (empty($caliperProxyUrl)) {
                $app_log->msg("Caliper proxy URL is missing.");
                return;
            }
            $this->config->setHost($caliperProxyUrl);
        } else {
            $this->config->setHost($endpointUrl);
        }

        $event
            ->setEventTime($this->getDateTimeWithMicroseconds())
            ->setEdApp(new SoftwareApplication($this->getUrl()));

        if (!is_null(getCourseId())) {
            $event->setGroup($this->getCourseOffering());
        }

        if (is_null($event->getActor())) {
            $event->setActor($this->getPerson());
        }

        $sensor = (new Sensor($sensorId));

        /** @global $VIADUTOO_REDIS_ENABLED bool */
        global $VIADUTOO_REDIS_ENABLED;
        /** @global $VIADUTOO_REDIS_HOST_PORT string */
        global $VIADUTOO_REDIS_HOST_PORT;
        /** @global $VIADUTOO_REDIS_QUEUE_NAME string */
        global $VIADUTOO_REDIS_QUEUE_NAME;

        if (isset($VIADUTOO_REDIS_ENABLED) && $VIADUTOO_REDIS_ENABLED === true) {
            // If optional config. value VIADUTOO_REDIS_HOST_PORT is set, use it
            if (isset($VIADUTOO_REDIS_HOST_PORT) && !empty($VIADUTOO_REDIS_HOST_PORT)) {
                Resque::setBackend($VIADUTOO_REDIS_HOST_PORT);
            }

            if (!isset($VIADUTOO_REDIS_QUEUE_NAME) || empty($VIADUTOO_REDIS_QUEUE_NAME)) {
                $app_log->msg("Viadutoo Redis queue name configuration value is not set.  Unable to send Caliper event.");
                exit;
            }

            Resque::enqueue(
                $VIADUTOO_REDIS_QUEUE_NAME,
                ViadutooJob::class,
                [
                    'config' => $this->config,
                    'event' => json_encode(
                        (new Envelope())
                            // With Sensor::send(), HttpRequestor creates Envelope
                            ->setSensorId($sensor)
                            ->setSendTime($this->getDateTimeWithMicroseconds())
                            ->setData($event),
                        $this->config->getJsonEncodeOptions()
                    ),
                ]);
        } else {
            $sensor
                ->registerClient('caliperHttpId', new Client('caliperClientId', $this->config))
                ->send($sensor, $event);
        }
    }

    /*
     * getting the application URL. eg., http://pr.local/
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
        $person = (new Person('https://mcommunity.umich.edu/#profile:' . $userName))
                ->setName($userName);
        return $person;
    }

    /**
     * @return CourseOffering
     */
    private function getCourseOffering() {
        $courseId = getCourseId();
        $courseOffering = (new CourseOffering($this->getUrl() . 'courses/' . urlencode($courseId)))
                ->setName(getCourseName($courseId));
        return $courseOffering;
    }

    private function getAssessment($id) {
        return new Assessment($id);
    }

    private function getAssessmentItemEvent() {
        return new AssessmentItemEvent();
    }

    private function getIsPartOf($topicId) {
        $isPartOf = $this->getAssessment($this->getUrl() . "courses/" . urlencode(getCourseId()) . "/topics/" . urlencode($topicId))
                ->setName(getTopicName($topicId));
        return $isPartOf;
    }

    private function getAssessmentItem(MProblem $problem, $topicId = null) {
        $assessmentItem = (new AssessmentItem($problem->m_prob_url))
                ->setName($problem->m_prob_name);
        if (!is_null($topicId)) {
            $assessmentItem->setIsPartOf($this->getIsPartOf($topicId));
        }
        return $assessmentItem;
    }

    private function getAttempt(MProblem $problem, $startTime, $endTime) {
        $startDataTime = $this->timeConvert($startTime);
        $endDataTime = $this->timeConvert($endTime);
        $durationSeconds = strval($endDataTime->getTimestamp() - $startDataTime->getTimestamp());
        $attempt = (new Attempt($problem->m_prob_url . "/attempt"))
            ->setCount(getAttemptCount($problem->m_prob_id))
            ->setStartedAtTime($startDataTime)
            ->setEndedAtTime($endDataTime)
            ->setDuration($durationSeconds);
        return $attempt;
    }

    private function timeConvert($time) {
        return date_create((is_numeric($time) ? '@' : null) . strval($time));
    }

    private function getWebPageWithACourse() {
        $webPage = (new WebPage($this->getUrl() . 'views/selections/courses/' . urlencode(getCourseId()) . '/topics'))
                ->setName("Selections: " . getCourseName(getCourseId()) . " Topics");
        return $webPage;
    }

    private function getWebPageWithCourses() {
        $webPage = (new WebPage($this->getUrl() . 'views/selections/courses'))
                ->setName('Selections: Course List');
        return $webPage;
    }

    private function getDateTimeWithMicroseconds() {
        return DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
    }

    private function logEventException($exception, $event, $problemId = null) {
        global $app_log;
        $app_log->msg('For User: "' . getUserName() . (!is_null($problemId) ? '" with problem id "' . $problemId : '')
                . '" failure in sending  "' . $event . '" Caliper event due to error: ' . PHP_EOL . $exception->getTraceAsString());
    }
}
