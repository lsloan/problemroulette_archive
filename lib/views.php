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
<html>";
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

class VProblems
{
	function __construct()
	{
	}
	
	function Deliver()
	{
        global $usrmgr;
        $str = "
            <p>
            This is the problems page! " .$usrmgr->GetUserId(). "
            </p>
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
		$str = "<p>The links below serve randomly-chosen questions, one at a time, from banks of multiple-choice problems derived from past exams.</p>
		<img class='logo' src='img/PR.jpg' width='200px'></img>
		<p><strong><font size='5'>".$this->v_selected_course->m_name."</font></strong></p>
		<p><strong>Please select a topic to begin:</strong></p>
		
		<form action='problems.php' method='post' name='topic_selector'>
		<ul class='topic-selector'>
			";
			$num_topics_in_course = count($this->v_selected_course->m_topics);
			for ($i=0; $i<$num_topics_in_course; $i++)
			{
				$str .= "<li>
				<input type='checkbox' 
				name='topic_checkbox_submission[]'
				value='".$this->v_selected_course->m_topics[$i]->m_id."'/>
				
				<button class='link'
				type='submit'
				name='topic_link_submission[]'
				value='".$this->v_selected_course->m_topics[$i]->m_id."'>
				".$this->v_selected_course->m_topics[$i]->m_name."
				</button>
				
				</li>";
			}
			$str .= "
		</ul>
		</form>
		
		<form action='' method='post'>
	    <button type='submit' class='btn btn-courses'><i class='icon-arrow-left'></i>Select Different Course</button>
		<a href='javascript:document.topic_selector.submit();' id='use-selected' class='btn btn-primary disabled'>Use Selected Topics</a>
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
        <div class='tab-pane' id='statistics'>
        statistics!
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
