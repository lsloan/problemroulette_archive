<?php
//test_edit
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
		ob_start(); ?>
		<title><?= $this->m_title ?> - Problem Roulette</title>
                <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
		<link href='css/bootstrap.css' rel='stylesheet' media='screen'>
		<link href='css/bootstrap-responsive.css' rel='stylesheet' media='screen'>
		<link href='css/styles.css' rel='stylesheet' media='screen'>
		<script src='js/trackingcode.js'></script>
		<script src='js/jquery-1.10.1.js'></script>
		<script src='js/bootstrap.js'></script>
		<script src='js/checkboxes.js'></script>
		<script type='text/javascript' src='js/jquery.tablesorter.js'></script>
		<script type='text/javascript' src='js/problem_library_actions.js'></script>
		<script type='text/javascript' src='js/problem_edit_actions.js'></script>
		<script type='text/javascript' src='js/mytable.js'></script>
		<script type='text/javascript' src='js/problem.js'></script>
		<script type='text/javascript' src='js/stats_export.js'></script>
		<?php if($this->m_cssfile != NULL): ?>
			<?php foreach((array)$this->m_cssfile as $css): ?>
				<link rel='stylesheet' href='<?= $css ?>' type='text/css' media='screen'></link>
			<?php endforeach ?>
		<?php endif ?>
		<?php if($this->m_javafile != NULL): ?>
			<?php foreach((array)$this->m_javafile as $java): ?>
				<script type='text/JavaScript' src='<?= $java ?>'></script>
			<?php endforeach ?>
		<?php endif ?>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
		<?php return ob_get_clean();
	}
}

class VPageTabs{
	// properties
	var $m_head;
  var $m_nav;
  var $m_nav2;
  var $m_content;
  var $m_alerts;

	// constructor
	function __construct($head, $nav, $content){
		$this->m_head = $head;
		$this->m_nav = $nav;
		$this->m_content = $content;
		$this->m_alerts = GlobalAlert::current_alerts();
	}

	function render($view) {
		ob_start();
		include($view);
		return ob_get_clean();
	}
	function Deliver(){
		return $this->render('views/layout.php');
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
            $str .= "<li  class='".$tabStyle."'><a href='".$url."'>".$tab."</a></li>";
        }
        $str .= "</ul>";
        return $str;
    }
}

class VTabNavProbDisabled extends VTabNav
{
	function Deliver()
	{
        $selected = 'Problems';
        $str = "<ul class='nav nav-tabs'>";
		foreach($this->m_nav->m_pages as $tab=>$url)
        {
            $tabStyle = '';
            if($this->m_nav->m_selected == $tab)
                $tabStyle = 'active';
            if ($tab == 'Problems')
                $str .= "<li  class='problem_tab disabled' ".
            			$tabStyle."onclick='return false'>
            			<a href='".$url."'>".$tab."</a></li>";
            else
            	$str .= "<li  class='".$tabStyle."'><a href='".$url."'>".$tab."</a></li>";
        }
        $str .= "</ul>";
        return $str;
    }
}

class VNoTabNav
{	
	function __construct($nav)
	{
        $this->m_nav = $nav;
	}
	
