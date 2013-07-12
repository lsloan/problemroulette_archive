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

//get from checkboxes if available and put into preferences
if (isset($_POST['topic_checkbox_submission']))
{
	$selected_topics_list_id = $_POST['topic_checkbox_submission'];
}

//get from link if available and put into preferences
if (isset($_POST['topic_link_submission']))
{
	$selected_topics_list_id = $_POST['topic_link_submission'];
}

$usrmgr->m_user->SetPref('selected_topics_list',$selected_topics_list_id);
$num_topics = count($selected_topics_list_id);

for ($i=0; $i<$num_topics; $i++)
{
	$selected_topics_list[$i] = MTopic::get_topic_by_id($selected_topics_list_id[$i]);
}

//get omitted problems list and put into preferences
/////////NOT DONE YET

$Picker = new MPpicker();
$Picker->pick_problem();

$picked_problem = $Picker->m_picked_problem;

// page construction
$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Problems'));
if ($num_topics >= 1)
{
	$content = new VProblems($picked_problem, $selected_topics_list);
}
else
{
	$content = new VProblems_no_topics();
}
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
