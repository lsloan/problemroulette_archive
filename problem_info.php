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
$_SESSION['sesstest'] = 1;

//get problem
if (isset($_POST['problem_info']))
{
	$problem_id = $_POST['problem_info'];
	$problem = new MProblem($problem_id);
}
else
{
	$problem = Null;
}


// page construction
$head = new CHeadCSSJavascript("Problem Info", array(), array());
$tab_nav = new VNoTabNav(new MTabNav('My Summary'));
$content = new VProblemInfo($problem);
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>