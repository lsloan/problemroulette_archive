<?php
// paths
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

# reset preferences if not logged in for a while
// if (!isset($_SESSION['current_problem']))
// {
// 	$usrmgr->m_user->SetPref('current_problem',Null);
// }
if (!isset($_SESSION['problem_submitted']))
{
	$usrmgr->m_user->SetPref('problem_submitted',Null);
}
$_SESSION['sesstest'] = 1;


$selected_topics_list_id = Null;
//check to see if new topic was selected
//get from checkboxes if available and put into preferences
if (isset($_POST['topic_checkbox_submission']))
{
	$selected_topics_list_id = $_POST['topic_checkbox_submission'];
//
//	if (min(implode(",",array_map("intval",$selected_topics_list_id))) !== 0)//make sure not to write a string
//  prevent error in logs re only one arg to min must be an array - implode was making it a string
	if ( min(array_map("intval",$selected_topics_list_id)) !== 0 )
	{
		$usrmgr->m_user->SetPref('selected_topics_list',$selected_topics_list_id);
	}
	$_SESSION['current_problem'] = Null;
	$usrmgr->m_user->SetPref('current_problem',Null);
	$_SESSION['problem_submitted'] = Null;
	$usrmgr->m_user->SetPref('problem_submitted',Null);
	header('Location:problems.php');
}

//check to see if new topic was selected
//get from link if available and put into preferences
if (isset($_POST['topic_link_submission']))
{
	$selected_topics_list_id = $_POST['topic_link_submission'];
	if (intval($selected_topics_list !== 0))//make sure not to write a string
	{
		$usrmgr->m_user->SetPref('selected_topics_list',$selected_topics_list_id);
	}
	$_SESSION['current_problem'] = Null;
	$usrmgr->m_user->SetPref('current_problem',Null);
	$_SESSION['problem_submitted'] = Null;
	$usrmgr->m_user->SetPref('problem_submitted',Null);
	header('Location:problems.php');
}

//check to see if user hit "skip" button
if (isset($_POST['skip']))
{
	//get end time and compare to start time to get total time
	$end_time = time();
	$usrmgr->m_user->SetPref('end_time',$end_time);
	if (isset($_SESSION['start_time']))
	{
		$start_time = $_SESSION['start_time'];
	}
	else
	{
		$start_time = $usrmgr->m_user->GetPref('start_time');
	}
	
	//get current problem
	$current_problem_id = $usrmgr->m_user->GetPref('current_problem');
	$current_problem = new MProblem($current_problem_id);
	
	//get user_id
	$usrmgr->m_user->get_id();
	$user_id = $usrmgr->m_user->id;

	//get current topic_id and omitted problems list for given topic
	$current_topic_id = intval($usrmgr->m_user->GetPref('current_topic'));
	$omitted_problem = new OmittedProblem($user_id, $current_topic_id);
	$current_omitted_problems_list = $omitted_problem->find();
			
	//update tables upon response
	$response = new MResponse($start_time,$end_time,$user_id,$current_problem_id,Null);
	
	$response->update_skips();
	
	$_SESSION['current_problem'] = Null;
	$usrmgr->m_user->SetPref('current_problem',Null);
	$_SESSION['problem_submitted'] = Null;
	$usrmgr->m_user->SetPref('problem_submitted',Null);
	header('Location:problems.php');
}

