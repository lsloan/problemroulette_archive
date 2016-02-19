<?php

/*
 * Using the Dependency injection pattern we enable/disable the Caliper feature to Problem Roulette. This class acts as abstract implementation of the
 * pattern if $GLOBALS["CALIPER_ENABLED"] = false then generating a caliper event method call will do nothing.
 */
class BaseCaliperService {

    var $STARTED;
    var $ENDED;

    public function __construct($config) {
    }

    public function sendNavigationEvent($course_name, $course_id){

    }

    public function sendAssessmentEvent($action,$selected_topic_list){

    }

}
