<?php

class CHeadCSSJavascript{
	// properties
	var $m_title;
	var $m_cssfile;
	var $m_javafile;

	function CHeadCSSJavascript($title, $cssfile, $javafile){
		$this->m_title = $title;
		$this->m_javafile = $javafile;
		$this->m_cssfile = $cssfile;
	}

	function Deliver(){
		$str   = "\n<title>".$this->m_title." - Problem Roulette</title>";
        $str .= " 
        <link href='css/bootstrap.css' rel='stylesheet' media='screen'>
        <link href='css/bootstrap-responsive.css' rel='stylesheet' media='screen'>
        <link href='css/styles.css' rel='stylesheet' media='screen'>
        <script src='trackingcode.js'></script>
        <script src='js/jquery-1.10.1.js'></script>
        <script src='js/bootstrap.js'></script>
		<script src='js/checkboxes.js'></script>
		<script type='text/javascript' src='js/jquery.tablesorter.js'></script> 
		<script type='text/javascript' src='js/mytable.js'></script> 
        ";
        if($this->m_cssfile != NULL)
		foreach((array)$this->m_cssfile as $css){
			$str .= "\n<link rel='stylesheet' href='".$css."' type='text/css' media='screen'></link>";
		}
        if($this->m_javafile != NULL)
		foreach((array)$this->m_javafile as $java){
			$str .= "\n<script type='text/JavaScript' src='".$java."'></script>";
		}
        $str .= "
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        ";
		return $str;
	}
}

class VPageTabs{
	// properties
	var $m_head;
    var $m_nav;
    var $m_nav2;
    var $m_content;

	// constructor
	function __construct($head, $nav, $content){
		$this->m_head = $head;
		$this->m_nav = $nav;
		$this->m_content = $content;
	}

	function Deliver(){
		$str 	= "
<html lang='en'>
<!--open head-->
	<head>
    "
	.$this->m_head->Deliver().
	"</head>
<!--close head-->
<!--open body-->
<body>

<div id='wrap'>
    <div class='container'>
        "
        .$this->m_nav->Deliver().
        "
        <div class='tab-content'>
            <div class='tab-pane active' id='problems'>
            " 
            .$this->m_content->Deliver().
            "
            </div>
        </div>
    </div>
    <div id='push'>
    </div>
</div>

<div id='footer'>
    <div class='container'>
    <p class='muted credit'>
      Development of this site was sponsored by the <a href='http://www.provost.umich.edu' target='_blank'>UM Office of the Provost</a> through the Gilbert Whitaker Fund for the Improvement of Teaching.
    </p>
    <p class='muted credit'>
      Please send any feedback to <a href='mailto:physics.sso@umich.edu'>physics.sso@umich.edu</a><br/>
      For issues with the content of the problems, see your instructor first.
    </p>
    </div>
</div>

</body>
<!--close body-->
</html>";
		return $str;
	}
}

class VTabNav
{	
	function __construct($nav)
	{
        $this->m_nav = $nav;
	}
	
	function Deliver()
	{
        $selected = 'Problems';
        $str = "<ul class='nav nav-tabs'>";
		foreach($this->m_nav->m_pages as $tab=>$url)
        {
            $tabStyle = '';
            if($this->m_nav->m_selected == $tab)
                $tabStyle = 'active';
            $str .= "<li class='".$tabStyle."'><a href='".$url."'>".$tab."</a></li>";
        }
        $str .= "</ul>";
        return $str;
    }
}

class VCourseTopicNav
{	
	function __construct($nav)
	{
        $this->m_nav = $nav;
	}
	
	function Deliver()
	{
        $str = "<div id='accordion'>";
        foreach($this->m_nav->m_courses as $cc)
        {
            $str .= "<h3>". $cc->m_name ."</h3><div>";
            foreach($cc->m_topics as $tt) 
            {
                $str .= "<input type='checkbox' name='".$tt->m_id."' value='".$tt->m_id."'>".$tt->m_name."<br>";
            }
            $str .= "</div>";
        }
        $str .= "</div>";
        return $str;
    }
}
/*
            <h3>Section 2</h3>
                <div>
                content
                </div>
            <h3>Section 3</h3>
                <div>
                content
                <ul>
                <li>List item one</li>
                <li>List item two</li>
                <li>List item three</li>
                </ul>
                </div>
            <h3>Section 4</h3>
                <div>
                </div>
*/

class VStaff
{
	#m_model
	
	function __construct()
	{
	}
	
