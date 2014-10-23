<?php

require_once("./include_all_libs.php");

session_start();

global $usrmgr;

//get user_id
$user_id = $usrmgr->m_user->id;

// page construction
$head = new CHeadCSSJavascript("Page Not Found", array(), array());
$tab_nav = new VTabNav(new MTabNav(''));
$content = new VErrorPage();
$page = new VPageTabs($head, $tab_nav, $content);
# delivery the html
echo $page->Deliver();

?>
