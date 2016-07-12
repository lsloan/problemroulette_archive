<?php

require_once("setup.php");

$search_username = Null;
$display_search = 0;
if (isset($_POST['input_search_username']))
{
	$search_username = $_POST['input_search_username'];
	if ($search_username !== Null && $search_username !== 0 && $search_username !== '')
	{
		$display_search = $search_username;
	}
	else
	{
		$display_search = 0;
	}
}

if (isset($_POST['hidden_search_username']))
{
	$search_username = $_POST['hidden_search_username'];
	if ($search_username !== Null && $search_username !== 0 && $search_username !== '')
	{
		$display_search = $search_username;
	}
	else
	{
		$display_search = 0;
	}
}

// populate and use models for business logic on page
if (isset($_POST['dropdown_course']))
{
	//get selected course from POST and set preference; then, refresh page
	$selected_course_id = $_POST['dropdown_course'];
	$_SESSION['dropdown_history_course'] = $selected_course_id;
	$usrmgr->m_user->SetPref('dropdown_history_course',$selected_course_id);
	$_SESSION['dropdown_history_topic'] = 'all';
	$usrmgr->m_user->SetPref('dropdown_history_topic','all');
	
	//header('Location:student_performance.php');
}

elseif (isset($_POST['dropdown_topic']))
{
	//get selected topic from POST and set preference; then, refresh page
	$selected_topic_id = $_POST['dropdown_topic'];
	$_SESSION['dropdown_history_topic'] = $selected_topic_id;
	$usrmgr->m_user->SetPref('dropdown_history_topic',$selected_topic_id);
	
	//header('Location:student_performance.php');
}

//get selected course
if (isset($_SESSION['sesstest']))
{
	if (isset($_SESSION['dropdown_history_course']))
	{
		$selected_course_id = $_SESSION['dropdown_history_course'];
	}
	else
	{
		$selected_course_id = Null;
	}
	if (isset($_SESSION['dropdown_history_topic']))
	{
		$selected_topic_id = $_SESSION['dropdown_history_topic'];
	}
	else
	{
		$selected_topic_id = Null;
	}
}
else
{
	if ($usrmgr->m_user->GetPref('dropdown_history_course') !== Null)
	{
		$selected_course_id = $usrmgr->m_user->GetPref('dropdown_history_course');
	}
	else
	{
		$selected_course_id = Null;
	}
	if ($usrmgr->m_user->GetPref('dropdown_history_topic') !== Null)
	{
		$selected_topic_id = $usrmgr->m_user->GetPref('dropdown_history_topic');
	}
	else
	{
		$selected_topic_id = Null;
	}
}
	
//get array of all problem IDs within course
if ($selected_course_id == 'all' || $selected_course_id == Null)
{
	$summary = new MUserSummary(Null,$search_username);
}
else
{
	if ($selected_topic_id == 'all')
	{
	//<DISPLAY ALL PROBLEMS IN GIVEN COURSE>
		$include_inactive_topics = $usrmgr->m_user->staff == 1;
		$problems = MProblem::get_problems_in_course($selected_course_id, $include_inactive_topics);
		$summary = new MUserSummary($problems, $search_username);
	//</DISPLAY ALL PROBLEMS IN GIVEN COURSE>
	}
	else
	{
	//<DISPLAY ALL PROBLEMS IN SELECTED TOPIC>
		$problems_list = MProblem::get_all_problems_in_topic_with_exclusion($selected_topic_id);
		$summary = new MUserSummary($problem_id_list, $search_username);
	//</DISPLAY ALL PROBLEMS IN SELECTED TOPIC>
	}
}

// page construction
$head = new CHeadCSSJavascript("Student Performance", array(), array());
$tab_nav = new VTabNav(new MTabNav('Student Performance'));
$content = new VStudentPerformance($summary,$display_search);
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