	function Deliver()
	{
        $str = "
        <p>
            hi, this is the staff page... this well soon be more then one page
        </p>";
        return $str;
    }
}

class VStats
{
	#m_model
	
	function __construct()
	{
	}
	
	function Deliver()
	{
		$summary = new MUserSummary();
		
		$num_responses = count($summary->m_problem_list);
		
		$alphabet = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		
		global $usrmgr;
        $str = "
        <p class='half-line'>&nbsp;</p>
        <h4 class='summary-header'>
            ".$usrmgr->m_user->username."'s Summary
        </h4>
		<p>ADD CLASS/TOPIC SELECTORS HERE</p>
		<p>
		You have attempted <b>".$summary->m_tot_tries."</b> problems and you got <b>".$summary->m_tot_correct."</b> right.</br>
		Your accuracy is <b>".round(100*$summary->m_tot_correct/$summary->m_tot_tries,1)."</b>%.</br>
		Your average time per problem is <b>".round($summary->m_tot_time/$summary->m_tot_tries,1)."</b> seconds.
		</p>
		<p>ADD NUM_ROWS/CORRECT_OR_NOT SELECTORS HERE</p>
		<p class='p-num-rows'>
		<!--Show <select class='dropdown-num-rows' name='DropDown' id='dropdown_num_rows'>
			<option value='10' id='10'>10</option>
			<option value='25' id='25'>25</option>
			<option value='50' id='50'>50</option>
			<option value='All' id='AllRows' selected='selected'>All</option>
		</select> rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
		Show: <select class='dropdown-correct'>
			<option value='all'>All</option>
			<option value='correct'>Only Correct</option>
			<option value='incorrect'>Only Incorrect</option>
		</select>

		</p>
		
		<table id='historyTable' class='tablesorter table table-condensed table-striped history'>
			<thead>
				<tr>
					<th>Name</th>
					<th>Date</th>
					<th>Your Answer</th>
					<th>Correct Answer</th>
					<th>Time (seconds)</th>
				</tr>
			</thead>
			<tbody>
				";
				//<history table body>
				for ($i=0; $i<$num_responses; $i++)
				{
					$str .= "
						<tr>
							<td><a class='link-history' href='".$summary->m_problem_list[$i]->m_prob_url."'>".$summary->m_problem_list[$i]->m_prob_name."</a></td>
							<td>".$summary->m_end_time_list[$i]."</td>
							<td class='cell-student-answer'>".$alphabet[$summary->m_student_answer_list[$i]-1]."</td>
							<td class='cell-correct-answer'>".$alphabet[$summary->m_problem_list[$i]->m_prob_correct-1]."</td>
							<td>".$summary->m_solve_time_list[$i]."</td>
						</tr>
					";
				}
				//</history table body>
				$str .= "
			</tbody>
		</table>
        ";
        return $str;
    }
}

class VProblems_no_topics
{
	function __construct()
	{
	}
	
	function Deliver()
	{
        $str = "
            <p class='half-line'>&nbsp;</p>
			<p>
            Sorry! You haven't selected which problems you would like to do
            </p>
			<p>
			<strong>Please return to selections tab to complete your selection</strong>
			</p>
        ";
        return $str;
    }
}

class VProblems_no_problems
{
	function __construct()
	{
	}
	
	function Deliver()
	{
        $str = "
            <p class='half-line'>&nbsp;</p>
			<p>
            Congratulations! You have correctly answered all of the problems for your selected topic(s)
            </p>
			<p>
			<strong>Please return to selections tab to reset your topic(s)</strong>
			</p>
        ";
        return $str;
    }
}

class VProblems
{
	var $v_picked_problem;//picked problem
	var $v_selected_topics_list;//selected topic list by ID
	var $v_selected_topics_list_name;//Selected topic list by NAME
	var $v_remaining_problems_in_topic_list;
	var $v_total_problems_in_topic_list;

	function __construct($picked_problem, $selected_topics_list, $remaining_problems_in_topic_list, $total_problems_in_topic_list)
	{
		$this->v_picked_problem = $picked_problem;
		$selected_topics_list_id = $selected_topics_list;
		$num_topics = count($selected_topics_list_id);
		for ($i=0; $i<$num_topics; $i++)
		{
			$this->v_selected_topics_list[$i] = MTopic::get_topic_by_id($selected_topics_list_id[$i]);
		}
		for ($i=0; $i<count($this->v_selected_topics_list); $i++)
		{
			$this->v_selected_topics_list_name[$i] = $this->v_selected_topics_list[$i]->m_name;
		}
		$this->v_remaining_problems_in_topic_list = $remaining_problems_in_topic_list;
		$this->v_total_problems_in_topic_list = $total_problems_in_topic_list;
	}
	
