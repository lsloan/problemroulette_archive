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

//logic for course or topic course selector and shown row number selection
global $usrmgr;
if (isset($_POST['dropdown_course']))
{
	//get selected course from POST and set preference
	$selected_course_id = $_POST['dropdown_course'];
	$usrmgr->m_user->SetPref('dropdown_history_course',$selected_course_id);
		
	//get array of all problem IDs within course
	if ($selected_course_id != 'all')
	{
		$problems_list = Array();
		$all_topics_in_course = MTopic::get_all_topics_in_course($selected_course_id);//topic objects
		$num_topics = count($all_topics_in_course);
		
		for ($i=0; $i<$num_topics; $i++)
		{
			$problems_list_in_topic = MProblem::get_all_problems_in_topic_with_exclusion($all_topics_in_course[$i]->m_id);
			for ($j=0; $j<count($problems_list_in_topic); $j++)
			{
				array_push($problems_list,$problems_list_in_topic[$j]);
			}
		}
			
		$num_problems = count($problems_list);
		
		$problems_list_id = Array();
		for ($i=0; $i<$num_problems; $i++)
		{
			array_push($problems_list_id, $problems_list[$i]->m_prob_id);
		}
		
		if ($num_problems > 0)
		{
			$summary = new MUserSummary($problems_list_id);
		}
		else
		{
			$summary = new MUserSummary('blank');
		}
	}
	else
	{
		$summary = new MUserSummary();
	}
}

else
{
	$summary = new MUserSummary();
}

// page construction
$head = new CHeadCSSJavascript("My Summary", array(), array());
$tab_nav = new VTabNav(new MTabNav('My Summary'));
$content = new VStats($summary);
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
