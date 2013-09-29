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

$timestamp = time();
$usrmgr->m_user->SetPref('selected_course',10);
$usrmgr->m_user->SetPref('last_activity',$timestamp);
$usrmgr->m_user->SetPref('selected_topics_list', Array(50,51,52,53,54,55));

#print_r($usrmgr->m_user->GetPref('selected_topics_list'));
#print_r($usrmgr->m_user->GetPref('selected_course'));


#header('Location:selections.php');
#header('Location:problems.php');

$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Problems'));
$content = new VSpecialExam();
$page = new VPageTabs($head, $tab_nav, $content);

echo $page->Deliver();

?>
