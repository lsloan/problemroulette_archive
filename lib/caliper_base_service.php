<?php

/*
 * Using the Dependency injection pattern we enable/disable the Caliper feature to Problem Roulette. This class acts as abstract implementation of the
 * pattern if $GLOBALS["CALIPER_ENABLED"] = false then generating a caliper event method call will do nothing.
 */
class BaseCaliperService {

    var $config;

    public function __construct(CaliperConfig $config) {
        $this->config =$config;
    }

    public function captureNavigationEventFromCourseToTopicView($course_name, $course_id)
    {

    }

}
