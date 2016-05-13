<?php

require_once("setup.php");

//get user_id
$user_id = $usrmgr->m_user->id;

// error_log("Selections");
// error_log(print_r($_POST, true));

$include_inactive_topics = ($usrmgr->m_user->staff == 1);

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
	//this session variable will be removed by end of the reset action.
	// Due to redirects it is difficult to distinguish between a navigation state and a reset topic state hence setting this variable
	$_SESSION['caliper_topic_link_submission']=true;
} elseif (isset($_POST['course_submission'])) {
	// user has chosen a course
	$selected_course_id = $_POST['course_submission'];
	$timestamp = time();
	$usrmgr->m_user->SetSelectedCourseId($selected_course_id);
	$usrmgr->m_user->SetLastActivity($timestamp);
	//caliper event
	$caliper->navigateToSelections();
} elseif (isset($_POST['select_different_course'])) {
	// user hit the 'Select Different Course' button
	$usrmgr->m_user->SetSelectedCourseId(Null);
	header('Location:selections.php');
}

# choose course selector or topic selector
$m_expiration_time = 5184000; //60 days in seconds
$m_selected_course;//get from user
$m_last_activity = 0;//get from user
$m_current_time;//current timestamp
$course_or_topic = 0;//bool--0 for course selector, 1 for topic selector
$m_selected_course = $usrmgr->m_user->selected_course_id;
$m_last_activity = $usrmgr->m_user->last_activity;
$m_current_time = time();
if (($m_current_time - $m_last_activity) <= $m_expiration_time && $m_selected_course != Null)
{
    $course_or_topic = 1;
}


// page construction
if (isset($_POST['course_submission'])) {
	$head = new CHeadCSSJavascript("Selections", array(), array());
	$tab_nav = new VTabNav(new MTabNav('Selections'));
}
else {
	$head = new CHeadCSSJavascript("Selections", array(), array());
	$tab_nav = new VTabNav(new MTabNav('Selections'));
	//we don't want to send a navigation event when we reset the topic hence the logic added.
	if (empty($_POST) && isset($_SESSION['caliper_topic_link_submission'])) {
		unset($_SESSION['caliper_topic_link_submission']);
	} else if (empty($_POST)) {
		//sending the nav event when user hit the 'Select Different Course' button and when user navigate to selections tab from any other Tab
		$caliper->navigateToSelections();
	}
 }

# choose topic or course selection view
if ($course_or_topic == 1)
{
	$content = new VTopic_Selections();
}
else
{
	$all_courses_with_topics = MCourse::get_all_courses_with_topics($include_inactive_topics);
	$content = new VCourse_Selections($all_courses_with_topics);
}


$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
