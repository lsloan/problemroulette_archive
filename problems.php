<?php

require_once("setup.php");

// error_log("Problems");
// error_log(print_r($_POST, true));


// Instead of using session and prefs to track 
// current_problem_id, start_time, end_time, and 
// student_answer, we will use local variables.
// When their values must survive a redirect, 
// we will pass the values in the redirect request.
$c_problem_id = Null;
$c_start_time = Null;
$c_end_time = Null;
$c_answer = Null;
$c_topic_id = Null;

$selected_topics_list_id = Null;

if (isset($_POST['topic_checkbox_submission'])) {
	//check to see if new topic was selected
	//get from checkboxes if available and put into preferences
	$selected_topics_list_id = $_POST['topic_checkbox_submission'];
	
	$usrmgr->m_user->SetSelectedTopicsForClass($usrmgr->m_user->selected_course_id, $selected_topics_list_id);

	header('Location:problems.php');
} elseif (isset($_POST['topic_link_submission'])) {
	//check to see if new topic was selected
	//get from link if available and put into preferences
	$selected_topics_list_id = $_POST['topic_link_submission'];
	if (intval($selected_topics_list_id !== 0))//make sure not to write a string
	{
		$array = array();
		$array[] = $selected_topics_list_id;
		$usrmgr->m_user->SetSelectedTopicsForClass($usrmgr->m_user->selected_course_id,$array);
	}
	header('Location:problems.php');
} elseif (count($usrmgr->m_user->GetSelectedTopics()) < 1) {
	
	# redirect to selections
	header('Location:selections.php');
	exit;

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
	$current_topic_id = intval($_POST['topic']);
	$omitted_problem = new OmittedProblem($user_id, $current_topic_id);
	$current_omitted_problems_list = $omitted_problem->find();
			
	//update tables upon response
	$response = new MResponse($start_time,$end_time,$user_id,$current_problem_id,Null,false,$current_topic_id);
	
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
		
		$ploads = $usrmgr->m_user->page_loads;
		if (is_null($ploads))
			$ploads = 1;
		else
			$ploads += 1;
		$usrmgr->m_user->SetPageLoads($ploads);
		
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
		$current_topic_id = intval($_POST['topic']);
		
		//get user_id
		$user_id = $usrmgr->m_user->id;

		//if the student answered correctly, add current problem to omitted problems list for given topic
		// and set student_answered_correctly to true
		if ($current_problem_answer == $c_answer)
		{
			$omitted_problem = new OmittedProblem($user_id, $current_topic_id, $c_problem_id);
			if ($omitted_problem->count() < 1) {
				$omitted_problem->add();
			}
			$c_student_answered_correctly = true;
		} else {
			$c_student_answered_correctly = false;
		}
		
		//update tables upon response
		$response = new MResponse($c_start_time,$c_end_time,$user_id,$c_problem_id,$c_answer,$c_student_answered_correctly,$current_topic_id);
		
		$response->update_responses();
		// $response->update_stats();
		$response->update_problems();
		$response->update_12m_prob_ans();
		
		header('Location:problems.php?ps=1&pr='.$c_problem_id.'&an='.$c_answer.'&st='.$c_start_time.'&et='.$c_end_time."&tp=".$current_topic_id);
	}
} elseif (isset($_POST['next'])) {
	// handle next event, which may have clarity rating
	include 'ratings.php';
	header('Location:problems.php');
} elseif (isset($_POST['retry'])) {
	$user_id = $usrmgr->m_user->id;
	$c_problem_id = $_POST['retry'];
	$c_topic_id = $_POST['topic'];
	header('Location:problems.php?pretry=1&pr='.$c_problem_id.'&tp='.$c_topic_id);
} elseif (isset($_GET['ps'])) {
	$c_problem_id = $_GET['pr'];
	$c_answer = $_GET['an'];
	$c_start_time = intval($_GET['st']);
	$c_end_time = intval($_GET['et']);
	$c_topic_id = intval($_GET['tp']);
} elseif (isset($_GET['pretry'])) {
	$c_problem_id =  $_GET['pr'];
	$c_topic_id = intval($_GET['tp']);
}

# translate ids to list of topic objects
$selected_topics_list_id = $usrmgr->m_user->selected_topics_list;
$num_topics = count($selected_topics_list_id);
// $selected_topics_list_id might just be a single topic as a string
if (! is_array($selected_topics_list_id))
{
	$selected_topics_list_id = MakeArray($selected_topics_list_id);
}

for ($i=0; $i<$num_topics; $i++)
{
	$one_topic = MTopic::get_topic_by_id($selected_topics_list_id[$i]);
	if($usrmgr->m_user->staff == 1 || $one_topic->m_inactive == 0) {
		$selected_topics_list[] = $one_topic;
	}
}
$num_topics = count($selected_topics_list);

$picker = new MProblemPicker();
if($c_problem_id == null || $c_problem_id < 1) {
	# use newly picked problem
	$picked_problem_id = $picker->m_problem_id;
	$topic = $picker->m_topic_id;
} else {
	# use problem student is already working on
	$picked_problem_id = $c_problem_id;
	$topic = $c_topic_id;
}

$picked_problem = new MProblem($picked_problem_id);
	
///////////////////////////////////////////////////////////////////////////
// page construction
///////////////////////////////////////////////////////////////////////////
$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Problems'));

# decide if problem or histogram showing and get the correct view
if ($c_answer !== Null)
{
	$content = new VProblems_submitted($picked_problem, $picker->m_problem_counts_by_topic, $c_answer, $c_end_time - $c_start_time, $topic);
}
elseif ($num_topics > 0)
{
	$content = new VProblems($picked_problem, $picker->m_problem_counts_by_topic, $topic);
}
else
{
	$content = new VProblems_no_topics();
}

if ($picked_problem_id == Null)
{
	$content = new Vproblems_no_problems();
}

$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();
?>