	function Deliver()
	{
		$alphabet = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$num_answers = $this->v_picked_problem->m_prob_ans_count;
        global $usrmgr;
        $str = "
            <p class='half-line'>&nbsp;</p>
			<p>
			Selected Topics/Remaining Problems: ";
			for ($i=0; $i<count($this->v_selected_topics_list); $i++)
			{
				$topic_depleted_label = " label-inverse";
				if ($this->v_remaining_problems_in_topic_list[$i] == 0)
				{
					$topic_depleted_label = "";
				}
				$str .= "<span class='label".$topic_depleted_label."'>
				".$this->v_selected_topics_list[$i]->m_name.":&nbsp;
				".$this->v_remaining_problems_in_topic_list[$i]."
				/
				".$this->v_total_problems_in_topic_list[$i]."
				</span>&nbsp;";
			}
			$str .= "
			</p>
			<form class='ans-form' action='' method='POST'>
			<p>";
			for ($i=0; $i<$num_answers; $i++)
			{
				$str .= "<input type='radio' 
				class='ans-choice' 
				name='student_answer' 
				value='".($i+1)."' 
				onClick='javascript:document.getElementById(&quot;submit_answer&quot;).disabled=false'></input> 
				<font size='4'>".$alphabet[$i]."</font>
				";
			}
			$str .= "
			</p>
			<button type='submit' 
			class='btn btn-submit' 
			name='submit_answer' 
			value='1' 
			id='submit_answer' 
			disabled='true'>
				Submit
			</button>
			or
			<button type='submit'
			class='btn'
			name='skip'
			value='1'>
				Skip
			</button>
			</form>
			<iframe class='problemIframe' id='problemIframe' src='
			".
			$this->v_picked_problem->m_prob_url
			."'></iframe>
        ";
        return $str;
    }
}

class VProblems_submitted
{
	var $v_picked_problem;
	var $v_selected_topics_list;
	var $v_selected_topics_list_name;
	var $v_remaining_problems_in_topic_list;
	var $v_total_problems_in_topic_list;

	function __construct($picked_problem, $selected_topics_list, $remaining_problems_in_topic_list, $total_problems_in_topic_list)
	{
		$this->v_picked_problem = $picked_problem;
		$selected_topics_list_id = $selected_topics_list;
		$num_topics = count($selected_topics_list_id);
		for ($i=0; $i<$num_topics; $i++)
		{
			$this->v_selected_topics_list[$i] = MTopic::get_topic_by_id($selected_topics_list_id[$i]);
		}
		for ($i=0; $i<count($this->v_selected_topics_list); $i++)
		{
			$this->v_selected_topics_list_name[$i] = $this->v_selected_topics_list[$i]->m_name;
		}
		$this->v_remaining_problems_in_topic_list = $remaining_problems_in_topic_list;
		$this->v_total_problems_in_topic_list = $total_problems_in_topic_list;
	}
	
