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

global $usrmgr;

$timestamp = time();

$usrmgr->m_user->SetPref('selected_course',4);
$usrmgr->m_user->SetPref('selected_topics_list',[2,5,7]);
//$usrmgr->m_user->SetPref('last_activity',$timestamp);

$CToptions = new MCTSelect();

$course_or_topic = new MDirector();
echo $course_or_topic->m_course_or_topic;
if ($course_or_topic->m_course_or_topic == 1)
{
	echo "<br/>".$CToptions->m_selected_course;
}
//echo $usrmgr->m_user->GetPref('selected_course');
?>