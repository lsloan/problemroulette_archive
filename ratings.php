<?php
require_once("setup.php");

date_default_timezone_set('America/New_York');

$rating_scales = RatingScale::rating_scales();
$user_id = $usrmgr->m_user->id;

if (isset($_POST['problem_id']) && isset($_POST['course_id'])) {

  $problem_id = $_POST['problem_id'];
  $course_id = $_POST['course_id'];
  $course_id_of_problem = MProblem::get_prob_class_id($problem_id);
  if ($course_id == $course_id_of_problem) {
    $course = MCourse::get_course_by_id($course_id);
    if ($course->m_disable_rating) {
      #report an error here because problem ratings are disabled for this class
    } else {
      foreach($rating_scales as $scale) {
          if (isset($_POST['rating-'.$scale->m_name])) {
              $rating = new Rating(null, $problem_id, $scale->m_id, $user_id, $_POST['rating-'.$scale->m_name]);
              $rating->save();
              $caliper->rateProblem($problem_id, $_POST['rating-'.$scale->m_name]);

              header('Content-type: application/json');
              echo json_encode($rating);
          }
      }
    }
  } else {
    # report an error here because the problem and course do not match up
  }
}



?>
