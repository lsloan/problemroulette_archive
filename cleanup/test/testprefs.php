<?php
// paths
require_once("../../paths.inc.php");

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


global $usrmgr;
global $dbmgr;
////////////////////////////////////////////////////////////////////////////////////////

/*$test_user = new MUser('testprefs');

$timestamp = time();

$test_user->SetPref('selected_course',2);
$test_user->SetPref('selected_topics_list',[1,2]);
$test_user->SetPref('last_activity',$timestamp);
//$usrmgr->m_user->SetPref(...);

echo $test_user->GetPref('selected_course');
echo "<br/>";
echo implode(", ",$test_user->GetPref('selected_topics_list'));
echo "<br/>";
echo $test_user->GetPref('last_activity');
*/

//$timestamp = time();

//$usrmgr->m_user->SetPref('selected_course',4);
//$usrmgr->m_user->SetPref('selected_topics_list',[2,5,7]);
//$usrmgr->m_user->SetPref('omitted_problems_list',[1,6,4]);
//$usrmgr->m_user->SetPref('last_activity',$timestamp);

//$CToptions = new MCTSelect();

//$course_or_topic = new MDirector();
//echo $course_or_topic->m_course_or_topic;
//if ($course_or_topic->m_course_or_topic == 1)
//{
	//echo "<br/>".$CToptions->m_selected_course;
//}
//$length = count($usrmgr->m_user->GetPref('selected_topics_list'));
//echo mt_rand(0,$length-1);
//echo $usrmgr->m_user->GetPref('selected_course');

//$testPpicker = new MPpicker();
//$testPpicker->pick_problem();
//echo $testPpicker->m_picked_topic;
//echo $testPpicker->m_picked_problem->m_prob_id;

/*$usrmgr->m_user->SetPref('omitted_problems_list',Null);

$current_omitted_problems_list = $usrmgr->m_user->GetPref('omitted_problems_list');
if ($current_omitted_problems_list == Null)
{
	$current_omitted_problems_list = array();
}
array_push($current_omitted_problems_list,1,2,3,4,5,6);
$usrmgr->m_user->SetPref('omitted_problems_list',$current_omitted_problems_list);
$new_omitted_problems_list = $usrmgr->m_user->GetPref('omitted_problems_list');
echo implode(', ',$new_omitted_problems_list);*/

//$usrmgr->m_user->SetPref('omitted_problems_list[2]',Null);
//$usrmgr->m_user->SetPref('omitted_problems_list[5]',6);
//$usrmgr->m_user->SetPref('omitted_problems_list[7]',[3,5]);

//echo $usrmgr->m_user->GetPref('omitted_problems_list[2]');
//echo "<br/>";
//echo $usrmgr->m_user->GetPref('omitted_problems_list[5]');
//echo "<br/>";
//echo implode($usrmgr->m_user->GetPref('omitted_problems_list[7]'));
	

//generate blank values for 12m_prob_ans for the 7 test problems
/*for ($problem_id=1;$problem_id<8;$problem_id++)
{
	$problem = new MProblem($problem_id);
	
	for ($i=1;$i<($problem->m_prob_ans_count+1);$i++)
	{
		$insertquery = "
		INSERT INTO 12m_prob_ans(
			prob_id, 
			ans_num
		)
		VALUES(
			'".$problem_id."',
			'".$i."'
		)";
		$dbmgr->exec_query($insertquery);
	}
}*/










	
?>