	function Deliver()
	{
	    global $usrmgr;
		$alphabet = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$correct_answer = $this->v_picked_problem->m_prob_correct;
		$student_answer = $usrmgr->m_user->GetPref('problem_submitted');
		
		$start_time = $usrmgr->m_user->GetPref('start_time');
		$end_time = $usrmgr->m_user->GetPref('end_time');
		$solve_time = $end_time - $start_time;
		
		if ($correct_answer == $student_answer)
		{
			$label_class = 'label-success';
		}
		else
		{
			$label_class = 'label-important';
		}
		
		if ($solve_time <= $this->v_picked_problem->get_avg_time())
		{
			$time_label_class = "label-success";
		}
		elseif ($solve_time <= 1.3*$this->v_picked_problem->get_avg_time())
		{
			$time_label_class = "label-warning";
		}
		else
		{
			$time_label_class = "label-important";
		}
		
		$ans_submit_count_sum = 0;
		for ($i=1;$i<($this->v_picked_problem->m_prob_ans_count+1);$i++)
		{
			$ans_submit_count_sum += $this->v_picked_problem->get_ans_submit_count($i);
		}
		
		$ans_submit_frac_count_string = "";
		$histogram_ans_string = "|";
		for ($i=1;$i<($this->v_picked_problem->m_prob_ans_count+1);$i++)
		{
			if ($i == $this->v_picked_problem->m_prob_ans_count)
			{
				$ans_submit_frac_count_string .= ($this->v_picked_problem->get_ans_submit_count($i))/$ans_submit_count_sum;
			}
			else
			{
				$ans_submit_frac_count_string .= ($this->v_picked_problem->get_ans_submit_count($i))/$ans_submit_count_sum.",";
			}
			$histogram_ans_string .= $alphabet[($i-1)]."|";
		}
		
        $str = "
            <p class='half-line'>&nbsp;</p>
			<p>
			Selected Topics/Remaining Problems: ";
			for ($i=0; $i<count($this->v_selected_topics_list); $i++)
			{
				$topic_depleted_label = " label-inverse";
				if ($this->v_remaining_problems_in_topic_list[$i] == 0)
				{
					$topic_depleted_label = "";
				}
				$str .= "<span class='label".$topic_depleted_label."'>
				".$this->v_selected_topics_list[$i]->m_name.":&nbsp;
				".$this->v_remaining_problems_in_topic_list[$i]."
				/
				".$this->v_total_problems_in_topic_list[$i]."
				</span>&nbsp;";
			}
			$str .= "
			</p>
			<form class='form-next' action='' method='post'>
			<button class='btn btn-next' type='submit' name='next' value='1'>
			Next
			</button>
			</form>
			<p>
			<span class='label student-answer ".$time_label_class."'>
			Your time:&nbsp;
			".$solve_time."
			 seconds
			</span>
			Average user time: 
			".$this->v_picked_problem->get_avg_time()." seconds
			</p>
			<p>
			<span class='label ".$label_class." student-answer'>
			Your answer:&nbsp;
			".$alphabet[$usrmgr->m_user->GetPref('problem_submitted')-1]."
			</span>
			Correct answer: 
			".$alphabet[$correct_answer-1]."
			</p>
			<img class='histogram'
			src='https://chart.googleapis.com/chart?cht=bvs&chd=t:".$ans_submit_frac_count_string."&chs=300x150&chbh=30,12,20&chxt=x,y&chxl=0:".$histogram_ans_string."&chds=a&chm=N*p1,000055,0,-1,13&chco=FFCC33&chtt=Total%20Responses%20(N=".$ans_submit_count_sum.")'>
			</img>
			<iframe class='problemIframe' id='problemIframe' src='
			".
			$this->v_picked_problem->m_prob_url
			."'></iframe>
        ";
        return $str;
    }
}

class VTopic_Selections
{
	var $v_CTprefs;
	var $v_selected_course;
	
	function __construct($CTprefs)
	{
		$this->v_CTprefs = $CTprefs;
		if ($this->v_CTprefs->m_selected_course != Null)
		{
			$selected_course_id = $this->v_CTprefs->m_selected_course;
			$selected_course = MCourse::get_course_by_id($selected_course_id);
		}
		$this->v_selected_course = $selected_course;
	}
	
	function Deliver()
	{
		$str = "<p class='half-line'>&nbsp;</p>
		<p>The links below serve randomly-chosen questions, one at a time, from banks of multiple-choice problems derived from past exams.</p>
		<img class='logo' src='img/PR.jpg' width='200px'></img>
		<p><span class='label label-inverse label-big'>".$this->v_selected_course->m_name."</span></p>
		<p><strong>Please select a topic to begin:</strong></p>
		
		<form action='problems.php' method='post' name='topic_selector'>
		<table class='topic-selector table'>
			<tr>
			<td class='cell-checkbox'><input class='checkbox' type='checkbox' id='select_all_checkboxes' onClick='toggle(this)' /></td>
			<td class='cell-topic'><span class='select-all'>Select All</span></td>
			<td class='cell-remaining'><span class='remaining-problems'>Remaining Problems</span></td>
			</tr>";
			$num_topics_in_course = count($this->v_selected_course->m_topics);
			for ($i=0; $i<$num_topics_in_course; $i++)
			{
				$topic = $this->v_selected_course->m_topics[$i];
				$str .= "<tr>
				<td class='cell-checkbox'><input type='checkbox' 
				class = 'group checkbox' 
				name='topic_checkbox_submission[]'
				value='".$topic->m_id."'/></td>
				
				<td class='cell-topic'><button class='link'
				id='".$topic->m_id."'
				type='submit'
				name='topic_link_submission'
				value='".$topic->m_id."'>
				".$topic->m_name."
				</button></td>
				
				<td class='cell-remaining'><span class='remaining-problems-topic'>
				".count(MProblem::get_all_problems_in_topic_with_exclusion($topic->m_id,1))."/".count(MProblem::get_all_problems_in_topic_with_exclusion($topic->m_id))."
				<a class='link link-reset'
				onClick='reset_topic(&quot;".$topic->m_id."&quot;);'>
				Reset
				</a>
				</span></td>
				</tr>";
			}
			$str .= "
		</table>
		</form>
		
		<form action='' method='post'>
	    <button type='submit' class='btn btn-courses' name='select_different_course' value='1'>
		<i class='icon-arrow-left'></i>
		Select Different Course</button>
		<a href='javascript:void(0);' id='reset-topics' class='btn btn-primary disabled'>Reset Selected Topics</a>
		<a href='javascript:void(0);' id='use-selected' class='btn btn-primary disabled'>Use Selected Topics</a>
		</form>
		";

		return $str;
	}
}