	function Deliver()
	{
		ob_start(); ?>
		<?php return ob_get_clean();
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

class VStaff
{
	
	function __construct()
	{
	}
	
	function Deliver()
	{
		ob_start(); ?>
		<p>
			hi, this is the staff page... this well soon be more then one page
		</p>
		<? return ob_get_clean();
	}
}

class VStudentPerformance
{
	var $v_summary;//summary of user statistics
	var $v_display_search;//whether or not to display what staff searched for
	
	function __construct($summary, $display_search = 0)
	{
		$this->v_summary = $summary;
		$this->v_display_search = $display_search;
	}
	
	function Deliver()
	{
		global $usrmgr;
		//determine if user has staff permissions
		$staff = $usrmgr->m_user->staff;

		if ($staff == 1)//if user has staff permissions
		{
			$num_responses = $this->v_summary->m_tot_tries;
			
			$all_courses_with_topics = MCourse::get_all_courses_with_topics();
			usort($all_courses_with_topics, array('MCourse', 'alphabetize'));
			$num_courses = count($all_courses_with_topics);
			
			$num_users = $this->v_summary->m_num_users;
			
			$alphabet = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			
			global $usrmgr;
			$str = "
			<p class='half-line'>&nbsp;</p>
			<h4 class='summary-header'>
				Student Peformance
			</h4>
			<div class='div-history-dropdown-course-topic'>
			Filter by Course: 
			<form name='dropdown_course_form' action='' method='POST' class='dropdown-course-topic-form'>
			<select class='dropdown-course' name='dropdown_course'>
			<option value='all' selected='selected'>All Courses</option>";
			for ($i=0; $i<$num_courses; $i++)
			{
				$all_topics_in_course = Array();
				$all_topics_in_course_id = Array();
				$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
				$topic_count = count($all_topics_in_course);
				for($j=0; $j<$topic_count; $j++)
				{
					$all_topics_in_course_id[$j] = $all_topics_in_course[$j]->m_id;
				}
				
				$str .= "<option 
				value='".$all_courses_with_topics[$i]->m_id."'";
				if (isset($_SESSION['sesstest']))
				{
					if (isset($_SESSION['dropdown_history_course']) && $_SESSION['dropdown_history_course'] == $all_courses_with_topics[$i]->m_id)
					{
						$str .= " selected='selected'";
					}
				}
				else
				{
					if ($usrmgr->m_user->GetPref('dropdown_history_course') !== Null && $usrmgr->m_user->GetPref('dropdown_history_course') == $all_courses_with_topics[$i]->m_id)
					{
						$str .= " selected='selected'";
					}
				}
				$str .= ">
				".$all_courses_with_topics[$i]->m_name."
				</option>
				";
			}
			
			if ($this->v_display_search !== 0)
			{
				$str .= "<input type='hidden' name='hidden_search_username' value='".$this->v_display_search."'/>";
			}
			
			$str .= "</select></form>";
			
			for ($i=0; $i<$num_courses; $i++)
			{
				$all_topics_in_course = Array();
				$all_topics_in_course_id = Array();
				$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
				$topic_count = count($all_topics_in_course);
				for($j=0; $j<$topic_count; $j++)
				{
					$all_topics_in_course_id[$j] = $all_topics_in_course[$j]->m_id;
				}
				
				$str .= "
				<input type='hidden'
				id='".$all_courses_with_topics[$i]->m_id."'
				value='".implode(',',$all_topics_in_course_id)."'/>
				";
			}
			
			$str .= "
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Filter by Topic (must select course): 
			<form name='dropdown_topic_form' action='' method='POST' class='dropdown-course-topic-form'>
			<select disabled='disabled' class='dropdown-topic' name='dropdown_topic'>
			<option value='all' selected='selected'>All Topics</option>";
			for ($i=0; $i<$num_courses; $i++)
			{
				$all_topics_in_course = Array();
				$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
				$num_topics = count($all_topics_in_course);
				for ($j=0; $j<$num_topics; $j++)
				{
					$str .= "<option 
					value='".$all_topics_in_course[$j]->m_id."'";
					if (isset($_SESSION['sesstest']))
					{
						if (isset($_SESSION['dropdown_history_topic']) && $_SESSION['dropdown_history_topic'] == $all_topics_in_course[$j]->m_id)
						{
							$str .= " selected='selected'";
						}
					}
					else
					{
						if ($usrmgr->m_user->GetPref('dropdown_history_topic') !== Null && $usrmgr->m_user->GetPref('dropdown_history_topic') == $all_topics_in_course[$j]->m_id)
						{
							$str .= " selected='selected'";
						}
					}
					$str .= ">
					".$all_topics_in_course[$j]->m_name."
					</option>";
				}
			}
						
			$str .= "
			</select>";
			if ($this->v_display_search !== 0)
			{
				$str .= "<input type='hidden' name='hidden_search_username' value='".$this->v_display_search."'/>";
			}
			
			$str .= "
			</form>
			<p>
			<form name='search_username' action='' method='POST' class='form-search-username'>
			Search by Username:
			<input id='input_search_username' name='input_search_username' class='input-search-username'";
			if ($this->v_display_search !== 0)
			{
				$str .= "value='".$this->v_display_search."'";
			}
			else
			{
				$str .= "value=''";
			}
			$str .= "/>
			<button type='submit' class='btn btn-search-username'>Search</button>
			</form>
			<button id='clear_search_username' class='btn btn-search-username'>Clear</button>
			</p>
			</div>
			<p>";
			
			if ($this->v_display_search !== Null && $this->v_display_search !== 0 && $this->v_display_search !== '')
			{
				$str .= "
				Searching for <b>&quot;".$this->v_display_search."&quot;</b>
				</p>
				<p>
				";
			}
			
			if ($num_users == 1)
			{
				$str .= "<b>".$num_users."</b> user has ";
			}
			else
			{
				$str .= "<b>".$num_users."</b> users have ";
			}
			
			$str .= "attempted <b>".$this->v_summary->m_tot_tries."</b> problems";
			if ($this->v_display_search !== Null && $this->v_display_search !== 0 && $this->v_display_search !== '')
			{
				$str .= " and got <b>".$this->v_summary->m_tot_correct."</b> right.</br>";
			
				if ($this->v_summary->m_tot_tries > 0)
				{
					$str .= "The average accuracy is <b>".round(100*$this->v_summary->m_tot_correct/$this->v_summary->m_tot_tries,1)."</b>%.</br>
					The average time per problem is <b>".round($this->v_summary->m_tot_time/$this->v_summary->m_tot_tries,1)."</b> seconds.";
				}
			
				$str .= "
				<form action='problem_info.php' method='POST' target='_blank'>
				<table id='historyTable' class='tablesorter table table-condensed table-striped history'>
					<thead>
						<tr>
							<th>Name</th>
							<th>Date</th>
							<th>Your Answer&nbsp;&nbsp;&nbsp;</th>
							<th>Correct Answer&nbsp;&nbsp;&nbsp;</th>
							<th>Time (seconds)&nbsp;&nbsp;&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<td class='invis'></td>
					<td class='invis'></td>
					<td class='invis'></td>
					<td class='invis'></td>
					<td class='invis'></td>
						";
						//<history table body>
						for ($i=0; $i<$num_responses; $i++)
						{
							$str .= "
								<tr>
									<td>
									<button class='btn btn-link btn-link-history' type='submit' name='problem_info' value='".$this->v_summary->m_problem_list[$i]->m_prob_id."'>
									".$this->v_summary->m_problem_list[$i]->m_prob_name."
									</button></td>
									<td>".$this->v_summary->m_end_time_list[$i]."</td>
									<td class='cell-student-answer'>".$alphabet[$this->v_summary->m_student_answer_list[$i]-1]."</td>
									<td class='cell-correct-answer'>".$alphabet[$this->v_summary->m_problem_list[$i]->m_prob_correct-1]."</td>
									<td>".$this->v_summary->m_solve_time_list[$i]."</td>
								</tr>
							";
						}
						//</history table body>
						$str .= "
					</tbody>
				</table>
				</form>
				";
			}
			else
			{
				$str .= ".";
			}
		}
		else//if user does not have staff permissions
		{
			$str = "<p class='half-line'>&nbsp;</p>
			<p>
				Sorry! You do not have access to this page.
			</p>";
		}
		
        return $str;
    }
}

class VProblemLibrary
{
	
	function __construct($v_problem_library_list)
	{
		$this->v_problem_library_list = $v_problem_library_list;
	}
	
	function Deliver()
	{
		global $usrmgr;
		$staff = $usrmgr->m_user->staff;

		if ($staff == 1)
		{
			$all_courses_with_topics = MCourse::get_all_courses_with_topics();
			usort($all_courses_with_topics, array('MCourse', 'alphabetize'));
			$num_courses = count($all_courses_with_topics);
						
			$alphabet = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			
			global $usrmgr;
			$str = "
			<p class='half-line'>&nbsp;</p>
			<h4 class='summary-header'>
				Update Problem Library
			</h4>
			<button class='btn btn-primary add-CTP' id='add_problem'>Add Problem</button>
			<button class='btn remove-add-CTP-form' id='remove_add_problem_form'><i class='icon-remove'></i></button>
			<button class='btn btn-primary add-CTP' id='add_topic'>Add Topic</button>
			<button class='btn remove-add-CTP-form' id='remove_add_topic_form'><i class='icon-remove'></i></button>
			<button class='btn btn-primary add-CTP' id='add_course'>Add Course</button>
			<button class='btn remove-add-CTP-form' id='remove_add_course_form'><i class='icon-remove'></i></button>
			<div class='div-update-problem-library'>
			<form id='add_course_form' action='' method='POST' class='add-CTP-form'>
			<p>
			<h4 class='add-CTP-title'>Add Course</h4>
			</p>
			<p>
			Course Name (alphanumeric and spaces only):
			<input type='text' placeholder='Course Name' id='add_course_name' name='add_course_name' class='input-error'/>
			</p>
			<p>
			<button class='btn' type='submit' id='submit_add_course'>Submit</button>
			</p>
			</form>
			<form id='add_topic_form' action='' method='POST' class='add-CTP-form'>
			<p>
			<h4 class='add-CTP-title'>Add Topic</h4>
			</p>
			<p>
			Select a Course
			<select id='course_for_new_topic' name='course_for_new_topic' class='input-error'>
			<option value='0' selected='selected'>Select One</option>";
			for ($i=0; $i<$num_courses; $i++)
			{
				$all_topics_in_course = Array();
				$all_topics_in_course_id = Array();
				$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
				$topic_count = count($all_topics_in_course);
				for($j=0; $j<$topic_count; $j++)
				{
					$all_topics_in_course_id[$j] = $all_topics_in_course[$j]->m_id;
				}
				
				$str .= "<option 
				value='".$all_courses_with_topics[$i]->m_id."'>
				".$all_courses_with_topics[$i]->m_name."
				</option>
				";
			}
			$str .= "
			</select>
			</p>
			<p>
			Topic Name (alphanumeric and spaces only):
			<input type='text' placeholder='Topic Name' id='add_topic_name' name='add_topic_name' class='input-error'/>
			</p>
			<p>
			<button class='btn' type='submit' id='submit_add_topic'>Submit</button>
			</p>
			</form>
			<form id='add_problem_form' action='' method='POST' class='add-CTP-form'>
			<p>
				<h4 class='add-CTP-title'>Add Problem</h4>
			</p>
			<p>
				<label for='course_for_new_problem' class='span3 text-right'>Select a Course</label>
				<select id='course_for_new_problem' name='course_for_new_problem' class='input-error'>
					<option value='0' selected='selected'>Select One</option>";
					for ($i=0; $i<$num_courses; $i++)
					{
						$all_topics_in_course = Array();
						$all_topics_in_course_id = Array();
						$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
						$topic_count = count($all_topics_in_course);
						for($j=0; $j<$topic_count; $j++)
						{
							$all_topics_in_course_id[$j] = $all_topics_in_course[$j]->m_id;
						}

						$str .= "<option
						value='".$all_courses_with_topics[$i]->m_id."'>
						".$all_courses_with_topics[$i]->m_name."
						</option>
						";
					}
					$str .= "
				</select>
			</p>
			<p>
				<label for='topic_for_new_problem' class='span3 text-right'>
					Select Topic(s)
				</label>
				<select multiple disabled='disabled' class='input-error' name='topic_for_new_problem[]' id='topic_for_new_problem'>
					<option value='0' selected='selected'>Select Topic(s)</option>";
					for ($i=0; $i<$num_courses; $i++)
					{
						$all_topics_in_course = Array();
						$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
						$num_topics = count($all_topics_in_course);
						for ($j=0; $j<$num_topics; $j++)
						{
							$str .= "<option
							value='".$all_topics_in_course[$j]->m_id."'>
							".$all_topics_in_course[$j]->m_name."
							</option>";
						}
					}
					$str .= "
				</select>
				<span class='label-addendum'>(select course first)</span>
			</p>
			<p>
				<label for='add_problem_name' class='span3 text-right'>
					Problem Name
				</label>
				<input type='text' placeholder='Problem Name' id='add_problem_name' name='add_problem_name' class='input-error input-xlarge' maxlength='200'/>
				 (alphanumeric and spaces only)
			</p>
			<p>
				<label for='add_problem_url' class='span3 text-right'>Problem URL</label>
				<input type='text' placeholder='Problem URL' id='add_problem_url' name='add_problem_url' class='input-error input-xxlarge'  maxlength='300'/>
			</p>
            <p>
                <label for='add_problem_num_ans' class='span3 text-right'>Number of Answers</label>
				<select required id='add_problem_num_ans' name='add_problem_num_ans' class='span1 left'>
				";
				 $str .= MakeSelectOptions(AnswerNumbers());
				$str .="</select>
			</p>

			<p>
                <label for='add_problem_cor_ans' class='span3 text-right'>Correct Answer Number</label>
                <select required type='text'  id='add_problem_cor_ans' name='add_problem_cor_ans' class='span1 left'>
				";
				$str .= MakeSelectOptions(AnswerNumbers());
				$str .="</select>
            </p>
			<p>
				<label for='add_problem_solution_url' class='span3 text-right'>Solution URL (optional)</label>
			    <input type='text' placeholder='Solution URL' id='add_problem_solution_url' name='add_problem_solution_url' class='input-xxlarge' maxlength='300'/>
			</p>
			<p>
			<button class='btn' type='submit' id='submit_add_problem' disabled='disabled'>Submit</button>
			</p>
            </form>
			</div>
			<h4 class='summary-header'>
				Problem Library
			</h4>

			<div class='div-history-dropdown-course-topic'>
			Filter by Course: 
			<form name='PL_dropdown_course_form' action='' method='POST' class='dropdown-course-topic-form'>
			<select name='PL_dropdown_course' id='PL_dropdown_course' class='dropdown-course'>
			<option value='0' selected='selected'>Select a Course</option>";
			for ($i=0; $i<$num_courses; $i++)
			{
				$all_topics_in_course = Array();
				$all_topics_in_course_id = Array();
				$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
				$topic_count = count($all_topics_in_course);
				for($j=0; $j<$topic_count; $j++)
				{
					$all_topics_in_course_id[$j] = $all_topics_in_course[$j]->m_id;
				}
				
				$str .= "<option 
				value='".$all_courses_with_topics[$i]->m_id."'";
				if (isset($_SESSION['dropdown_history_course']) && $_SESSION['dropdown_history_course'] == $all_courses_with_topics[$i]->m_id)
				{
					$str .= " selected='selected'";
				}
				$str .= ">
				".$all_courses_with_topics[$i]->m_name."
				</option>
				";
			}
						
			$str .= "</select>
            </form>";
			
			for ($i=0; $i<$num_courses; $i++)
			{
				$all_topics_in_course = Array();
				$all_topics_in_course_id = Array();
				$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
				$topic_count = count($all_topics_in_course);
				for($j=0; $j<$topic_count; $j++)
				{
					$all_topics_in_course_id[$j] = $all_topics_in_course[$j]->m_id;
				}
				
				$str .= "
				<input type='hidden'
				id='".$all_courses_with_topics[$i]->m_id."'
				value='".implode(',',$all_topics_in_course_id)."'/>
				";
			}
			
			$str .= "
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Filter by Topic (must select course): 
			<form name='PL_dropdown_topic_form' action='' method='POST' class='dropdown-course-topic-form'>
			<select disabled='disabled' name='PL_dropdown_topic' id='PL_dropdown_topic' class='dropdown-topic'>
			<option value='all' selected='selected'>All Topics</option>";
			for ($i=0; $i<$num_courses; $i++)
			{
				$all_topics_in_course = Array();
				$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
				$num_topics = count($all_topics_in_course);
				for ($j=0; $j<$num_topics; $j++)
				{
					$str .= "<option 
					value='".$all_topics_in_course[$j]->m_id."'";
					if (isset($_SESSION['dropdown_history_topic']) && $_SESSION['dropdown_history_topic'] == $all_topics_in_course[$j]->m_id)
					{
						$str .= " selected='selected'";
					}
					$str .= ">
					".$all_topics_in_course[$j]->m_name."
					</option>";
				}
			}
			$str .= "
			</select>
			</form>
			";
					
			if (count($this->v_problem_library_list)>0)
			{
				$str .= "
				</div>
				<div>
				<form action='problem_edit.php' method='POST' >
				<table id='historyTable2' class='tablesorter table table-condensed table-striped history'>
					<thead>
						<tr>
							<th>Name (click to edit)</th>
							<th>Answer Choices</th>
							<th>Correct Answer</th>
							<th>Total Tries</th>
							<th>Accuracy&nbsp;&nbsp;&nbsp;</th>
							<th>Average Time (seconds)</th>
							<th>Solution</th>
						</tr>
					</thead>
					<tbody>
						<td class='invis'></td>
						<td class='invis'></td>
						<td class='invis'></td>
						<td class='invis'></td>
						<td class='invis'></td>
						<td class='invis'></td>
						<td class='invis'></td>
						";
						for ($i=0; $i<count($this->v_problem_library_list); $i++)
						{
							$str .= "
								<tr>
									<td><button class='btn btn-link btn-link-history' type='submit' name='problem_info' value='".$this->v_problem_library_list[$i]->m_prob_id."'>".$this->v_problem_library_list[$i]->m_prob_name."</button></td>
									<td>".$this->v_problem_library_list[$i]->m_prob_ans_count."</td>
									<td>".$alphabet[$this->v_problem_library_list[$i]->m_prob_correct-1]."</td>
									<td>".$this->v_problem_library_list[$i]->m_prob_tot_tries."</td>
									<td>";
									$trytime = $this->v_problem_library_list[$i]->m_prob_tot_tries;
									if ($trytime != 0) {
										$str .= round($this->v_problem_library_list[$i]->m_prob_tot_correct/$this->v_problem_library_list[$i]->m_prob_tot_tries,3)."</td>
												<td>".round($this->v_problem_library_list[$i]->m_prob_tot_time/$this->v_problem_library_list[$i]->m_prob_tot_tries,1);
									}
									else {
										$str .= "0</td><td>0";
									};
									$str .= "</td>
									<td><a href='".$this->v_problem_library_list[$i]->m_prob_solution."'>".$this->v_problem_library_list[$i]->m_prob_solution."</a></td>
								</tr>
							";
						}
						$str .= "
					</tbody>
				</table>
				</form>";
			}
			$all_courses = MCourse::get_all_courses();
			$str .= "<label id='num_courses' class='label-hidden'>".count($all_courses)."</label>";
			for ($i=0;$i<count($all_courses);$i++)
			{
				$str .= "<label id='course".$i."' class='label-hidden'>".$all_courses[$i]->m_name."</label>
				";
			}
			$str .= "</div>";
		}
		else
		{
			$str = "<p class='half-line'>&nbsp;</p>
			<p>
				Sorry! You do not have access to this page.
			</p>";
		}
		
        return $str;
    }
}

class VStats
{
	var $v_summary;//summary statistics from responses table
	
	function __construct($summary)
	{
		$this->v_summary = $summary;
	}
	
	function Deliver()
	{		
		$num_responses = count($this->v_summary->m_problem_list);
		
		$all_courses_with_topics = MCourse::get_all_courses_with_topics();
		usort($all_courses_with_topics, array('MCourse','alphabetize'));
		$num_courses = count($all_courses_with_topics);
		
		$alphabet = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		
		global $usrmgr;
        $str = "
        <p class='half-line'>&nbsp;</p>
        <h4 class='summary-header'>
            ".$usrmgr->m_user->username."'s Summary
        </h4>
		<div class='div-history-dropdown-course-topic'>
		Filter by Course:
		<form name='dropdown_course_form' action='' method='POST' class='dropdown-course-topic-form'>
		<select class='dropdown-course' name='dropdown_course'>
		<option value='-1'>Select a Course</option>
		<option value='all'";

		if (! isset($_SESSION['dropdown_history_course']) || ($_SESSION['dropdown_history_course'] == 'all')) {
		$str .= " selected='selected'";
		}
		$str .=  ">All Courses</option>";

		for ($i=0; $i<$num_courses; $i++)
		{
			$all_topics_in_course = Array();
			$all_topics_in_course_id = Array();
			$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
			$topic_count = count($all_topics_in_course);
			for($j=0; $j<$topic_count; $j++)
			{
				$all_topics_in_course_id[$j] = $all_topics_in_course[$j]->m_id;
			}
			
			$str .= "<option 
			value='".$all_courses_with_topics[$i]->m_id."'";
			if (isset($_SESSION['sesstest']))
			{
				if (isset($_SESSION['dropdown_history_course']) && $_SESSION['dropdown_history_course'] == $all_courses_with_topics[$i]->m_id)
				{
					$str .= " selected='selected'";
				}
			}
			else
			{
				if ($usrmgr->m_user->GetPref('dropdown_history_course') !== Null && $usrmgr->m_user->GetPref('dropdown_history_course') == $all_courses_with_topics[$i]->m_id)
				{
					$str .= " selected='selected'";
				}
			}
			$str .= ">
			".$all_courses_with_topics[$i]->m_name."
			</option>
			";
		}
		$str .= "</select></form>";
		
		for ($i=0; $i<$num_courses; $i++)
		{
			$all_topics_in_course = Array();
			$all_topics_in_course_id = Array();
			$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
			$topic_count = count($all_topics_in_course);
			for($j=0; $j<$topic_count; $j++)
			{
				$all_topics_in_course_id[$j] = $all_topics_in_course[$j]->m_id;
			}
			
			$str .= "
			<input type='hidden'
			id='".$all_courses_with_topics[$i]->m_id."'
			value='".implode(',',$all_topics_in_course_id)."'/>
			";
		}
		
		$str .= "
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Filter by Topic (must select course): 
		<form name='dropdown_topic_form' action='' method='POST' class='dropdown-course-topic-form'>
		<select disabled='disabled' class='dropdown-topic' name='dropdown_topic'>
		<option value='all' selected='selected'>All Topics</option>";
		for ($i=0; $i<$num_courses; $i++)
		{
			$all_topics_in_course = Array();
			$all_topics_in_course = MTopic::get_all_topics_in_course($all_courses_with_topics[$i]->m_id);
			$num_topics = count($all_topics_in_course);
			for ($j=0; $j<$num_topics; $j++)
			{
				$str .= "<option 
				value='".$all_topics_in_course[$j]->m_id."'";
				if (isset($_SESSION['sesstest']))
				{
					if (isset($_SESSION['dropdown_history_topic']) && $_SESSION['dropdown_history_topic'] == $all_topics_in_course[$j]->m_id)
					{
						$str .= " selected='selected'";
					}
				}
				else
				{
					if ($usrmgr->m_user->GetPref('dropdown_history_topic') !== Null && $usrmgr->m_user->GetPref('dropdown_history_topic') == $all_topics_in_course[$j]->m_id)
					{
						$str .= " selected='selected'";
					}
				}
				$str .= ">
				".$all_topics_in_course[$j]->m_name."
				</option>";
			}
		}
		$str .= "
		</select>
		</form>
		
		</div>
		<p>
		You have attempted <b>".$this->v_summary->m_tot_tries."</b> problems and you got <b>".$this->v_summary->m_tot_correct."</b> right.</br>";
		
		if ($this->v_summary->m_tot_tries > 0)
		{
			$str .= "Your accuracy is <b>".round(100*$this->v_summary->m_tot_correct/$this->v_summary->m_tot_tries,1)."</b>%.</br>
			Your average time per problem is <b>".round($this->v_summary->m_tot_time/$this->v_summary->m_tot_tries,1)."</b> seconds.";
		}
		
		$str .= "
		</p>
		<p class='p-num-rows'>
		<!--Show <select class='dropdown-num-rows' name='DropDown' id='dropdown_num_rows'>
			<option value='10'>10</option>
			<option value='25'>25</option>
			<option value='50'>50</option>
			<option value='All' selected='selected'>All</option>
		</select> rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
		Show: <select class='dropdown-correct'>
			<option value='all'>All</option>
			<option value='correct'>Only Correct</option>
			<option value='incorrect'>Only Incorrect</option>
		</select>

		</p>
		
		<form action='problem_info.php' method='POST' target='_blank'>
		<table id='historyTable' class='tablesorter table table-condensed table-striped history'>
			<thead>
				<tr>
					<th>Name</th>
					<th>Date</th>
					<th>Your Answer&nbsp;&nbsp;&nbsp;</th>
					<th>Correct Answer&nbsp;&nbsp;&nbsp;</th>
					<th>Time (seconds)&nbsp;&nbsp;&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<td class='invis'></td>
			<td class='invis'></td>
			<td class='invis'></td>
			<td class='invis'></td>
			<td class='invis'></td>
				";
				//<history table body>
				for ($i=0; $i<$num_responses; $i++)
				{
					$str .= "
						<tr>
							<td>
							<button class='btn btn-link btn-link-history' type='submit' name='problem_info' value='".$this->v_summary->m_problem_list[$i]->m_prob_id."'>
							".$this->v_summary->m_problem_list[$i]->m_prob_name."
							</button></td>
							<td>".$this->v_summary->m_end_time_list[$i]."</td>
							<td class='cell-student-answer'>".$alphabet[$this->v_summary->m_student_answer_list[$i]-1]."</td>
							<td class='cell-correct-answer'>".$alphabet[$this->v_summary->m_problem_list[$i]->m_prob_correct-1]."</td>
							<td>".$this->v_summary->m_solve_time_list[$i]."</td>
						</tr>
					";
				}
				//</history table body>
				$str .= "
			</tbody>
		</table>
		</form>
        ";
        return $str;
    }
}

class VStatsExport
{
	var $v_semesters;
	var $v_courses;
	var $v_files;
	
	function __construct($semesters, $courses, $files)
	{
		$this->v_semesters = $semesters;
		$this->v_courses   = $courses;
		$this->v_files     = $files;
	}
	
	function Deliver()
	{
		global $usrmgr;

		$researcher = $usrmgr->m_user->researcher;

		if ($researcher == 1)//if user has staff permissions
		{	
			// show sql dumps available to download (by date, description).
			// show choices of semesters and classes and enable start of an sql dump.
			ob_start(); ?>
			<div class='tab-pane active' id='export-stats'>
				<div class="export_stats_page">
					<div class="row-fluid">
						<div class="span12">
							<p class='half-line'>&nbsp;</p>
							<h4 class='summary-header'>Export summary data</h4>
							<div class="row-fluid">
								<div class="span8">
									<div class="well well-large">
										<strong>
											Files downloaded from this page are for research purposes only. 
											Disclosure to other people or any other use besides the intended
											purpose may violate policies of The University of Michigan and 
											federal or state laws.
										</strong>
									</div>
								</div>
							</div>
				      <h5>
				      	Options in this page:
				      </h5>
				      <ul>
				      	<li>Download an existing export file</li>
				      	<li>Generate a new export file of all data</li>
				      	<li>Generate a new export file filtered by selester and/or class</li>
				      </ul>
				      <h5>Download existing export file</h5>
				      <?php if($this->v_files == NULL): ?>
				      	<p>No files to download</p>
				      <?php else: ?>
				      	<ul>
									<?php foreach((array)$this->v_files as $file): ?>
										<li class="export_file_for_download">
											<a href='<?= $GLOBALS["DOMAIN"] . 'stats_export.php?download='.$file ?>' class="stats_file" title="Download the file (<?= $file ?>)"><?= $file ?></a>
											<a href='#' class="delete_stats_file" data-url="<?= $GLOBALS["DOMAIN"] . 'stats_export.php' ?>" data-filename="<?= $file ?>" title="Permanently delete the file (<?= $file ?>)">
												<img src="img/delete_16.png"></img>
											</a>
										</li>
									<?php endforeach ?>
								</ul>
				      <?php endif ?>
				      <h5>Generate a new export file</h5>
				      <form action='' method='post'>
					      <h6>Specify filters (if any)</h6>
					      <fieldset>
						      <legend>Semester(s)</legend>
						      <div class="row-fluid">
						      	<p class="span8">
							      	Checking one or more semesters will filter the responses included in the export, 
							      	eliminating any responses that did not occur during the selected semesters.  To 
							      	export data about all semesters, leave all semesters unchecked.
							      </p>
							    </div>
					      	<div class="row-fluid">
										<?php foreach((array)$this->v_semesters as $index => $item): ?>
											<div class="span3">
												<label class="checkbox" for="semester-<?= $item['semester']->m_id ?>">
													<input type="checkbox" name="semester[]" value="<?= $item['semester']->m_id ?>" id="semester-<?= $item['semester']->m_id ?>" class="semester-filter" />
													<strong><?= $item['semester']->m_name ?></strong> <small>(<?= number_format($item['response_count']) ?> responses)</small>
												</label>
											</div>
											<?php if(($index + 1) % 4 == 0): ?>
												</div>
												<div class="row-fluid">
											<?php endif ?>
										<?php endforeach ?>
									</div>
								</fieldset>
								<fieldset>
						      <legend>Course(s)</legend>
						      <div class="row-fluid">
						      	<p class="span8">
							      	Checking one or more courses will filter the responses included in the export, 
							      	eliminating any responses that do not relate to the selected courses.  To 
							      	export data about all courses, leave all courses unchecked.
							      </p>
							    </div>
						      <div class="row-fluid">
										<?php foreach((array)$this->v_courses as $index => $item): ?>
											<div class="span3">
												<label class="checkbox" for="course-<?= $item['course']->m_id ?>">
													<input type="checkbox" name="course[]" value="<?= $item['course']->m_id ?>" id="course-<?= $item['course']->m_id ?>" class="course-filter" />
													<strong><?= $item['course']->m_name ?></strong> <small>(<?= number_format($item['response_count']) ?> responses) </small>
												</label>
											</div>
											<?php if(($index + 1) % 4 == 0): ?>
												</div>
												<div class="row-fluid">
											<?php endif ?>
										<?php endforeach ?>
									</div>
								</fieldset>
								<h5>Start exporting data to file</h5>
								<p>
									<button type='submit' class='btn btn-submit' name='start_export' value='1' id='start_export'>
										Start Export
									</button>
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php return ob_get_clean();
		}
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
	var $v_problem_counts_by_topic;
	var $v_topic;

	function __construct($picked_problem, $problem_counts_by_topic, $topic)
	{
		$this->v_picked_problem = $picked_problem;
		$this->v_problem_counts_by_topic = $problem_counts_by_topic;
		$this->v_topic = $topic;
	}
	
	function Deliver()
	{
		$alphabet = range('A', 'Z');
		$num_answers = $this->v_picked_problem->m_prob_ans_count;
    
    $str = "<p class='half-line'>&nbsp;</p>
			<p>Selected Topics/Remaining Problems: ";
		foreach ($this->v_problem_counts_by_topic as $key => $value) {
			if($value['remaining'] > 0) {
				$str .= "<span class='label label-inverse'>".$value['name'].":&nbsp;".$value['remaining']."&nbsp;/&nbsp;".$value['total']."</span>&nbsp;";
			} else {
				$str .= "<span class='label'>".$value['name'].":&nbsp;".$value['remaining']."&nbsp;/&nbsp;".$value['total']."</span>&nbsp;";
			}
		}

		$str .= "</p>
			<form class='ans-form' name='ans_form' action='' method='POST'>
			<input type='hidden' id='submit_or_skip' name='tbd' value='0'/>
			<input type='hidden' name='problem' value='".$this->v_picked_problem->m_prob_id."'>
			<input type='hidden' name='topic' value='".$this->v_topic."'>
			<input type='hidden' name='started' value='".date("U")."'>
			<br>
			<p>";
			for ($i=0; $i<$num_answers; $i++)
			{
				$str .= "<input type='radio' 
				class='ans-choice' 
				name='student_answer' 
				value='".($i+1)."'></input> 
				<font size='4'>".$alphabet[$i]."</font>";
			}
		$str .= "</p>
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
			id='skip'
			value='1'>
				Skip
			</button>
			</form><div class='problem-name-bar'>".
			$this->v_picked_problem->m_prob_name.
			"</div>
			<iframe class='problemIframe' id='problemIframe' src='".
			$this->v_picked_problem->get_gdoc_url()
			."'></iframe>
      <div class='problem-footer-bar'>".$this->v_picked_problem->m_prob_name."</div>
        ";
      return $str;
    }
}

class VProblems_submitted
{
	var $v_picked_problem;
	var $v_problem_counts_by_topic;
	var $v_solve_time;
	var $v_student_answer;

	function __construct($picked_problem, $problem_counts_by_topic, $student_answer, $solve_time = 0)
	{
		$this->v_picked_problem = $picked_problem;
		$this->v_problem_counts_by_topic = $problem_counts_by_topic;
		$this->v_student_answer = $student_answer;
		$this->v_solve_time = $solve_time;
	}
	
	function Deliver()
	{
		$alphabet = range('A', 'Z');
		$correct_answer = $this->v_picked_problem->m_prob_correct;
		
		//green label for correct answer; red label for incorrect answer
		if ($correct_answer == $this->v_student_answer)
		{
			$label_class = 'label-success';
		}
		else
		{
			$label_class = 'label-important';
		}
		
		//determine total tries for a problem (N)
		$ans_submit_count_sum = 0;
		for ($i=1;$i<($this->v_picked_problem->m_prob_ans_count+1);$i++)
		{
			$ans_submit_count_sum += $this->v_picked_problem->get_ans_submit_count($i);
		}
		
		//create some substrings to help generate histogram
		$ans_submit_frac_count_string = "";
		$histogram_ans_string = "|";
		for ($i=1;$i<($this->v_picked_problem->m_prob_ans_count+1);$i++)
		{
			if ($i == $this->v_picked_problem->m_prob_ans_count)
			{
                if ($ans_submit_count_sum > 0)
                {
                    $ans_submit_frac_count_string .= ($this->v_picked_problem->get_ans_submit_count($i))/$ans_submit_count_sum;
                }
                else
                {
                    $ans_submit_frac_count_string .= $this->v_picked_problem->get_ans_submit_count($i);
                }
			}
			else
			{
                if ($ans_submit_count_sum > 0)
                {
                    $ans_submit_frac_count_string .= ($this->v_picked_problem->get_ans_submit_count($i))/$ans_submit_count_sum.",";
                }
                else
                {
                    $ans_submit_frac_count_string .= $this->v_picked_problem->get_ans_submit_count($i).",";
                }
			}
			$histogram_ans_string .= $alphabet[($i-1)]."|";
		}
		
    $str = "<p class='half-line'>&nbsp;</p>
			<p>Selected Topics/Remaining Problems: ";
		foreach ($this->v_problem_counts_by_topic as $key => $value) {
			if($value['remaining'] > 0) {
				$str .= "<span class='label label-inverse'>".$value['name'].":&nbsp;".$value['remaining']."&nbsp;/&nbsp;".$value['total']."</span>&nbsp;";
			} else {
				$str .= "<span class='label'>".$value['name'].":&nbsp;".$value['remaining']."&nbsp;/&nbsp;".$value['total']."</span>&nbsp;";
			}
		}
		$str .= "</p>
			<form class='form-next' action='' method='post'>
			<button class='btn btn-next' type='submit' name='next' value='1'>
			Next
			</button>
			</form>
			<p>
			<span class='label student-answer'>
			Your time:&nbsp;
			".$this->v_solve_time."
			 seconds
			</span>
			Average user time: 
			".$this->v_picked_problem->get_avg_time()." seconds
			</p>
			<p>
			<span class='label ".$label_class." student-answer'>
			Your answer:&nbsp;".$alphabet[$this->v_student_answer-1]."</span>
			Correct answer: 
			".$alphabet[$correct_answer-1]."</p>";
			if ($this->v_picked_problem->m_prob_solution !== '')
			{
				$str .= "<p>
				Solution: <a class='link' target='_blank' href='".$this->v_picked_problem->m_prob_solution."'>".$this->v_picked_problem->m_prob_name."</a>
				</p>";
			}
      $chart_width = 150;
      if ($this->v_picked_problem->m_prob_ans_count > 3)
      {
          $chart_width = 50*$this->v_picked_problem->m_prob_ans_count;
      }
			$str .= "
			<img class='histogram'
			src='https://chart.googleapis.com/chart?cht=bvs&chd=t:".$ans_submit_frac_count_string."&chs=".$chart_width."x150&chbh=30,12,20&chxt=x,y&chxl=0:".$histogram_ans_string."&chds=a&chm=N*p1,000055,0,-1,13&chco=FFCC33&chtt=Responses%20(N=".$ans_submit_count_sum.")'>
			</img><div class='problem-name-bar'>".
			$this->v_picked_problem->m_prob_name.
			"</div>
			<iframe class='problemIframe' id='problemIframe' src='
			".
			$this->v_picked_problem->get_gdoc_url()
			."'></iframe>
			<div class='problem-footer-bar'>".$this->v_picked_problem->m_prob_name."</div>
        ";
        return $str;
    }
}

class VSpecialExam
{
    function __construct()
	{
	}
	
	function Deliver()
	{
        return "
        <br>
        <p>
        This special crowd sourced exam will only be available to particpants in the Chemistry 130 Exam Prep exercize.
        </p>
        <p>
        This will be available beginning October 11th with additional information <a href='https://ecoach.lsa.umich.edu/coach8/tournament/01/' style='margin:0px;'>here</a> on ECoach.
        </p>
        ";
    }
}
class VTopic_Selections
{
	var $v_CTprefs;//course/topic preferences
	var $v_selected_course;//selected course (course object)
	var $v_pre_fill_topics = 0;//0 for DO NOT pre-fill. 1 for DO pre-fill
	var $v_selected_topics_list_id;//list of topic IDs (ints)
	
	function __construct($CTprefs,$pre_fill_topics)
	{
		$this->v_CTprefs = $CTprefs;
		if ($this->v_CTprefs->m_selected_course != Null)
		{
			$selected_course_id = $this->v_CTprefs->m_selected_course;
			$selected_course = MCourse::get_course_by_id($selected_course_id);
		}
		if ($this->v_CTprefs->m_selected_topics_list != Null)
		{
			$selected_topics_list_id = $this->v_CTprefs->m_selected_topics_list;
		}
		$this->v_selected_course = $selected_course;
		if ($this->v_CTprefs->m_selected_topics_list != Null)
		{
			$this->v_selected_topics_list_id = $selected_topics_list_id;
		}
		$this->v_pre_fill_topics = $pre_fill_topics;
	}
	
	function Deliver()
	{
		$str = "<p class='half-line'>&nbsp;</p>
		<p>The links below serve randomly-chosen questions, one at a time, from banks of multiple-choice problems derived from past exams.</p>
		<img class='logo' src='img/PR.jpg' width='200px'></img>
		<p><span class='label label-inverse label-big'>".$this->v_selected_course->m_name."</span></p>
		<p><strong>Please select a topic to begin:</strong></p>
		
		<form action='' method='post'>
	    <button type='submit' class='btn btn-courses' name='select_different_course' value='1'>
		<i class='icon-arrow-left'></i>
		Select Different Course</button>
		<a href='javascript:void(0);' id='reset-topics-top' class='btn btn-primary disabled'>Reset Selected Topics</a>
		<a href='javascript:void(0);' id='use-selected-top' class='btn btn-primary disabled'>Use Selected Topics</a>
		</form>
		
		<form action='problems.php' method='post' name='topic_selector'>
		<table class='topic-selector table'>
			<tr>
			<td class='cell-checkbox'><input class='checkbox' type='checkbox' id='select_all_checkboxes' onClick='toggle(this)' /></td>
			<td class='cell-topic'><span class='select-all'>Select All</span></td>
			<td class='cell-remaining'><span class='remaining-problems'>Remaining Problems</span></td>
			</tr>";
			$num_topics_in_course = count($this->v_selected_course->m_topics);
			if ($num_topics_in_course > 0)
			{
				for ($i=0; $i<$num_topics_in_course; $i++)
				{
					$topic = $this->v_selected_course->m_topics[$i];
					$str .= "<tr>
					<td class='cell-checkbox'><input type='checkbox' 
					class = 'group checkbox'
					name='topic_checkbox_submission[]'
					value='".$topic->m_id."'";
					if ($this->v_pre_fill_topics == 1)
					{
						if (is_array($this->v_selected_topics_list_id))
						{
							if (in_array($topic->m_id, $this->v_selected_topics_list_id))
							{
								$str .= " checked='checked'";
							}
						}
						elseif ($topic->m_id == $this->v_selected_topics_list_id)
						{
							$str .= " checked='checked'";
						}
					}
					$str .= "/></td>
					
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
	var $v_all_courses_with_topics;
	
	function __construct($all_courses_with_topics)
	{
		$this->v_all_courses_with_topics = $all_courses_with_topics;
	}
				
	function Deliver()
	{
		usort($this->v_all_courses_with_topics, array('MCourse', 'alphabetize'));

        $str = "
        <div class='tab-pane active' id='problems'>
		<p class='half-line'>&nbsp;</p>
        <p>
        Welcome! This site serves random problems from past exams given in courses at the University of Michigan.
        </p>

        <img class='logo' src='img/PR.jpg'></img>

        <p><strong>Please select your class:</strong></p>
            <div class='button-container'>
			<form action='' method='post'>";
				$num_courses = count($this->v_all_courses_with_topics);
				for ($i=0; $i<$num_courses; $i++)
				{
					$str .= "<button 
					class='btn btn-inverse btn-course' 
					type='submit' 
					name='course_submission' 
					value='".$this->v_all_courses_with_topics[$i]->m_id."'>
					".$this->v_all_courses_with_topics[$i]->m_name."
					</button><br/>";
				}
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

class VProblemInfo
{
	//vars
	var $v_problem = Null;//problem to display info about NULL: DISPLAY MESSAGE SAYING YOU NEED PROBLEM
	
	//constructor
	function __construct($problem)
	{
		$this->v_problem = $problem;
	}
	
	function Deliver()
	{
		$alphabet = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		
		if ($this->v_problem == Null)
		{
			$str = "<p class='half-line'>&nbsp;</p>
			<p>Sorry! There is no problem selected. I have no data to give.</p>
			";
		}
		else
		{		
			$correct_answer = $this->v_problem->m_prob_correct;
			
			//determine total tries for a problem (N)
			$ans_submit_count_sum = 0;
			for ($i=1;$i<($this->v_problem->m_prob_ans_count+1);$i++)
			{
				$ans_submit_count_sum += $this->v_problem->get_ans_submit_count($i);
			}
			
			//create some substrings to help generate histogram
			$ans_submit_frac_count_string = "";
			$histogram_ans_string = "|";
			for ($i=1;$i<($this->v_problem->m_prob_ans_count+1);$i++)
			{
				if ($i == $this->v_problem->m_prob_ans_count)
				{
					$ans_submit_frac_count_string .= ($this->v_problem->get_ans_submit_count($i))/$ans_submit_count_sum;
				}
				else
				{
					$ans_submit_frac_count_string .= ($this->v_problem->get_ans_submit_count($i))/$ans_submit_count_sum.",";
				}
				$histogram_ans_string .= $alphabet[($i-1)]."|";
			}
	
			//problem info and histogram
			$str = "
            <p class='half-line'>&nbsp;</p>
            <p>
            Average user time: 
            ".$this->v_problem->get_avg_time()." seconds
            </p>
            <p>
            Correct answer: 
            ".$alphabet[$correct_answer-1]."
            </p>";
            if ($this->v_problem->m_prob_solution !== '')
            {
                $str .= "
                <p>
                Solution: <a class='link' target='_blank' href='".$this->v_problem->m_prob_solution."'>".$this->v_problem->m_prob_name."</a>
                </p>
                ";
            }
            $chart_width = 150;
            $chart_width = 50*$this->v_problem->m_prob_ans_count;
            $str .= "
            <img class='histogram'
            src='https://chart.googleapis.com/chart?cht=bvs&chd=t:".$ans_submit_frac_count_string."&chs=".$chart_width."x150&chbh=30,12,20&chxt=x,y&chxl=0:".$histogram_ans_string."&chds=a&chm=N*p1,000055,0,-1,13&chco=FFCC33&chtt=Responses%20(N=".$ans_submit_count_sum.")'>
            </img><div class='problem-name-bar'>".
						$this->v_problem->m_prob_name.
						"</div>
            <iframe class='problemIframe' id='problemIframe' src='
            ".
            $this->v_problem->get_gdoc_url()
            ."'></iframe>
            <div class='problem-footer-bar'>".$this->v_picked_problem->m_prob_name."</div>
            <p align='center'>
            <font color='blue'>".$this->v_problem->m_prob_url."</font>
            </p>
            ";		
		}
		return $str;
	}
}

class VProblemEdit
{
    //vars
	var $v_problem = Null;//problem to display info about NULL: DISPLAY MESSAGE SAYING YOU NEED PROBLEM
    
    //constructor
    function __construct($problem)
    {
		$this->v_problem = $problem;
    }
    
    //page construction
    function Deliver()
    {
		$alphabet = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		
		if ($this->v_problem == Null)
		{
			$str = "<p class='half-line'>&nbsp;</p>
			<p>Sorry! There is no problem selected. I have no data to give.</p>
			";
		}
		else
		{		
			$correct_answer = $this->v_problem->m_prob_correct;
			
			//determine total tries for a problem (N)
			$ans_submit_count_sum = 0;
			for ($i=1;$i<($this->v_problem->m_prob_ans_count+1);$i++)
			{
				$ans_submit_count_sum += $this->v_problem->get_ans_submit_count($i);
			}
			
			//create some substrings to help generate histogram
			$ans_submit_frac_count_string = "";
			$histogram_ans_string = "|";
			for ($i=1;$i<($this->v_problem->m_prob_ans_count+1);$i++)
			{
				$ans_submit_frac_count_string .=
					($ans_submit_count_sum != 0 ? ($this->v_problem->get_ans_submit_count($i))/$ans_submit_count_sum : 0);

				if ($i != $this->v_problem->m_prob_ans_count)
				{
					$ans_submit_frac_count_string .= ",";
				}
				$histogram_ans_string .= $alphabet[($i-1)]."|";
			}
			// make an array of the topic names
			$topic_choices = array();
			$class_id = MProblem::get_prob_class_id($this->v_problem->m_prob_id);
			$all_topics_in_course = MTopic::get_all_topics_in_course($class_id);
			$num_topics = count($all_topics_in_course);
			for ($j=0; $j<$num_topics; $j++)
			{
				$topic_choices[$all_topics_in_course[$j]->m_name] = $all_topics_in_course[$j]->m_id;
			}

			$str =
			"<p class='half-line'>&nbsp;</p>
			<h1 class='indent10'>Edit Problem Information</h1>

			<form action='' method='POST' class='indent10' id='edit_problem'>
			<p>
			<label for='edit_problem_name' class='span2 text-right' >Problem Name</label>
			<input type='text' required id='edit_problem_name' name='edit_problem_name' value='".$this->v_problem->m_prob_name."' maxlength='200' class='span4 left'/>
			</p>

			<p>
			<label for='topic_for_new_problem' class='span2 text-right'>Topic(s)</label>
			<select  size=". $num_topics ." multiple class='span4' required name='topic_for_new_problem[]' id='topic_for_new_problem' >
			";

			$str .= MakeSelectTopicOptions($topic_choices, $this->v_problem->m_prob_topic_name);
			$str .= "</select></p>

            <p>
                <label for='edit_problem_url' class='span2 text-right'>Problem URL</label>
				<input required type='text'  id='edit_problem_url' name='edit_problem_url' value='".$this->v_problem->m_prob_url."' maxlength='300' class='span7 left'/>
            </p>

            <p>
                <label for='edit_problem_num_ans' class='span2 text-right'>Number of Answers</label>
				<select required id='edit_problem_num_ans' value='".$this->v_problem->m_prob_ans_count."'name='edit_problem_num_ans' class='span1 left'>
				";
				$str .= MakeSelectOptions(AnswerNumbers(), $this->v_problem->m_prob_ans_count);
				$str .="</select>
            </p>

            <p>
                <label for='edit_problem_cor_ans' class='span2 text-right'>Correct Answer Number</label>
                <select required type='text'  id='edit_problem_cor_ans' value='".$this->v_problem->m_prob_correct."' name='edit_problem_cor_ans' class='span1 left'>
				";
				$str .= MakeSelectOptions(AnswerNumbers(), $this->v_problem->m_prob_correct);
				$str .="</select>
            </p>

            <p>
                <label for='edit_problem_sol_url' class='span2 text-right'>Solution URL</label>
				<input type='text' id='edit_problem_sol_url' name='edit_problem_sol_url' value='".$this->v_problem->m_prob_solution."' maxlength='300' class='span7 left'/>
				<a href='#' id='clear_solution' title='Remove the Solution URL'> Clear </a>
            </p>
                <input type='hidden' name='problem_info' value='".$this->v_problem->m_prob_id."'/>
            <div class='row'>
            	<div class='offset3'>
	            	<button class='btn btn-primary offset3 span1 text-center' type='submit' id='edit_problem'>Submit</button>
	            	<a class='btn btn-primary' href='problem_library.php'>Cancel</a>
            	</div>
            </div>
            </form>
            <hr>
            <p align='center'><a href='".$this->v_problem->get_edit_url()."' target='_blank'>Edit the Google Doc</a>
            (Only works if you have permission to edit the Google Doc, and may require login to Google)
            </p>
            ";

            $chart_width = 150;
            if ($this->v_problem->m_prob_ans_count > 3)
            {
                $chart_width = 50*$this->v_problem->m_prob_ans_count;
            }
            $str .= "
            <img class='histogram'
            src='https://chart.googleapis.com/chart?cht=bvs&chd=t:".$ans_submit_frac_count_string."&chs=".$chart_width."x150&chbh=30,12,20&chxt=x,y&chxl=0:".$histogram_ans_string."&chds=a&chm=N*p1,000055,0,-1,13&chco=FFCC33&chtt=Responses%20(N=".$ans_submit_count_sum.")'>
            </img><div class='problem-name-bar'>".
						$this->v_problem->m_prob_name.
						"</div>
            <iframe class='problemIframe' id='problemIframe' src='
            ".
            $this->v_problem->get_gdoc_url()
            ."'></iframe>
            <p align='center'>
            <font color='blue'>".$this->v_problem->m_prob_url."</font>
            </p>
            ";		
        }
        return $str;
    }
}

class VHome
{
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

class VGlobalAlertsAdmin
{
	var $time_now;
	var $time_tomorrow;
	
	function __construct()
	{
		date_default_timezone_set('America/New_York');
		$this->time_now = new DateTime();
		$this->alerts = GlobalAlert::get_alerts();
	}
	
	function Deliver()
	{
		date_default_timezone_set('America/New_York');
		ob_start(); ?>
		<div class="global-alerts-page">
			<h1>Global Alerts</h1>
			<div class="global-alerts-form">
				<form action="global_alerts.php" method="post">
					<h2>New alert</h2>
					<dl>
						<dt>
							Message
						</dt>
						<dd>
							<input type="text" name="global_alert[message]" maxlength="250" class="input-xxlarge" required></input>
						</dd>
						<dt>
							Priority
						</dt>
						<dd>
							<select name="global_alert[priority]">
								<option class="global-alert-priority-3" value="3">Urgent</option>
								<option class="global-alert-priority-2" value="2">Important</option>
								<option class="global-alert-priority-1" value="1">Low-priority</option>
								<option class="global-alert-priority-0" value="0">None</option>
							</select>
						</dd>
						<dt>
							Start-time
						</dt>
						<dd class="input-append date">
							<input id="global-alerts-start-time" data-format="yyyy-MM-dd hh:mm" type="text" name="global_alert[start_time]" required></input>
						</dd>
						<dt>
							End-time
						</dt>
						<dd class="input-append date">
							<input id="global-alerts-end-time" data-format="yyyy-MM-dd hh:mm" type="text" name="global_alert[end_time]" required></input>
						</dd>
					</dl>
					<p>
						<input type="submit" value="Create"></input>
					</p>
						<small><span class="pull-right">
							<?php echo 'PHP version: ' . phpversion(); ?>
						</span></small>
				</form>
			</div>
			<div class="global-alerts-list">
				<h2>Previous alerts</h2>
				<?php if($this->alerts != NULL): ?>
					<table class="table">
						<thead>
							<tr>
								<th id="id">ID</th>
								<th id="message">Message</th>
								<th id="priority">Priority</th>
								<th id="start-time">Start-time</th>
								<th id="end-time">End-time</th>
								<th id="buttons">Expire</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->alerts as $alert): ?>
								<tr>
									<td headers="id">
										<?= $alert->m_id ?>
									</td>
									<td headers="message">
										<?= $alert->m_message ?>
									</td>
									<td headers="priority">
										<?= $alert->m_priority ?>
									</td>
									<td headers="start-time">
										<?= $alert->m_start_time ?>
									</td>
									<td headers="end-time">
										<?= $alert->m_end_time ?>
									</td>
									<td headers="buttons">
										<?php if(new DateTime($alert->m_end_time) > $this->time_now): ?>
											<form action="global_alerts.php" method="post">
												<input type="hidden" name="expire" value="<?= $alert->m_id ?>"></input>
												<input type="submit" value="Expire" class="btn btn-mini"></input> 
											</form>
										<?php else: ?>
											&nbsp;
										<?php endif ?>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				<?php else: ?>
					<p>No previous alerts found</p>
				<?php endif ?>
			</div>
		</div>
		<? return ob_get_clean();
	}
}

class VErrorPage
{
	function __construct()
	{
	}

	function Deliver()
	{
		return "
		<div class='error-page'>
			<img class='logo' src='img/PR.jpg' width='200px' alt='Problem Roulette'/>
			<h1>Page Not Found</h1>
			<p>
			Please contact <a href='mailto:physics.sso@umich.edu'>physics.sso@umich.edu</a> with any problems.
			</p>
		</div>
		";
	}
}

?>
