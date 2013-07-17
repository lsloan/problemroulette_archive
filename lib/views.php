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
        $str = "
        <p>
            hi, this is the statistics page
        <p>
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
            <p>
            Sorry! There are no remaining problems with your topic selection
            </p>
			<p>
			<strong>Please return to selections tab</strong>
			</p>
        ";
        return $str;
    }
}

class VProblems
{
	var $v_picked_problem;
	var $v_selected_topics_list;
	var $v_selected_topics_list_name;

	function __construct($picked_problem, $selected_topics_list)
	{
		$this->v_picked_problem = $picked_problem;
		$this->v_selected_topics_list = $selected_topics_list;
		for ($i=0; $i<count($this->v_selected_topics_list); $i++)
		{
			$this->v_selected_topics_list_name[$i] = $this->v_selected_topics_list[$i]->m_name;
		}
	}
	
	function Deliver()
	{
		$alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
		$num_answers = $this->v_picked_problem->m_prob_ans_count;
        global $usrmgr;
        $str = "
            <p class='half-line'>&nbsp;</p>
			<p>
			Selected Topics: ";
			for ($i=0; $i<count($this->v_selected_topics_list); $i++)
			{
				$str .= "<span class='label'>
				".$this->v_selected_topics_list[$i]->m_name."
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
			<iframe id='problemIframe' src='
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

	function __construct($picked_problem, $selected_topics_list)
	{
		$this->v_picked_problem = $picked_problem;
		$this->v_selected_topics_list = $selected_topics_list;
		for ($i=0; $i<count($this->v_selected_topics_list); $i++)
		{
			$this->v_selected_topics_list_name[$i] = $this->v_selected_topics_list[$i]->m_name;
		}
	}
	
	function Deliver()
	{
	    global $usrmgr;
		$alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
		$correct_answer = $this->v_picked_problem->m_prob_correct;
		$student_answer = $usrmgr->m_user->GetPref('problem_submitted');
		
		if ($correct_answer == $student_answer)
		{
			$label_class = 'label-success';
		}
		else
		{
			$label_class = 'label-important';
		}
		
        $str = "
            <p class='half-line'>&nbsp;</p>
			<p>
			Selected Topics: ";
			for ($i=0; $i<count($this->v_selected_topics_list); $i++)
			{
				$str .= "<span class='label'>
				".$this->v_selected_topics_list[$i]->m_name."
				</span>&nbsp;";
			}
			$str .= "
			</p>
			<p>
			<span class='label ".$label_class." student-answer'>
			Your answer:&nbsp;
			".$alphabet[$usrmgr->m_user->GetPref('problem_submitted')-1]."
			</span>
			Correct answer: 
			".$alphabet[$correct_answer-1]."
			</p>
			<form action='' method='post'>
			<button class='btn' type='submit' name='next' value='1'>
			Next
			</button>
			</form>
			<iframe id='problemIframe' src='
			".
			$this->v_picked_problem->m_prob_url
			."'></iframe>
        ";
        return $str;
    }
}

class VTopic_Selections
{
	var $v_selected_course;
	
	function __construct($selected_course)
	{
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
		<ul class='topic-selector'>
			<li>
			<input type='checkbox' id='all' onClick='toggle(this)' />
			<span class='select-all'>Select All</span>
			</li>";
			$num_topics_in_course = count($this->v_selected_course->m_topics);
			for ($i=0; $i<$num_topics_in_course; $i++)
			{
				$str .= "<li>
				<input type='checkbox' 
				class = 'group' 
				name='topic_checkbox_submission[]'
				value='".$this->v_selected_course->m_topics[$i]->m_id."'/>
				
				<button class='link'
				type='submit'
				name='topic_link_submission'
				value='".$this->v_selected_course->m_topics[$i]->m_id."'>
				".$this->v_selected_course->m_topics[$i]->m_name."
				</button>
				
				</li>";
			}
			$str .= "
		</ul>
		</form>
		
		<form action='' method='post'>
	    <button type='submit' class='btn btn-courses' name='select_different_course' value='1'>
		<i class='icon-arrow-left'></i>
		Select Different Course</button>
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
