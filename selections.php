<?php
// pathsTESTGIT
require_once("./paths.inc.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
$GLOBALS["dbmgr"] = new CDbMgr();
// user manager
require_once( $DIR_LIB."usrmgr.php" );
$GLOBALS["usrmgr"] = new UserManager();
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
$args = GrabAllArgs();
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");

session_start();

global $usrmgr;
//checks to see if user reset topics
if (isset($_POST['topic_checkbox_submission']))
{
	$reset_topics_list_id = $_POST['topic_checkbox_submission'];
	$length = count($reset_topics_list_id);
	for ($i=0; $i<$length; $i++)
	{
		$topic_id = $reset_topics_list_id[$i];
		$usrmgr->m_user->SetPref('omitted_problems_list['.$topic_id.']',Null);
	}
}

///////////////////////////////////////
if (isset($_POST['topic_link_submission']))
{
	$topic_id = $_POST['topic_link_submission'];
	$usrmgr->m_user->SetPref('omitted_problems_list['.$topic_id.']',Null);	
}

//course_or_topic logic (0 for course selection, 1 for topic selection)
$course_or_topic = 0;
//whether or not to pre-fill in the topics (only if returning from another tab)
$pre_fill_topics = 0;

//checks to see if user is coming from different tab
//CURRENTLY ONLY CHECKS FOR PROBLEMS TAB
if (isset($_SERVER['HTTP_REFERER']))
{
	$ref = $_SERVER['HTTP_REFERER'];
	if (strpos($ref,'problems.php') !== false || strpos($ref,'stats.php') !== false || strpos($ref,'staff.php') !== false)
	{
		$course_or_topic = 1;
		$pre_fill_topics = 1;
	}
}

//checks to see if user has chosen a course; if so, updates preferences;
if (isset($_POST['course_submission']))
{
	$course_or_topic = 1;
	$selected_course_id = $_POST['course_submission'];
	//$selected_course = MCourse::get_course_by_id($selected_course_id);
	//echo $selected_course->m_topics[0]->m_name;
	//echo count($selected_course->m_topics);
	
	$timestamp = time();
	$usrmgr->m_user->SetPref('selected_course',$selected_course_id);
	$usrmgr->m_user->SetPref('last_activity',$timestamp);
	header('Location:selections.php');
}

//checks to see if user hit the 'Select Different Course' button
if (isset($_POST['select_different_course']))
{
	$usrmgr->m_user->SetPref('selected_course',Null);
	$course_or_topic = 0;
	header('Location:selections.php');
}

//use Director object to determine whether to display course selector or topic selector
$Director = new MDirector();
$course_or_topic = $Director->m_course_or_topic;


// populate and use models for business logic on page





// page construction
$head = new CHeadCSSJavascript("Selections", array(), array());
$tab_nav = new VTabNav(new MTabNav('Selections'));

//get course and topic selection options and time of last activity
$CTprefs = new MCTSelect();

//set selected course if it exists
if ($CTprefs->m_selected_course != Null)
{
	$selected_course_id = $CTprefs->m_selected_course;
	$selected_course = MCourse::get_course_by_id($selected_course_id);
}

//set selected topics list if it exists
if ($CTprefs->m_selected_topics_list != Null)
{
	$selected_topics_list = $CTprefs->m_selected_topics_list;
}

if ($course_or_topic == 1)
{
	$content = new VTopic_Selections($CTprefs,$pre_fill_topics);
}
else
{
	$all_courses_with_topics = MCourse::get_all_courses_with_topics();
	$content = new VCourse_Selections($all_courses_with_topics);
}

$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
