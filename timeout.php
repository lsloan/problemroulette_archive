<?php
// Flag requests directly to timeout.php so we always terminate the session, but
// don't force another redirect in setup.
$GLOBALS['timeout'] = true;
require_once("setup.php");

$head = new CHeadCSSJavascript("Session Timeout", array(), array());
$tab_nav = new VTabNav(new MTabNav('Session Timeout'));
$content = new VTimeout();
$page = new VPageTabs($head, $tab_nav, $content);

echo $page->Deliver();

