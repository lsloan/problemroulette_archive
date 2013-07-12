<?php

// MODEL OBJECTS
Class MProblem
{
	var $m_prob_id;			#ID of problem
	var $m_prob_name;		#Name of problem
	var $m_prob_url;		#URL of problem
	//var $m_prob_topic_id;	#topic of problem
	var $m_prob_ans_count;	#Number of answers for problem
	var $m_prob_correct;	#Correct answer choice for problem
	
	function __construct($prob_id = Null)
	{
		if ($prob_id == Null)
		{
			return;
		}
        global $dbmgr; 
		$selectquery = "SELECT * 
		FROM problems
		WHERE id = ".$prob_id;
        $res = $dbmgr->fetch_assoc($selectquery);
		$this->m_prob_id = $prob_id;
		$this->m_prob_name = $res[0]['name'];
		$this->m_prob_url = $res[0]['url'];
		//$this->m_prob_topic_id = $res[0]['topic_id'];
		$this->m_prob_ans_count = $res[0]['ans_count'];
		$this->m_prob_correct = $res[0]['correct'];
	}
	
	function create($prob_name, $prob_url, $prob_topic_id, $prob_ans_count, $prob_correct)
	{	
        global $dbmgr; 
		$insertquery = "
        INSERT INTO problems(
			topic_id,
			name,
			url,
			correct,
			ans_count
        )VALUES(
            '".$prob_topic_id."',
            '".$prob_name."',
            '".$prob_url."',
            '".$prob_correct."',
            '".$prob_ans_count."'
        )";
        $dbmgr->exec_query($insertquery);
	}
	
	function Get_GD_info()
	{
	#call GD API
		#get url
		#get doc name
		#check to see if it's published
		
		#$file = $service->files->get($fileId);
		#print "Title: " . $file->getTitle();
	}
		
	function Create_new_GD()
	{
		#this.url = '...'
		#...
	}
	
	function Retrieve($prob_id)
	{
		#make object turn into this problem
	}
	
	function Update($prob_url=Null, $others=Null)
	{
		#update php variables with new problem info
	}
	
	function Persist()
	{
		#push data to database
	}
	
	/*public static function get_all_problems_in_topic($topic_id)
	{
		global $dbmgr;
		$selectquery = "SELECT * 
		FROM 12m_topic_prob
		WHERE topic_id = ".$topic_id;
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		#$all_prob_ids_in_topic = array();
		$all_problems_in_topic = array();
		for ($i=0; $i<$numrows; $i++)
		{
			#$all_prob_ids_in_topic[$i] = $res[$i]['problem_id'];
			$all_problems_in_topic[$i] = new MProblem($res[$i]['problem_id']);
		}
		*/
		/*$whereclause = "WHERE ";
		for ($i=0; $i<$numrows; $i++)
		{
			$whereclause .= "id = ".$all_prob_ids_in_topic[$i];
			if ($i < ($numrows - 1))
			{
				$whereclause .= " OR ";
			}
		}
		
		$selectquery = "SELECT * 
		FROM problems
		".$whereclause;
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_problems_in_topic = array();
		for ($i=0; $i<$numrows; $i++)
		{
			#$all_problems_in_topic[$i] = new MProblem($res[$i]['id'],$res[$i]['name']);
			#TEST ECHO:::::
			$all_problems_in_topic[$i] = $res[$i]['name'];
		}
		*/
		/*TEST ECHO:::::
		for ($i=0; $i<$numrows; $i++)
		{
			echo $all_problems_in_topic[$i]->m_prob_name;
		}
		*/
		/*return $all_problems_in_topic;
	}*/
	
	public static function get_all_problems_in_topic_with_exclusion($topic_id,$omitted_problems_list = Null)
	{
		global $dbmgr;
		$selectquery = "SELECT * 
		FROM 12m_topic_prob
		WHERE topic_id = ".$topic_id;
		if ($omitted_problems_list != Null)
		{
			$selectquery .= " AND ";
			$omitted_length = count($omitted_problems_list);
			for ($i=0; $i<$omitted_length; $i++)
		{
			$selectquery .= "problem_id <> ".$omitted_problems_list[$i];
			if ($i < ($omitted_length - 1))
			{
				$selectquery .= " AND ";
			}
		}
		}
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_problems_in_topic = array();
		for ($i=0; $i<$numrows; $i++)
		{
			$all_problems_in_topic[$i] = new MProblem($res[$i]['problem_id']);
		}
		return $all_problems_in_topic;
	}	
}

