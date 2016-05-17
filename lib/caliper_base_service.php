<?php

/*
 * Using the Dependency injection pattern we enable/disable the Caliper feature to Problem Roulette. This class acts as abstract implementation of the
 * pattern if $GLOBALS["CALIPER_ENABLED"] = false then generating a caliper event method call will do nothing.
 */
class BaseCaliperService {


    public function __construct($config) {
    }

    public function navigateToSelections() {

    }

    public function assessmentStart($selectedTopicList) {

    }

    public function assessmentSubmit() {

    }

    public function assessmentReset($selectedTopicList) {

    }

    public function assessmentItemStart(MProblem $problem, $topicId) {

    }

    public function assessmentItemComplete(MResponse $response, MProblem $problem) {

    }

    public function assessmentItemSkip(MResponse $response, MProblem $problem) {

    }

    public function sessionStart() {

    }

    public function sessionTimeout() {

    }

    public function rateProblem($problemId, $rating) {

    }

}
