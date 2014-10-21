<?php

require_once("./include_all_libs.php");

session_start();
$_SESSION['sesstest'] = 1;

global $usrmgr;

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
		//$problems_list = Array();
		$problem_id_list = Array();
		$all_topics_in_course = MTopic::get_all_topics_in_course($selected_course_id);//topic objects
		$num_topics = count($all_topics_in_course);
		
		for ($i=0; $i<$num_topics; $i++)
		{
			//$problems_list_in_topic = MProblem::get_all_problems_in_topic_with_exclusion($all_topics_in_course[$i]->m_id);
			$problem_id_list_in_topic = MProblem::get_all_problems_in_topic_with_exclusion($all_topics_in_course[$i]->m_id,Null,1);
			for ($j=0; $j<count($problem_id_list_in_topic); $j++)
			{
				//array_push($problems_list,$problems_list_in_topic[$j]);
				array_push($problem_id_list,$problem_id_list_in_topic[$j]);
			}
		}
			
		$num_problems = count($problem_id_list);
		
		//$problems_list_id = Array();
		//for ($i=0; $i<$num_problems; $i++)
		//{
		//	array_push($problems_list_id, $problems_list[$i]->m_prob_id);
		//}
		
		if ($num_problems > 0)
		{
			$summary = new MUserSummary($problem_id_list,$search_username);
		}
		else
		{
			$summary = new MUserSummary('blank',$search_username);
		}
	//</DISPLAY ALL PROBLEMS IN GIVEN COURSE>
	}
	else
	{
	//<DISPLAY ALL PROBLEMS IN SELECTED TOPIC>
		//$problems_list = MProblem::get_all_problems_in_topic_with_exclusion($selected_topic_id);
		$problem_id_list = MProblem::get_all_problems_in_topic_with_exclusion($selected_topic_id,Null,1);
		
		$num_problems = count($problem_id_list);
		
		//$problems_list_id = Array();
		//for ($i=0; $i<$num_problems; $i++)
		//{
		//	array_push($problems_list_id, $problems_list[$i]->m_prob_id);
		//}
		
		if ($num_problems > 0)
		{
			$summary = new MUserSummary($problem_id_list,$search_username);
		}
		else
		{
			$summary = new MUserSummary('blank',$search_username);
		}
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