//check to see if user submitted an answer
//if so, {set pref 'problem_submitted' to something other than null to display submitted problem view
//if they get the problem right, exclude problem in future}
if (isset($_POST['submit_answer']))
{
	if (isset($_POST['student_answer']))
	{
		//increment page_loads
		global $usrmgr;
		
		$ploads = $usrmgr->m_user->GetPref('page_loads');
		if (is_null($ploads))
			$ploads = 1;
		else
			$ploads += 1;
		$usrmgr->m_user->SetPref('page_loads', $ploads);
		
		//get end time and compare to start time to get total time
		$end_time = time();
		$usrmgr->m_user->SetPref('end_time',$end_time);
		if (isset($_SESSION['start_time']))
		{
			$start_time = $_SESSION['start_time'];
		}
		else
		{
			$start_time = $usrmgr->m_user->GetPref('start_time');
		}
		//get student answer
		$student_answer = $_POST['student_answer'];
		$_SESSION['problem_submitted'] = $student_answer;
		$usrmgr->m_user->SetPref('problem_submitted',$student_answer);
		
		//get current problem and correct answer
		$current_problem_id = $usrmgr->m_user->GetPref('current_problem');
		$current_problem = new MProblem($current_problem_id);
		$current_problem_answer = $current_problem->m_prob_correct;
		
		//get current topic_id and omitted problems list for given topic
		$current_topic_id = intval($usrmgr->m_user->GetPref('current_topic'));
		
		//get user_id
		$usrmgr->m_user->get_id();
		$user_id = $usrmgr->m_user->id;

		//if the student answered correctly, add current problem to omitted problems list for given topic
		if ($current_problem_answer == $student_answer)
		{
			$omitted_problem = new OmittedProblem($user_id, $current_topic_id, $current_problem_id);
			if ($omitted_problem->count() < 1) {
				$omitted_problem->add();
			}
		}
		
		//update tables upon response
		$response = new MResponse($start_time,$end_time,$user_id,$current_problem_id,$student_answer);
		
		$response->update_responses();
		$response->update_stats();
		$response->update_problems();
		$response->update_12m_prob_ans();
		
		header('Location:problems.php');
	}
}

# handle next event
if (isset($_POST['next']))
{
	$_SESSION['current_problem'] = Null;
	$usrmgr->m_user->SetPref('current_problem',Null);
	$_SESSION['problem_submitted'] = Null;
	$usrmgr->m_user->SetPref('problem_submitted',Null);
	header('Location:problems.php');
}

# translate ids to list of topic objects
$selected_topics_list_id = $usrmgr->m_user->GetPref('selected_topics_list');
$num_topics = count($selected_topics_list_id);
// $selected_topics_list_id might just be a single topic as a string
if (! is_array($selected_topics_list_id))
{
	$selected_topics_list_id = MakeArray($selected_topics_list_id);
}

for ($i=0; $i<$num_topics; $i++)
{
	$selected_topics_list[$i] = MTopic::get_topic_by_id($selected_topics_list_id[$i]);
}

# ask picker for problem using constraints
$Picker = new MPpicker();
$Picker->pick_problem();
$remaining_problems_in_topic_list = $Picker->m_remaining_problems_in_topic_list;
$total_problems_in_topic_list = $Picker->m_total_problems_in_topic_list;
//pick either current problem a student is working on OR pick new problem
if ($usrmgr->m_user->GetPref('current_problem') !== Null)
{
	//use current problem
	$picked_problem_id = $usrmgr->m_user->GetPref('current_problem');
	$picked_problem = new MProblem($picked_problem_id);
}
else
{
	//set current topic and pick random problem
	$usrmgr->m_user->SetPref('current_topic',$Picker->m_picked_topic);
	$picked_problem = $Picker->m_picked_problem;
	$picked_problem_id = Null;
	if ($picked_problem !== Null)
	{
		$picked_problem_id = $picked_problem->m_prob_id;
	}
	
	//set start time in session variable
	$start_time = time();
	$_SESSION['start_time'] = $start_time;
	$usrmgr->m_user->SetPref('start_time',$start_time);
}
if ($picked_problem_id !== Null)
{
	$_SESSION['current_problem'] = $picked_problem_id;
	$usrmgr->m_user->SetPref('current_problem',$picked_problem_id);
}
	
	
	
///////////////////////////////////////////////////////////////////////////
// page construction
///////////////////////////////////////////////////////////////////////////
$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Problems'));

# decide if problem or histogram showing and get the correct view
if ($usrmgr->m_user->GetPref('problem_submitted') !== Null)
{
	$content = new VProblems_submitted($picked_problem, $selected_topics_list_id, $remaining_problems_in_topic_list, $total_problems_in_topic_list);
}
elseif ($num_topics >= 1)
{
	$content = new VProblems($picked_problem, $selected_topics_list_id, $remaining_problems_in_topic_list, $total_problems_in_topic_list);
}
else
{
	$content = new VProblems_no_topics();
}
if ($picked_problem == Null)
{
	$content = new Vproblems_no_problems();
}

$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();
?>
