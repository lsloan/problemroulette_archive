<?php

require_once("./include_all_libs.php");

session_start();

// populate and use models for business logic on page

// page construction
$head = new CHeadCSSJavascript("Problems", array(), array());
$tab_nav = new VTabNav(new MTabNav('Staff Access'));
$content = new VStaff();
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
