<?php
// paths

require_once("./paths.inc.php");

// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");
//require_once( $DIR_LIB."usrmgr.php" );
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
$GLOBALS["dbmgr"] = new CDbMgr( "localhost", "pr_user", "pr_user", "prexpansion" );
// session
//require_once( $DIR_LIB."sessions.php" );
//$GLOBALS["sessionmgr"] = new CSessMgr( "session_table", 3600);

// url arguments
$args = GrabAllArgs();

require_once( $DIR_LIB."usrmgr.php" );
$GLOBALS["usrmgr"] = new UserManager();
//business logic

// page construction
$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Problems'));
$content = new VProblems();
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
