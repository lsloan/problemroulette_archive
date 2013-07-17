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


// populate and use models for business logic on page

// example of how to use the pref manager -- this just increments a count...
global $usrmgr;
$ploads = $usrmgr->m_user->GetPref('page_loads');
if (is_null($ploads))
    $ploads = 1;
else
    $ploads += 1;
$usrmgr->m_user->SetPref('page_loads', $ploads);

//Get selected_topics_list and put into preferences
$selected_topics_list_id = Null;
$histogram_view = Null;

//check to see if new topic was selected
//get from checkboxes if available and put into preferences
if (isset($_POST['topic_checkbox_submission']))
{
	$selected_topics_list_id = $_POST['topic_checkbox_submission'];
	$usrmgr->m_user->SetPref('selected_topics_list',$selected_topics_list_id);
	$usrmgr->m_user->SetPref('current_problem',Null);
	$usrmgr->m_user->SetPref('problem_submitted',Null);
	header('Location:problems.php');
}

//check to see if new topic was selected
//get from link if available and put into preferences
if (isset($_POST['topic_link_submission']))
{
	$selected_topics_list_id = $_POST['topic_link_submission'];
	$usrmgr->m_user->SetPref('selected_topics_list',$selected_topics_list_id);
	$usrmgr->m_user->SetPref('current_problem',Null);
	$usrmgr->m_user->SetPref('problem_submitted',Null);
	header('Location:problems.php');
}

//check to see if user hit "skip" button
if (isset($_POST['skip']))
{
	$usrmgr->m_user->SetPref('current_problem',Null);
	$usrmgr->m_user->SetPref('problem_submitted',Null);
	header('Location:problems.php');
}

//check to see if user submitted an answer
if (isset($_POST['submit_answer']))
{
	if (isset($_POST['student_answer']))
	{
		$usrmgr->m_user->SetPref('problem_submitted',$_POST['student_answer']);
		header('Location:problems.php');
	}
}

if (isset($_POST['next']))
{
	$usrmgr->m_user->SetPref('current_problem',Null);
	$usrmgr->m_user->SetPref('problem_submitted',Null);
	header('Location:problems.php');
}

//$usrmgr->m_user->SetPref('selected_topics_list',$selected_topics_list_id);
$selected_topics_list_id = $usrmgr->m_user->GetPref('selected_topics_list');
$num_topics = count($selected_topics_list_id);

for ($i=0; $i<$num_topics; $i++)
{
	$selected_topics_list[$i] = MTopic::get_topic_by_id($selected_topics_list_id[$i]);
}

//get omitted problems list and put into preferences
/////////NOT DONE YET

$Picker = new MPpicker();
$Picker->pick_problem();

//pick either current problem a student is working on OR pick new problem
if ($usrmgr->m_user->GetPref('current_problem') != Null)
{
	$picked_problem_id = $usrmgr->m_user->GetPref('current_problem');
	$picked_problem = new MProblem($picked_problem_id);
}
else
{
	$picked_problem = $Picker->m_picked_problem;
	$picked_problem_id = $picked_problem->m_prob_id;
}

$usrmgr->m_user->SetPref('current_problem',$picked_problem_id);

// page construction
$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Problems'));

if ($usrmgr->m_user->GetPref('problem_submitted') != Null)
{
	$content = new VProblems_submitted($picked_problem, $selected_topics_list);
}

elseif ($num_topics >= 1)
{
	$content = new VProblems($picked_problem, $selected_topics_list);
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
