<?php

require_once("./include_all_libs.php");

session_start();

global $usrmgr;

//get user_id
$user_id = $usrmgr->m_user->id;

// error_log("Selections");
// error_log(print_r($_POST, true));

# set flag to indicate coming from any other tab
// $pre_fill_topics = 0;
// if (isset($_SERVER['HTTP_REFERER']))
// {
// 	$ref = $_SERVER['HTTP_REFERER'];
// 	if (strpos($ref,'problems.php') !== false || strpos($ref,'stats.php') !== false || strpos($ref,'staff.php') !== false)
// 	{
// 		$pre_fill_topics = 1;
// 	}
// }

$pre_fill_topics = 1;

if (isset($_POST['topic_checkbox_submission'])) {
	// user reset topics
	$reset_topics_list_id = $_POST['topic_checkbox_submission'];
	$length = count($reset_topics_list_id);
	for ($i=0; $i<$length; $i++)
	{
		$topic_id = $reset_topics_list_id[$i];
		$omitted_problem = new OmittedProblem($user_id, $topic_id);
		$current_omitted_problems_list = $omitted_problem->remove();
	}
} elseif (isset($_POST['topic_link_submission'])) {
	# direct topic link
	$topic_id = $_POST['topic_link_submission'];
	$omitted_problem = new OmittedProblem($user_id, $topic_id);
	$current_omitted_problems_list = $omitted_problem->remove();
} elseif (isset($_POST['course_submission'])) {
	// user has chosen a course
	$selected_course_id = $_POST['course_submission'];
	$timestamp = time();
	$usrmgr->m_user->SetSelectedCourseId($selected_course_id);
	$usrmgr->m_user->SetLastActivity($timestamp);
	// header('Location:selections.php');
} elseif (isset($_POST['select_different_course'])) {
	// user hit the 'Select Different Course' button
	$usrmgr->m_user->SetSelectedCourseId(Null);
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
if (isset($_POST['course_submission'])) {
	$head = new CHeadCSSJavascript("Selections", array(), array());
	$tab_nav = new VTabNavProbDisabled(new MTabNav('Selections'));
}
else {
	$head = new CHeadCSSJavascript("Selections", array(), array());
	$tab_nav = new VTabNav(new MTabNav('Selections'));
 }

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