Class MCourse
{
    var $m_id;
    var $m_name;
    var $m_topics = Array(); // Courses have an array of topics

	function __construct($id,$name)
	{
		$this->m_id = $id;
		$this->m_name = $name;
	}
	
	function create($name)
	{
		global $dbmgr;
		$insertquery = "
		INSERT INTO class(
			name
		) VALUES(
			'".$name."'
		)
		";
		$dbmgr->exec_query($insertquery);
	}
	
	public static function get_course_by_id($id)
	{
		global $dbmgr;
		$selectquery = "SELECT * FROM class WHERE id = ".$id;
		$res = $dbmgr->fetch_assoc($selectquery);
		$course = new MCourse($res[0]['id'],$res[0]['name']);
		$course->m_topics = MTopic::get_all_topics_in_course($course->m_id);
		return $course;
	}
	
	public static function get_all_courses()
	{
		global $dbmgr;
		$selectquery = "SELECT * FROM class";
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_courses = array();
		for ($i=0; $i<$numrows; $i++)
		{
			$all_courses[$i] = new MCourse($res[$i]['id'],$res[$i]['name']);
		}
		return $all_courses;
	}

	public static function get_all_courses_with_topics()
	{
		global $dbmgr;
		$selectquery = "SELECT * FROM class";
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_courses = array();
		for ($i=0; $i<$numrows; $i++)
		{
            $course = new MCourse($res[$i]['id'],$res[$i]['name']);
            $course->m_topics = MTopic::get_all_topics_in_course($course->m_id);
			array_push($all_courses, $course);
		}
		return $all_courses;
	}
}

Class MTopic
{
    var $m_id;
    var $m_name;
    var $m_course;
    var $m_questions; // Topics have an array of questions
	
	function __construct($id,$name)
	{
		$this->m_id = $id;
		$this->m_name = $name;
	}
	
	public static function get_topic_by_id($id)
	{
		global $dbmgr;
		$selectquery = "SELECT * FROM topic WHERE id = ".$id;
		$res = $dbmgr->fetch_assoc($selectquery);
		$topic = new MTopic($res[0]['id'],$res[0]['name']);
		//$topic->m_questions = MProblem::get_all_problems_in_topic_with_exclusion($topic->m_id);
		return $topic;
	}

	
	public static function get_all_topics()
	{
		global $dbmgr;
		$selectquery = "SELECT * FROM topic";
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_topics = array();
		for ($i=0; $i<$numrows; $i++)
		{
			$all_topics[$i] = new MTopic($res[$i]['id'],$res[$i]['name']);
		}
		return $all_topics;
	}
	
	public static function get_all_topics_in_course($course_id)
	{
		global $dbmgr;
		$selectquery = "SELECT * 
		FROM 12m_class_topic
		WHERE class_id = ".$course_id;
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_topic_ids_in_course = array();
		for ($i=0; $i<$numrows; $i++)
		{
			$all_topic_ids_in_course[$i] = $res[$i]['topic_id'];
		}
		
		$whereclause = "WHERE ";
		for ($i=0; $i<$numrows; $i++)
		{
			$whereclause .= "id = ".$all_topic_ids_in_course[$i];
			if ($i < ($numrows - 1))
			{
				$whereclause .= " OR ";
			}
		}
		
		$selectquery = "SELECT * 
		FROM topic
		".$whereclause;
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_topics_in_course = array();
		for ($i=0; $i<$numrows; $i++)
		{
			$all_topics_in_course[$i] = new MTopic($res[$i]['id'],$res[$i]['name']);
			#TEST ECHO:::::$all_topics_in_course[$i] = $res[$i]['name'];
		}
		
		/*TEST ECHO:::::
		for ($i=0; $i<$numrows; $i++)
		{
			echo $all_topics_in_course[$i];
		}
		*/
		return $all_topics_in_course;
	}
}
Class MTabNav
{
    var $m_selected = 'Home';

	function __construct($selected)
    {
        $this->m_selected = $selected;

        $this->m_pages = array(
        'Selections' => $GLOBALS["DOMAIN"] . 'selections.php', 
        'Problems' => $GLOBALS["DOMAIN"] . 'problems.php', 
        'Statistics' => $GLOBALS["DOMAIN"] . 'stats.php', 
        'Staff Access' => $GLOBALS["DOMAIN"] . 'staff.php'
        );
    }
}
Class MCourseTopicNav
{
    var $m_courses;

	function __construct()
    { 
        $this->m_courses = MCourse::get_all_courses_with_topics();
    }
}

