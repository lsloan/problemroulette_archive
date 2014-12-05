<?php
require_once("setup.php");

$timestamp = time();
$usrmgr->m_user->SetSelectedCourseId(10);
$usrmgr->m_user->SetLastActivity($timestamp);
$usrmgr->m_user->SetSelectedTopicsForClass(10, Array(50,51,52,53,54,55));
# $usrmgr->m_user->SetPref('selected_topics_list', Array(50,51,52,53,54,55));

#print_r($usrmgr->m_user->GetPref('selected_topics_list'));
#print_r($usrmgr->m_user->selected_course_id);


#header('Location:selections.php');
header('Location:problems.php');


?>
