<?php
require_once("setup.php");

date_default_timezone_set('America/New_York');

if($usrmgr->m_user->admin == 1)
{
    $json_response = false;
    $file_response = false;

    if (isset($_POST['global_alert'])) {

        $alert = new GlobalAlert(null, $_POST['global_alert']['message'], $_POST['global_alert']['priority'], $_POST['global_alert']['start_time'], $_POST['global_alert']['end_time']);
        $alert->save();

        header('Location:global_alerts.php');

    } elseif (isset($_POST['expire'])) {
        GlobalAlert::expire( $_POST['expire'] );
        
        header('Location:global_alerts.php');
    }

    // page construction
    $head = new CHeadCSSJavascript("Global Alerts Admin", array('css/jquery.simple-dtpicker.css'), array('js/jquery.simple-dtpicker.js', 'js/global_alerts.js'));
    $tab_nav = new VTabNav(new MTabNav('Global Alerts'));
    $content = new VGlobalAlertsAdmin();
    $page = new VPageTabs($head, $tab_nav, $content);

    # delivery the html
    echo $page->Deliver();

} else {
    http_response_code(403);
    echo "<p>Prohibited.  Please contact physics.sso@umich.edu if you are getting this message in error.</p><p><a href=\"selections.php\">Return to Problem Roulette</a></p>";
}

?>
