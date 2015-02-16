<?php
require_once("setup.php");

// business logic
//get problem
if (isset($_POST['problem_info']))
{
	$problem_id = $_POST['problem_info'];
	$problem = new MProblem($problem_id);
}
elseif (isset($_GET['p_id']))
{
    $problem_id = $_GET['p_id'];
    $problem = new MProblem($problem_id);
}
else
{
	$problem = Null;
}

// //Update problem name in database if requested
// if (isset($_POST['edit_problem_name']))
// {
//     $new_problem_name = $_POST['edit_problem_name'];
//     MProblem::update_problem_name($problem_id,$new_problem_name);
//     header('Location:problem_edit.php?p_id='.$problem_id);
// }

// //Update problem URL in database if requested
// if (isset($_POST['edit_problem_url']))
// {
//     $new_problem_url = str_replace(' ','',$_POST['edit_problem_url']);
//     MProblem::update_problem_url($problem_id,$new_problem_url);
//     header('Location:problem_edit.php?p_id='.$problem_id);
// }

// //Update number of answers in database if requested
// if (isset($_POST['edit_problem_num_ans']))
// {
//     $new_problem_num_ans = str_replace(' ','',$_POST['edit_problem_num_ans']);
//     MProblem::update_problem_num_ans($problem_id,$new_problem_num_ans);
//     header('Location:problem_edit.php?p_id='.$problem_id);
// }

// //Update correct answer in database if requested
// if (isset($_POST['edit_problem_cor_ans']))
// {
//     $new_problem_cor_ans = str_replace(' ','',$_POST['edit_problem_cor_ans']);
//     MProblem::update_problem_cor_ans($problem_id,$new_problem_cor_ans);
//     header('Location:problem_edit.php?p_id='.$problem_id);
// }

// //Update solution URL in database if requested
// if (isset($_POST['edit_problem_sol_url']))
// {
//     $new_problem_sol_url = str_replace(' ','',$_POST['edit_problem_sol_url']);
//     MProblem::update_problem_sol_url($problem_id,$new_problem_sol_url);
//     header('Location:problem_edit.php?p_id='.$problem_id);
// }
if (isset($_POST['edit_problem_name']))
{
    $new_problem_name = $_POST['edit_problem_name'];
    $new_problem_url = str_replace(' ','',$_POST['edit_problem_url']);
    $new_problem_num_ans = str_replace(' ','',$_POST['edit_problem_num_ans']);
    $new_problem_cor_ans = str_replace(' ','',$_POST['edit_problem_cor_ans']);
    $new_problem_sol_url = str_replace(' ','',$_POST['edit_problem_sol_url']);
    $new_topic_id = $_POST['topic_for_new_problem'];
    MProblem::update_problem($problem_id, $new_problem_name, $new_problem_url, $new_problem_num_ans, $new_problem_cor_ans, $new_problem_sol_url);
    # old_topics are the ids of the problem's current topics
    # new_topic_id are the new set of topic ids
    # delete what's in old and not in new, then add whats in new and not in old
    $old_topics = MProblem::get_problem_topics($problem_id);
    $to_add = array_diff($new_topic_id, $old_topics);
    $to_delete = array_diff($old_topics, $new_topic_id);
    if(count($to_delete) > 0) {
        MTopic::remove_problem_topics($problem_id, $to_delete);
    }
    foreach ($to_add as $id) {
        MTopic::update_problem_topic($problem_id, $id);
    }
    unset($id);
    # header('Location:problem_edit.php?p_id='.$problem_id);
    header('Location:problem_library.php');
}

// page construction
$head = new CHeadCSSJavascript("Edit Problem", array(), array());
$tab_nav = new VNoTabNav(new MTabNav('My Summary'));
$content = new VProblemEdit($problem);
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
