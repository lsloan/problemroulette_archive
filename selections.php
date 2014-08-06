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

//Set selected_course or selected_topics_list to Null if it is currently a string (instead of a number)
if (intval($usrmgr->m_user->GetPref('selected_course') == 0))
{
	$usrmgr->m_user->SetPref('selected_course',Null);
}
if (is_array($usrmgr->m_user->GetPref('selected_topics_list')))
{
	if (min(array_map("intval",$usrmgr->m_user->GetPref('selected_topics_list'))) == 0)
	{
		$usrmgr->m_user->SetPref('selected_topics_list',Null);
	}
}
else
{
	if (intval($usrmgr->m_user->GetPref('selected_course') == 0))
	{
		$usrmgr->m_user->SetPref('selected_course',Null);
	}
}

global $usrmgr;

//get user_id
$user_id = $usrmgr->m_user->id;

//checks to see if user reset topics
if (isset($_POST['topic_checkbox_submission']))
{
	$reset_topics_list_id = $_POST['topic_checkbox_submission'];
	$length = count($reset_topics_list_id);
	for ($i=0; $i<$length; $i++)
	{
		$topic_id = $reset_topics_list_id[$i];
		$omitted_problem = new OmittedProblem($user_id, $topic_id);
		$current_omitted_problems_list = $omitted_problem->remove();
	}
}

# direct topic link
if (isset($_POST['topic_link_submission']))
{
	$topic_id = $_POST['topic_link_submission'];
	$omitted_problem = new OmittedProblem($user_id, $topic_id);
	$current_omitted_problems_list = $omitted_problem->remove();
}

# set flag to indicate coming from any other tab
$pre_fill_topics = 0;
if (isset($_SERVER['HTTP_REFERER']))
{
	$ref = $_SERVER['HTTP_REFERER'];
	if (strpos($ref,'problems.php') !== false || strpos($ref,'stats.php') !== false || strpos($ref,'staff.php') !== false)
	{
		$pre_fill_topics = 1;
	}
}

//checks to see if user has chosen a course; if so, updates preferences;
if (isset($_POST['course_submission']))
{
	$selected_course_id = $_POST['course_submission'];	
	$timestamp = time();
	$usrmgr->m_user->SetPref('selected_course',$selected_course_id);
	$usrmgr->m_user->SetPref('last_activity',$timestamp);
	header('Location:selections.php');
}

//checks to see if user hit the 'Select Different Course' button
if (isset($_POST['select_different_course']))
{
	$usrmgr->m_user->SetPref('selected_course',Null);
	header('Location:selections.php');
}

# choose course selector or topic selector
$m_expiration_time = 5184000; //60 days in seconds
$m_selected_course;//get from MCTSelect
$m_last_activity = 0;//get from MCTSelect
$m_current_time;//current timestamp
$course_or_topic = 0;//bool--0 for course selector, 1 for topic selector
$CTprefs = new MCTSelect();
$m_selected_course = $CTprefs->m_selected_course;
$m_last_activity = $CTprefs->m_last_activity;
$m_current_time = time();
if (($m_current_time - $m_last_activity) <= $m_expiration_time && $m_selected_course != Null)
{
    $course_or_topic = 1;
}
//set selected course if it exists
if ($CTprefs->m_selected_course != Null)
{
    //this is dead logic?
	$selected_course_id = $CTprefs->m_selected_course;
	$selected_course = MCourse::get_course_by_id($selected_course_id);
}
//set selected topics list if it exists
if ($CTprefs->m_selected_topics_list != Null)
{
    //this is dead logic?
	$selected_topics_list = $CTprefs->m_selected_topics_list;
}


// page construction
$head = new CHeadCSSJavascript("Selections", array(), array());
$tab_nav = new VTabNav(new MTabNav('Selections'));

# choose topic or course selection view
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
