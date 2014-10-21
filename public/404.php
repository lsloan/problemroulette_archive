<?php
// pathsTESTGIT
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

//get user_id
$user_id = $usrmgr->m_user->id;

// page construction
$head = new CHeadCSSJavascript("Selections", array(), array());
$tab_nav = new VTabNav(new MTabNav(''));
$content = new VErrorPage();
$page = new VPageTabs($head, $tab_nav, $content);
# delivery the html
echo $page->Deliver();

?>
