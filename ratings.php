<?php
require_once("setup.php");

date_default_timezone_set('America/New_York');

$rating_scales = RatingScale::rating_scales();
$user_id = $usrmgr->m_user->id;

foreach($rating_scales as $scale) {
    if (isset($_POST['rating-'.$scale->m_name]) && isset($_POST['problem_id'])) {

        $rating = new Rating(null, $_POST['problem_id'], $scale->m_id, $user_id, $_POST['rating-'.$scale->m_name]);
        $rating->save();

        header('Content-type: application/json');
        echo json_encode($rating);
        # header('Location:global_alerts.php');
        return;
    }
}



?>