//model containing the course and topic selection information
//this model doesn't do anything, just stores the variables
Class MCTSelect
{
	var $m_selected_course;//get from preferences
	var $m_selected_topics_list;//one or more topics, get from preferences
	var $m_last_activity;//get from preferences
	
	//read in preferences data to set vars
	function __construct()
	{
		global $usrmgr;
		$this->m_selected_course = $usrmgr->m_user->GetPref('selected_course');
		$this->m_selected_topics_list = $usrmgr->m_user->GetPref('selected_topics_list');
		$this->m_last_activity = $usrmgr->m_user->GetPref('last_activity');
	}
}

/*
*****model that will determine the correct information in selections.php (course_or_topic)
<LOGIC>

if (you've selected a course/done a problem in a course in the past 60 days)
	display topic selector for given course
	
else
	display course selector

</LOGIC>
*/

//$this->m_course_or_topic will be 0 for course selector or 1 for topic selector;
//use this variable (along with selected course from MCTSelect if topic selector) to display the right page;
Class MDirector
{
	var $m_expiration_time = 5184000; //60 days in seconds
	var $m_selected_course;//get from MCTSelect
	var $m_last_activity = 0;//get from MCTSelect
	var $m_current_time;//current timestamp
	var $m_course_or_topic = 0;//bool--0 for course selector, 1 for topic selector
	
	function __construct()
	{
		$CTprefs = new MCTSelect();
		$this->m_selected_course = $CTprefs->m_selected_course;
		$this->m_last_activity = $CTprefs->m_last_activity;
		$this->m_current_time = time();
		//vvvvvvv course_or_topic vvvvvvv
		if (($this->m_current_time - $this->m_last_activity) <= $this->m_expiration_time && $this->m_selected_course != Null)
		{
			$this->m_course_or_topic = 1;
		}
	}
}

//read in preferences and pick a problem to output based on course and topic selection and omitted problems
Class MPpicker
{
	var $m_selected_topics_list;//one or more topics (by topic_id), get from preferences
	var $m_omitted_problems_list;//zero or more omitted problems (by prob_id), get from preferences
	var $m_picked_topic;//topic (by topic ID) picked by Ppicker
	var $m_picked_problem;//problem (as MProblem object) picked by Ppicker
	
	function __construct()
	{
		global $usrmgr;
		$this->m_selected_topics_list = $usrmgr->m_user->GetPref('selected_topics_list');
		$this->m_omitted_problems_list = $usrmgr->m_user->GetPref('omitted_problems_list');
	}
	
	//picks a topic (by ID) and a problem in that topic (as an MProblem object)
	function pick_problem()
	{
		//pick random topic from list
		$picked_topic_index = 0;
		$length = count($this->m_selected_topics_list);
		if ($length > 1)
		{
			$picked_topic_index = mt_rand(0,$length - 1);
		}
		$this->m_picked_topic = $this->m_selected_topics_list[$picked_topic_index];
		
		//pick random problem from topic with exclusion
		$picked_problem_index = 0;
		$all_problems = MProblem::get_all_problems_in_topic_with_exclusion($this->m_picked_topic,$this->m_omitted_problems_list);
		$num_problems = count($all_problems);
		if ($num_problems > 1)
		{
			$picked_problem_index = mt_rand(0,$num_problems - 1);
		}
		//echo $picked_problem_index;
		$this->m_picked_problem = $all_problems[$picked_problem_index];
	}
}

?>
