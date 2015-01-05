<?php
require_once("setup.php");

global $usrmgr;
global $dbmgr;

if($usrmgr->m_user->staff == 1)
{
  if(isset($_POST['question_status']))
  {
    $head = new CHeadCSSJavascript("Show Question", array(), array());
    $tab_nav = new VTabNav(new MTabNav('Show Question'));
    $content = new VQuestionsShow($_POST);
    $page = new VPageTabs($head, $tab_nav, $content);

    # delivery the html
    echo $page->Deliver();    
  } elseif (isset($_GET['question_id'])) {
    # code...
  } else {
    $head = new CHeadCSSJavascript("New Question", array(), 
      array('js/tinymce/tinymce.min.js', 'js/questions.js', 'js/tinymce/plugins/tiny_mce_wiris/core/WIRISplugins.js?viewer=image'));
    $tab_nav = new VTabNav(new MTabNav('New Question'));
    $content = new VQuestionsForm();
    $page = new VPageTabs($head, $tab_nav, $content);

    # delivery the html
    echo $page->Deliver();    
  }
} else {
  http_response_code(403);
  echo "<p>Prohibited.  Please contact physics.sso@umich.edu if you are getting this message in error.</p><p><a href=\"selections.php\">Return to Problem Roulette</a></p>";
}

?>
