<?php

require_once("setup.php");

//logic for course or topic course selector and shown row number selection
global $usrmgr;
$include_inactive_topics = ($usrmgr->m_user->staff == 1);

if (isset($_POST['dropdown_course']))
{
	//get selected course from POST and set preference; then, refresh page
	$selected_course_id = $_POST['dropdown_course'];
	$_SESSION['dropdown_history_course'] = $selected_course_id;
	$usrmgr->m_user->SetPref('dropdown_history_course',$selected_course_id);
	$_SESSION['dropdown_history_topic'] = 'all';
	$usrmgr->m_user->SetPref('dropdown_history_topic','all');
	
	header('Location:stats.php');
}
elseif (isset($_POST['dropdown_topic']))
{
	//get selected topic from POST and set preference; then, refresh page
	$selected_topic_id = $_POST['dropdown_topic'];
	$_SESSION['dropdown_history_topic'] = $selected_topic_id;
	$usrmgr->m_user->SetPref('dropdown_history_topic',$selected_topic_id);
	
	header('Location:stats.php');
}

//get selected course
if (isset($_SESSION['dropdown_history_course']))
{
    $selected_course_id = $_SESSION['dropdown_history_course'];
}
else
{
    $selected_course_id = 'all';
}

//get selected topic
if (isset($_SESSION['dropdown_history_topic']))
{
    $selected_topic_id = $_SESSION['dropdown_history_topic'];
}
else
{
    $selected_topic_id = 'all';
}
    
//get array of all problem IDs within course
if ($selected_course_id == 'all' || $selected_course_id == Null)
{
    $summary = new MUserSummary();
}
else
{
    if ($selected_topic_id == 'all')
    {
        //<DISPLAY ALL PROBLEMS IN GIVEN COURSE>
        $problems_list = Array();
        $all_topics_in_course = MTopic::get_all_topics_in_course($selected_course_id, $include_inactive_topics);//topic objects
        $num_topics = count($all_topics_in_course);
        
        for ($i=0; $i<$num_topics; $i++)
        {
            $problems_list_in_topic = MProblem::get_all_problems_in_topic_with_exclusion($all_topics_in_course[$i]->m_id);
            for ($j=0; $j<count($problems_list_in_topic); $j++)
            {
                array_push($problems_list,$problems_list_in_topic[$j]);
            }
        }
            
        $num_problems = count($problems_list);
        
        $problems_list_id = Array();
        for ($i=0; $i<$num_problems; $i++)
        {
            array_push($problems_list_id, $problems_list[$i]->m_prob_id);
        }
        
        if ($num_problems > 0)
        {
            $summary = new MUserSummary($problems_list_id);
        }
        else
        {
            $summary = new MUserSummary('blank');
        }
    //</DISPLAY ALL PROBLEMS IN GIVEN COURSE>
    }
    else
    {
    //<DISPLAY ALL PROBLEMS IN SELECTED TOPIC>
        $problems_list = MProblem::get_all_problems_in_topic_with_exclusion($selected_topic_id);
        
        $num_problems = count($problems_list);
        
        $problems_list_id = Array();
        for ($i=0; $i<$num_problems; $i++)
        {
            array_push($problems_list_id, $problems_list[$i]->m_prob_id);
        }
        
        if ($num_problems > 0)
        {
            $summary = new MUserSummary($problems_list_id);
        }
        else
        {
            $summary = new MUserSummary('blank');
        }
    //</DISPLAY ALL PROBLEMS IN SELECTED TOPIC>
    }
}

// page construction
$head = new CHeadCSSJavascript("My Summary", array(), array());
$tab_nav = new VTabNav(new MTabNav('My Summary'));
$content = new VStats($summary);
$page = new VPageTabs($head, $tab_nav, $content);

# delivery the html
echo $page->Deliver();

?>
