<?php
// paths
require_once("./paths.inc.php");
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");
require_once( $DIR_LIB."usrmgr.php" );
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
//$GLOBALS["dbmgr"] = new CDbMgr( "host", "user", "password", "database" );
$GLOBALS["dbmgr"] = new CDbMgr( "localhost", "pr_user", "pr_user", "prexpansion" );
// session
require_once( $DIR_LIB."sessions.php" );
//$GLOBALS["sessionmgr"] = new CSessMgr( "session_table", 3600);
// url arguments
$args = GrabAllArgs();

// permission
$GLOBALS["usrmgr"] = new UserManager();

// business logic
$model = new MProblem(1);

#TEST: add problem
#$model->create('hi', 'url-somewhere', 1, 5, 2);

#$course = new MCourse();

#TEST: see all courses
#MCourse::get_all_courses();

#TEST: get all topics in course ($course_id)
#MTopic::get_all_topics_in_course(3);

#TEST: get all problems in topic ($topic_id)
#MProblem::get_all_problems_in_topic(1);

#TEST: add course
#$course->create('Chemistry 456');

// page construction
$head = new CHeadCSSJavascript("Problem Roulette",
    array(
        #$GLOBALS["DOMAIN_CSS"]."test.css",
    ),

    array(
        #$GLOBALS["DOMAIN_JS"]."test.js.php",
    )
);

$body = new VProblemEditReview($model);
$page = new CPageBasic($head, $body);
//$page = new CPageBasic($head, $nav, $body);

# delivery the html
echo $page->Deliver();

?>
