<?php
// paths
require_once("./paths.inc.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
$GLOBALS["dbmgr"] = new CDbMgr( "localhost", "pr_user", "pr_user", "prexpansion" );
// user manager
require_once( $DIR_LIB."usrmgr.php" );
$GLOBALS["usrmgr"] = new UserManager();
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
$args = GrabAllArgs();
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");


//course_or_topic logic (0 for course selection, 1 for topic selection)
//checks to see if user has chosen a course; if so, updates preferences;
$course_or_topic = 0;
if (isset($_POST['course_submission']))
{
	$course_or_topic = 1;
	$selected_course_id = $_POST['course_submission'];
	$selected_course = MCourse::get_course_by_id($selected_course_id);
	//echo $selected_course->m_topics[0]->m_name;
	//echo count($selected_course->m_topics);
	
	global $usrmgr;
	$timestamp = time();
	$usrmgr->m_user->SetPref('selected_course',$selected_course_id);
	$usrmgr->m_user->SetPref('last_activity',$timestamp);
}



// populate and use models for business logic on page





// page construction
$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Selections'));

$all_courses_with_topics = MCourse::get_all_courses_with_topics();
if ($course_or_topic == 1)
{
	$content = new VTopic_Selections($selected_course);
}
else
{
	$content = new VCourse_Selections($all_courses_with_topics);
}

$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
