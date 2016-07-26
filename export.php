<?php
require_once("setup.php");
$head = new CHeadCSSJavascript("Export Stats", array(), array());
$tab_nav = new VTabNav(new MTabNav('Export Stats'));
$content = new VExport();
$page = new VPageTabs($head, $tab_nav, $content);
echo $page->Deliver();

