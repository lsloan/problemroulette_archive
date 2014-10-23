<?php
require_once("./include_all_libs.php");

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