class VCourse_Selections
{
	#m_model
	var $v_all_courses_with_topics;
	
	function __construct($all_courses_with_topics)
	{
		$this->v_all_courses_with_topics = $all_courses_with_topics;
	}
	
	function Deliver()
	{		
        $str = "
        <div class='tab-pane active' id='problems'>
		<p class='half-line'>&nbsp;</p>
        <p>
        Welcome! This site serves random problems from past exams given in introductory courses of the University of Michigan Department of Physics.
        </p>

        <img class='logo' src='img/PR.jpg'></img>

        <p><strong>Please select your class:</strong></p>
            <div class='button-container'>
			<form action='' method='post'>";
				$num_courses = count($this->v_all_courses_with_topics);
				for ($i=0; $i<$num_courses; $i++)
				{
					$str .= "<button 
					class='btn' 
					type='submit' 
					name='course_submission' 
					value='".$this->v_all_courses_with_topics[$i]->m_id."'>
					".$this->v_all_courses_with_topics[$i]->m_name."
					</button><br/>";
				}
				//<button class='btn' type='submit' name='course_submission' value='course1'>Course 1</button><br/>
				//<button class='btn' type='submit' name='course_submission' value='course2'>Course 2</button><br/>
			$str .= "</form>
            <!--<a class='btn' href='Home140.php'>Physics 140</a><br/>
            <a class='btn' href='Home240.php'>Physics 240</a><br/>
            <a class='btn' href='Home135.php'>Physics 135</a><br/>
            <a class='btn' href='Home235.php'>Physics 235</a><br/>-->
            </div>

        </div>
        ";
        return $str;
    }
}

class VHome
{
	#m_model
	
	function __construct()
	{
	}
	
	function Deliver()
	{
        return "hi, this is the home page"; 
    }
}

class VProblemEditReview
{
	#m_model
	
	function __construct($model)
	{
		$this->m_model = $model;
	}
	
	function Deliver()
	{
		$str = '';
		$str.="
			<form name = 'myForm' onsubmit='return validateForm()' method='POST' action='url'>
			<table border='1'>
			  <TR>
				<TD>Problem Name</TD>
				<TD>Problem Name Goes Here</TD>
			  </TR>
			  <TR>
				<TD>Problem URL</TD>
				<TD>Problem URL Goes Here</TD>
			  </TR>
			  <TR>
				<TD>Class Name</TD>
				<TD>Class Name Goes Here</TD>
			  </TR>
			  <TR>
				<TD>Topic Name</TD>
				<TD>Topic Name Goes Here</TD>
			  </TR>
			  <TR>
			  <TR>
				<TD>Number of answer choices</TD>
				<TD>
				  <input id='ans_count' type='text' name='ans_count' size='1'>
				</TD>
			  </TR>
			  <TR>
				<TD>Correct answer choice (1,2,3,...)</TD>
				<TD><input id='correct' type='text' name='correct' size='1'></TD>
			  </TR>
			</table>
			<p><input type='submit' id='submit' value='Submit' name='submit'></p>
			</form>
			<script language='javascript'>
			function validateForm()
			{
			var x=document.forms['myForm']['ans_count'].value;
			var y=document.forms['myForm']['correct'].value;
			if (x==null || x=='' || y==null || y=='')
			  {
			  alert('Form must be filled out');
			  return false;
			  }
			}
			</script>
		";
		return $str;
	}
}
?>
