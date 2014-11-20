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
global $usrmgr;

//BUSINESS LOGIC
////////////////////////////////////////////////////////////////////////////////////////////////////

//Set selected_course or selected_topics_list to Null if it is currently a string (instead of a number)
MDirector::safecheck_CT_selected();

//take course/topic data from POST variable and set it as session variable and user preference (if a user selected a new course/topic)
MDirector::post2sess_CT_history();

//Use any POST data received
if (isset($_POST['add_course_name']))
{
	//Add a Course to Database
	MDirector::add_course_to_db($_POST['add_course_name']);
	header('Location:problem_library.php');//necessary so user does not refresh and resubmit form
}

if (isset($_POST['add_topic_name']))
{
	//Add a Topic to Database
	MDirector::add_topic_to_db($_POST['course_for_new_topic'],$_POST['add_topic_name']);
	header('Location:problem_library.php');//necessary so user does not refresh and resubmit form
}

if (isset($_POST['add_problem_name']))
{
    //Add a problem to Database
    MDirector::add_problem_to_db($_POST['topic_for_new_problem'], $_POST['add_problem_name'], str_replace(' ','',$_POST['add_problem_url']), str_replace(' ','',$_POST['add_problem_num_ans']), str_replace(' ','',$_POST['add_problem_cor_ans']), str_replace(' ','',$_POST['add_problem_solution_url']));
    header('Location:problem_library.php');//necessary so user does not refresh and resubmit form
}

if (isset($_POST['PL_dropdown_course_selected']))
{
    //Set course dropdown to selected choice
    $_SESSION['dropdown_history_course'] = $_POST['PL_dropdown_course_selected'];
    $_SESSION['dropdown_history_topic'] = 'all';
}

if (isset($_POST['PL_dropdown_topic_selected']))
{
    //Set topic dropdown to selected choice
    $_SESSION['dropdown_history_topic'] = $_POST['PL_dropdown_topic_selected'];
}

//get selected course/topic history
$selected_course_id = MDirector::get_selected_course_history();
$selected_topic_id = MDirector::get_selected_topic_history();

//generate array of problem objects
$problem_library_list = MDirector::get_problem_library_list($selected_course_id,$selected_topic_id);
////////////////////////////////////////////////////////////////////////////////////////////////////

// page construction
$head = new CHeadCSSJavascript("Problem Library", array(), array());
$tab_nav = new VTabNav(new MTabNav('Problem Library'));
$content = new VProblemLibrary($problem_library_list);
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
