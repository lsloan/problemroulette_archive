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

// Instead of using session and prefs to track 
// current_problem_id, start_time, end_time, and 
// student_answer, we will use local variables.
// When their values must survive a redirect, 
// we will pass the values in the redirect request.
$c_problem_id = Null;
$c_start_time = Null;
$c_end_time = Null;
$c_answer = Null;

$_SESSION['sesstest'] = 1;

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

$selected_topics_list_id = Null;

if (isset($_POST['topic_checkbox_submission'])) {
	//check to see if new topic was selected
	//get from checkboxes if available and put into preferences
	$selected_topics_list_id = $_POST['topic_checkbox_submission'];
//
//	if (min(implode(",",array_map("intval",$selected_topics_list_id))) !== 0)//make sure not to write a string
//  prevent error in logs re only one arg to min must be an array - implode was making it a string
	if ( min(array_map("intval",$selected_topics_list_id)) !== 0 )
	{
		$usrmgr->m_user->SetPref('selected_topics_list',$selected_topics_list_id);
	}
	header('Location:problems.php');
} elseif (isset($_POST['topic_link_submission'])) {
	//check to see if new topic was selected
	//get from link if available and put into preferences
	$selected_topics_list_id = $_POST['topic_link_submission'];
	if (intval($selected_topics_list !== 0))//make sure not to write a string
	{
		$usrmgr->m_user->SetPref('selected_topics_list',$selected_topics_list_id);
	}
	header('Location:problems.php');
} elseif (isset($_POST['skip'])) {
	//check to see if user hit "skip" button

	//get end time and compare to start time to get total time
	$end_time = time();
	
	//get current problem
	$current_problem_id = $_POST['problem'];
	$current_problem = new MProblem($current_problem_id);
	$start_time = $_POST['started'];	

	//get user_id
	$user_id = $usrmgr->m_user->id;

	//get current topic_id and omitted problems list for given topic
	$current_topic_id = intval($usrmgr->m_user->GetPref('current_topic'));
	$omitted_problem = new OmittedProblem($user_id, $current_topic_id);
	$current_omitted_problems_list = $omitted_problem->find();
			
	//update tables upon response
	$response = new MResponse($start_time,$end_time,$user_id,$current_problem_id,Null);
	
	$response->update_skips();
	
	header('Location:problems.php');
} elseif (isset($_POST['submit_answer'])) {
	//check to see if user submitted an answer
	//if so, {set pref 'problem_submitted' to something other than null to display submitted problem view
	//if they get the problem right, exclude problem in future}
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
		$c_start_time = $_POST['started'];
		$c_end_time = time();
				
		//get student answer
		// $student_answer = $_POST['student_answer'];
		$c_answer = $_POST['student_answer'];
		
		//get current problem and correct answer
		$c_problem_id = $_POST['problem'];
		$current_problem = new MProblem($c_problem_id);
		$current_problem_answer = $current_problem->m_prob_correct;

		//get current topic_id and omitted problems list for given topic
		$current_topic_id = intval($usrmgr->m_user->GetPref('current_topic'));
		
		//get user_id
		$user_id = $usrmgr->m_user->id;

		//if the student answered correctly, add current problem to omitted problems list for given topic
		if ($current_problem_answer == $c_answer)
		{
			$omitted_problem = new OmittedProblem($user_id, $current_topic_id, $c_problem_id);
			if ($omitted_problem->count() < 1) {
				$omitted_problem->add();
			}
		}
		
		//update tables upon response
		$response = new MResponse($c_start_time,$c_end_time,$user_id,$c_problem_id,$c_answer);
		
		$response->update_responses();
		$response->update_stats();
		$response->update_problems();
		$response->update_12m_prob_ans();
		
		header('Location:problems.php?ps=1&pr='.$c_problem_id.'&an='.$c_answer.'&st='.$c_start_time.'&et='.$c_end_time);
	}
} elseif (isset($_POST['next'])) {
	// handle next event
	header('Location:problems.php');
} elseif (isset($_GET['ps'])) {
	$c_problem_id = $_GET['pr'];
	$c_answer = $_GET['an'];
	$c_start_time = intval($_GET['st']);
	$c_end_time = intval($_GET['et']);
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
if ($c_problem_id !== Null)
{
	//use current problem
	$picked_problem_id = $c_problem_id;
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
}
	
	
///////////////////////////////////////////////////////////////////////////
// page construction
///////////////////////////////////////////////////////////////////////////
$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Problems'));

# decide if problem or histogram showing and get the correct view
if ($c_answer !== Null)
{
	$content = new VProblems_submitted($picked_problem, $selected_topics_list_id, $remaining_problems_in_topic_list, $total_problems_in_topic_list, $c_answer, $c_end_time - $c_start_time);
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
