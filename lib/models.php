<?php

// MODEL OBJECTS
Class MProblem
{
	var $m_prob_id;			#ID of problem
	var $m_prob_name;		#Name of problem
	var $m_prob_url;		#URL of problem
	var $m_prob_ans_count;	#Number of answers for problem
	var $m_prob_correct;	#Correct answer choice for problem
	var $m_prob_tot_tries;	#Number of times this problem was attempted
	var $m_prob_tot_correct;#Number of times this problem was correctly answered
	var $m_prob_tot_time;	#Cumulative time spent working on this problem
	var $m_prob_solution;	#URL of solution, if supplied
	var $m_prob_topic_name;	#topic name the problem is in (NOTE: what to do when problem can be in multiple topics?)

	function __construct($prob_id = Null)
	{
		if ($prob_id == Null)
		{
			return;
		}
		global $dbmgr;
		$query = "SELECT * FROM problems WHERE id = :id";
		$bindings =array(":id"=>$prob_id);
		$res = $dbmgr->fetch_assoc( $query , $bindings );
		if (! empty($res[0]))
		{
			$this->m_prob_id = $prob_id;
			$this->m_prob_name = $res[0]['name'];
			$this->m_prob_url = $res[0]['url'];
			$this->m_prob_ans_count = $res[0]['ans_count'];
			$this->m_prob_correct = $res[0]['correct'];
			$this->m_prob_tot_tries = $res[0]['tot_tries'];
			$this->m_prob_tot_correct = $res[0]['tot_correct'];
			$this->m_prob_tot_time = $res[0]['tot_time'];
			$this->m_prob_solution = $res[0]['solution'];
		}
		$query = "SELECT name from 12m_topic_prob tp, topic t WHERE tp.problem_id = :pid AND t.id = tp.topic_id";
		$bindings = array(":pid"=>$prob_id);
		$res = $dbmgr->fetch_assoc($query, $bindings);
		if (! empty($res[0]))
		{
			$this->m_prob_topic_name = $res[0]['name'];
		}
	}
	
	function create($prob_name, $prob_url, $prob_ans_count, $prob_correct, $prob_solution='')
	{
		global $dbmgr;
		$query =
			"INSERT INTO problems (name,       url,      correct,      ans_count,      solution) ".
			"VALUES               (:prob_name,:prob_url,:prob_correct,:prob_ans_count,:prob_solution)";
		$bindings = array(
			":prob_name"      => $prob_name,
			":prob_url"       => $prob_url,
			":prob_correct"   => $prob_correct,
			":prob_ans_count" => $prob_ans_count,
			":prob_solution"  => $prob_solution
			);
		$res = $dbmgr->exec_query( $query , $bindings );
	}
	
	function get_ans_submit_count($ans_num)
	{
		if ($this->m_prob_id != Null)
		{
			global $dbmgr;
			$query =
				"SELECT `count` FROM 12m_prob_ans ".
				"WHERE prob_id = :prob_id AND ans_num = :ans_num";
			$bindings = array(
				":prob_id"=>$this->m_prob_id,
				":ans_num"=>$ans_num);
			$res = $dbmgr->fetch_assoc( $query , $bindings );

			if ($res)
			{
				$count = $res[0]['count'];
			}
			else
			{
				$count = 0;
			}
			return $count;
		}
	}
	
	function get_prob_class_id($prob_id)
	{
		global $dbmgr;
		$query = "SELECT c.id from class c, 12m_topic_prob tp, 12m_class_topic ct ".
				  "WHERE tp.problem_id = :prob_id AND ct.topic_id = tp.topic_id AND c.id = ct.class_id";
		$bindings = array( ":prob_id"=>$prob_id);
		$res = $dbmgr->fetch_assoc( $query , $bindings );
		$pid = $res[0]['id'];
		return $pid;
	}

	function get_avg_time()
	{
		if ($this->m_prob_id != Null)
		{
			global $dbmgr;
			$query = "SELECT tot_tries, tot_time FROM problems WHERE id = :id";
			$bindings = array(":id"=>$this->m_prob_id);
			$res = $dbmgr->fetch_assoc( $query , $bindings );
			$tot_tries = $res[0]['tot_tries'];
			$tot_time = $res[0]['tot_time'];
			$avg_time = $tot_time/$tot_tries;
			
			return round($avg_time,1);
		}
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
	
    public static function update_problem_name($prob_id=Null, $new_prob_name=Null)
    {
        global $dbmgr;
        $query = "UPDATE problems SET name = :name WHERE id = :id";
        $bindings = array(":name" => $new_prob_name, ":id"   => $prob_id);
        $dbmgr->exec_query( $query , $bindings );
    }

    public static function update_problem_url($prob_id=Null, $new_prob_url=Null)
    {
        global $dbmgr;
        $query = "UPDATE problems SET url = :url WHERE id = :id";
        $bindings = array(":url" => $new_prob_url, ":id" => $prob_id);
        $dbmgr->exec_query( $query, $bindings );
    }

    public static function update_problem_num_ans($prob_id=Null, $new_prob_num_ans=Null)
    {
        global $dbmgr;
        $query = "UPDATE problems SET ans_count = :new_prob_num_ans WHERE id = :id";
        $bindings = array(":new_prob_num_ans" => $new_prob_num_ans, ":id" => $prob_id);
        $dbmgr->exec_query( $query , $bindings );
    }

    public static function update_problem_cor_ans($prob_id=Null, $new_prob_cor_ans=Null)
    {
        global $dbmgr;
        $query = "UPDATE problems SET correct = :correct WHERE id = :id";
        $bindings = array(":correct" => $new_prob_cor_ans, ":id" => $prob_id);
        $dbmgr->exec_query( $query , $bindings );
    }

    public static function update_problem_sol_url($prob_id=Null, $new_prob_sol_url=Null)
    {
        global $dbmgr;
        $query = "UPDATE problems SET solution = :solution WHERE id = :id";
        $bindings = array(":solution" => $new_prob_sol_url, ":id" => $prob_id);
        $dbmgr->exec_query( $query , $bindings );
    }

    public static function update_problem($prob_id, $name, $url, $num_ans, $cor_ans, $sol_url, $topic_id)
    {
    	global $dbmgr;
    	$query = "UPDATE problems SET name = :name, url = :url, correct = :cor_ans, ans_count = :num_ans, solution = :sol_url " .
    			 " WHERE id = :id";
    	$bindings = array(":name" => $name, ":url" => $url, ":cor_ans" => $cor_ans,
    					  ":num_ans" => $num_ans, ":sol_url" => $sol_url, ":id" => $prob_id);
    	$dbmgr->exec_query($query, $bindings);
    	$query = "UPDATE 12m_topic_prob SET topic_id = :topic_id WHERE problem_id = :prob_id";
    	$bindings = array(":topic_id"=>$topic_id, ":prob_id"=>$prob_id);
    	$dbmgr->exec_query($query, $bindings);
    }

	//for $exclusion: input 0 or nothing for no exclusion; input 1 or true for exclusion
	//for $by_id: input 0 or nothing to return problem objects; input 1 or true to output problem ids
	public static function get_all_problems_in_topic_with_exclusion($topic_id,$exclusion = Null,$by_id = Null)
	{
		global $usrmgr;
		global $dbmgr;
		if (is_array($topic_id))
		{
			$topic_id = $topic_id[0];
		}
		$where_clause = array();
		$bindings = array();

		if (isset($topic_id))
		{
			$selectquery = "SELECT * FROM 12m_topic_prob WHERE topic_id = :topic_id ";
			$bindings[":topic_id"]= $topic_id;

			if ($exclusion == true || $exclusion == 1)
			{
				//get user_id
				$user_id = $usrmgr->m_user->id;

				$selectquery .=
					" AND problem_id NOT IN ".
					"(SELECT problem_id from omitted_problems ".
					"where user_id=:user_id and topic_id=:topic_id)";
				$bindings[":user_id"] = $user_id;
			}
			$res = $dbmgr->fetch_assoc($selectquery,$bindings);
			$numrows = count($res);
			
			//return problem ids
			if ($by_id == true || $by_id == 1)
			{
				$all_problem_ids_in_topic = array();
				for ($i=0; $i<$numrows; $i++)
				{
					$all_problem_ids_in_topic[$i] = $res[$i]['problem_id'];
				}
				//$all_problem_ids_in_topic = pg_fetch_all($res)['problem_id'];
				return $all_problem_ids_in_topic;
			}
			
			//return problem objects
			$all_problems_in_topic = array();
			for ($i=0; $i<$numrows; $i++)
			{
				$all_problems_in_topic[$i] = new MProblem($res[$i]['problem_id']);
			}
			return $all_problems_in_topic;
		}
		else
		{
			return Null;
		}
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
		$query = "INSERT INTO class(name) VALUES (:name)";
		$bindings = array(":name" => $name);
		$dbmgr->exec_query( $query , $bindings );
	}
	
	public static function get_course_by_id($id)
	{
		global $dbmgr;
		$query = "SELECT * FROM class WHERE id = :id";
		$bindings = array(":id" => $id);
		$res = $dbmgr->fetch_assoc( $query , $bindings );
		$course = new MCourse($res[0]['id'],$res[0]['name']);
		$course->m_topics = MTopic::get_all_topics_in_course($course->m_id);
		return $course;
	}
	
	public static function get_all_courses()
	{
		global $dbmgr;
		$query = "SELECT * FROM class";
		$res = $dbmgr->fetch_assoc( $query );
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
		$res = $dbmgr->fetch_assoc("SELECT * FROM class");
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
	
	static function alphabetize($a,$b)
	{
		$a1 = strtolower($a->m_name);
		$b1 = strtolower($b->m_name);
		if ($a1 == $b1){return 0;}
		return ($a1 > $b1) ? +1 : -1;
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
		if (is_array($id))
		{
			$id = $id[0];
		}
		$query = "SELECT * FROM topic WHERE id = :id";
		$bindings = array(":id" => $id);
		$res = $dbmgr->fetch_assoc( $query , $bindings );
		$topic = new MTopic($res[0]['id'],$res[0]['name']);
		//$topic->m_questions = MProblem::get_all_problems_in_topic_with_exclusion($topic->m_id);
		return $topic;
	}
	
	public static function get_all_topics()
	{
		global $dbmgr;
		$query = "SELECT * FROM topic";
		$res = $dbmgr->fetch_assoc( $query );
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
		if ($course_id !== Null)
		{
			$query =
				"SELECT t.* FROM topic t ".
				"INNER JOIN 12m_class_topic tc ON t.id = tc.topic_id ".
				"WHERE tc.class_id = :course_id";
			$bindings = array(":course_id" => $course_id);
			$res = $dbmgr->fetch_assoc( $query , $bindings );
		}
		else
		{
			echo "Error! please contact mcmills@umich.edu.";
		}

		// TODO: Handle missing course case possibly as distinct from the zero-topic case
		$numrows = count($res);
		$all_topics_in_course = array();
		for ($i=0; $i<$numrows; $i++)
		{
			$all_topics_in_course[$i] = new MTopic($res[$i]['id'],$res[$i]['name']);
		}
		//UNCOMMENT TO ALPHABETIZE TOPICS
		//usort($all_topics_in_course, array('MTopic','alphabetize'));
		return $all_topics_in_course;
	}
	
	static function alphabetize($a,$b)
	{
		$a1 = strtolower($a->m_name);
		$b1 = strtolower($b->m_name);
		if ($a1 == $b1){return 0;}
		return ($a1 > $b1) ? +1 : -1;
	}
}
Class MTabNav
{
    var $m_selected = 'Home';

	function __construct($selected)
    {
        $this->m_selected = $selected;

		global $usrmgr;
		
		if ($usrmgr->m_user->staff == 1)
		{
			$this->m_pages = array(
			'Selections' => $GLOBALS["DOMAIN"] . 'selections.php', 
			'Problems' => $GLOBALS["DOMAIN"] . 'problems.php', 
			'My Summary' => $GLOBALS["DOMAIN"] . 'stats.php', 
			'Problem Library' => $GLOBALS["DOMAIN"] . 'problem_library.php',
			'Student Performance' => $GLOBALS["DOMAIN"] . 'student_performance.php'
			);
		}
		else
		{
			$this->m_pages = array(
			'Selections' => $GLOBALS["DOMAIN"] . 'selections.php', 
			'Problems' => $GLOBALS["DOMAIN"] . 'problems.php', 
			'My Summary' => $GLOBALS["DOMAIN"] . 'stats.php' 
			);
		}
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
	var $m_selected_topics_list;//one or more topics (By ID), get from preferences
	var $m_omitted_problems_list;//zero or more omitted problems (by prob_id), get from preferences
								//^^^^^Associative array (omitted_problems_list[topic_id] = array of omitted problems in topic)
	var $m_remaining_problems_in_topic_list;//how many problems are left in a given topic after leaving out omitted problems
	var $m_total_problems_in_topic_list;//how many problems are in topic before omitting problems
	var $m_last_activity;//get from preferences
	
	//read in preferences data to set vars
	function __construct()
	{
		global $usrmgr;
		$this->m_selected_course = $usrmgr->m_user->GetPref('selected_course');
		$this->m_selected_topics_list = $usrmgr->m_user->GetPref('selected_topics_list');
		$num_selected_topics = count($this->m_selected_topics_list);

		//get user_id
		$user_id = $usrmgr->m_user->id;

		for ($i=0; $i<$num_selected_topics; $i++)
		{
			$topic_id = $this->m_selected_topics_list[$i];
			$omitted_problem = new OmittedProblem($user_id, $topic_id);
			$this->m_omitted_problems_list[$topic_id] = $omitted_problem->find();
		}
		//^^^taken care of above^^^//$this->m_omitted_problems_list = $usrmgr->m_user->GetPref('omitted_problems_list');
		$this->m_last_activity = $usrmgr->m_user->GetPref('last_activity');
		
		for ($i=0;$i<count($this->m_selected_topics_list);$i++)
		{
			$topic_id = $this->m_selected_topics_list[$i];
			$remaining_problems = MProblem::get_all_problems_in_topic_with_exclusion($topic_id,$this->m_omitted_problems_list[$topic_id]);
			$total_problems = MProblem::get_all_problems_in_topic_with_exclusion($topic_id);
			$this->m_remaining_problems_in_topic_list[$i] = count($remaining_problems);
			$this->m_total_problems_in_topic_list[$i] = count($total_problems);
		}
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
    # redirects
    var $target_page;

     # selector page
    var $topic_selected;
    var $course_selected;
    # var course_or_topic;

    # problems page
    var $state_response; # 0 = working on problem, 1 = completed problem

    # stats page
    var $stats_course_selected;
    var $stats_topic_selected;

    function __construct($args)
    {
        $this->args = $args;
    }

	public static function safecheck_CT_selected()
	{
		global $usrmgr;
		//Set selected_course or selected_topics_list to Null if it is currently a string (instead of a number)
		if (intval($usrmgr->m_user->GetPref('selected_course') == 0))
		{
			$usrmgr->m_user->SetPref('selected_course',Null);
		}
		if (is_array($usrmgr->m_user->GetPref('selected_topics_list')))
		{
			if (min(array_map("intval",$usrmgr->m_user->GetPref('selected_topics_list'))) == 0)
			{
				$usrmgr->m_user->SetPref('selected_topics_list',Null);
			}
		}
		else
		{
			if (intval($usrmgr->m_user->GetPref('selected_course') == 0))
			{
				$usrmgr->m_user->SetPref('selected_course',Null);
			}
		}
	}
	
	public static function post2sess_CT_history()
	{
		global $usrmgr;
		if (isset($_POST['dropdown_course']))
		{
			//get selected course from POST and set preference
			$selected_course_id = $_POST['dropdown_course'];
			$_SESSION['dropdown_history_course'] = $selected_course_id;
			$usrmgr->m_user->SetPref('dropdown_history_course',$selected_course_id);
			$_SESSION['dropdown_history_topic'] = 'all';
			$usrmgr->m_user->SetPref('dropdown_history_topic','all');
			}

		elseif (isset($_POST['dropdown_topic']))
		{
			//get selected topic from POST and set preference
			$selected_topic_id = $_POST['dropdown_topic'];
			$_SESSION['dropdown_history_topic'] = $selected_topic_id;
			$usrmgr->m_user->SetPref('dropdown_history_topic',$selected_topic_id);
		}
	}
	
	public static function get_selected_course_history()
	{
		global $usrmgr;
		if (isset($_SESSION['dropdown_history_course']))
		{
			$selected_course_id = $_SESSION['dropdown_history_course'];
		}
		else
		{
			$selected_course_id = 'all';
		}
		return $selected_course_id;
	}
	
	public static function get_selected_topic_history()
	{
		global $usrmgr;
		if (isset($_SESSION['dropdown_history_topic']))
		{
			$selected_topic_id = $_SESSION['dropdown_history_topic'];
		}
		else
		{
			$selected_topic_id = 'all';
		}
		return $selected_topic_id;
	}
	
	public static function get_problem_library_list($selected_course_id,$selected_topic_id)
	{
		if ($selected_course_id !== Null && $selected_course_id !== 'all')
		{
			if ($selected_topic_id !== Null && $selected_topic_id !== 'all')
			{
				//12m_topic_prob -> list of probs
				$problem_library_list = MProblem::get_all_problems_in_topic_with_exclusion($selected_topic_id);
			}
			else
			{
				//12m_class_topic -> list of topics -> 12m_topic_prob -> list of probs
				$topic_list = MTopic::get_all_topics_in_course($selected_course_id);
				$problem_library_list = array();
				for ($i=0;$i<count($topic_list);$i++)
				{
					$temp = MProblem::get_all_problems_in_topic_with_exclusion($topic_list[$i]->m_id);
					for ($j=0;$j<count($temp);$j++)
					{
						$problem_library_list[] = $temp[$j];
					}
				}
			}
		}
		else
		{
			//no problems
			$problem_library_list = Null;
		}
		return $problem_library_list;
	}
	
	//Add a course to the database ($course_name should be a string)
	public static function add_course_to_db($course_name)
	{
		global $dbmgr;
		$query = "INSERT INTO class (name) VALUES (:name)";
		$bindings = array(":name" => $course_name);
		$dbmgr->exec_query( $query , $bindings );
	}
	
	public static function add_topic_to_db($course_id, $topic_name)
	{
		global $dbmgr;
		$query = "INSERT INTO topic (name) VALUES (:topic_name)";
		$bindings = array(":topic_name" => $topic_name);
		//insert new topic
		$dbmgr->exec_query( $query , $bindings );
		//get new topic id
		$query = "SELECT * FROM topic ORDER BY id DESC";
		$res=$dbmgr->fetch_assoc( $query );
		$topic_id = $res[0]['id'];
		//insert into 12m_class_topic
		$query =
			"INSERT INTO 12m_class_topic (class_id, topic_id) ".
			"VALUES (:course_id,:topic_id)";
		$bindings = array(":course_id" => $course_id, ":topic_id" => $topic_id);
		$dbmgr->exec_query( $query , $bindings );
	}
	
    public static function add_problem_to_db($topic_id, $prob_name, $prob_url, $num_ans, $cor_ans, $sol_url="")
    {
        global $dbmgr;
        //CREATE NEW PROBLEM OBJECT
        $new_prob = new MProblem();
        $new_prob->create($prob_name, $prob_url, $num_ans, $cor_ans, $sol_url);

        //GET NEW PROBLEM ID
        $selectquery = "SELECT * FROM problems ORDER BY id DESC";
        $res=$dbmgr->fetch_assoc( $selectquery );
        $problem_id = $res[0]['id'];

        //GENERATE BLANK 12M_PROB_ANS FOR PROBLEM
        for ($i=0;$i<$num_ans;$i++)
        {
            $query =
                "INSERT INTO 12m_prob_ans (prob_id, ans_num) ".
                "VALUES (:problem_id, :ans_num)";
            $bindings = array(":problem_id" => $problem_id, ":ans_num" => ($i+1));
            $dbmgr->exec_query( $query , $bindings );
        }

        //FILL IN 12M_TOPIC_PROB
        $query =
            "INSERT INTO 12m_topic_prob (topic_id, problem_id) ".
            "VALUES (:topic_id, :problem_id)";
        $bindings = array(":topic_id" => $topic_id, ":problem_id" => $problem_id);
        $dbmgr->exec_query( $query , $bindings );
    }

    function init_selector()
    {
        # peal out the POST data (course/topic selected)
        # reset user course/topic if time > X elapsed
        # user has course?
        # user has topic?
        # direct to selector page
    }

    function init_problems()
    {
        # peal out the POST data (

    }

    function init_stats()
    {
        
    }
}

//read in preferences and pick a problem to output based on course and topic selection and omitted problems
Class MPpicker
{
	var $m_selected_topics_list;//one or more topics (by topic_id), get from preferences
	var $m_omitted_problems_list;//zero or more omitted problems (by prob_id), get from preferences
								//^^^^^Associative array (omitted_problems_list[topic_id] = array of omitted problems in topic)
	var $m_remaining_problems_in_topic_list;//how many problems are left in a given topic after leaving out omitted problems
	var $m_total_problems_in_topic_list;//how many problems are in topic before omitting problems
	var $m_remaining_selected_topics_list = array();//selected_topics_list after removing topics with zero problems left
	var $m_picked_topic;//topic (by topic ID) picked by Ppicker
	var $m_picked_problem = Null;//problem (as MProblem object) picked by Ppicker
	
	function __construct()
	{
		global $usrmgr;
		$this->m_selected_topics_list = $usrmgr->m_user->GetPref('selected_topics_list');
		
		//get user_id
		$user_id = $usrmgr->m_user->id;

		if (is_array($this->m_selected_topics_list))
		{
			for ($i=0; $i<count($this->m_selected_topics_list); $i++)
			{
				$topic_id = $this->m_selected_topics_list[$i];
				$omitted_problem = new OmittedProblem($user_id, $topic_id);
				$this->m_omitted_problems_list[$topic_id] = $omitted_problem->find();

				$remaining_problems = MProblem::get_all_problems_in_topic_with_exclusion($topic_id,$this->m_omitted_problems_list[$topic_id]);
				$total_problems = MProblem::get_all_problems_in_topic_with_exclusion($topic_id);
				$this->m_remaining_problems_in_topic_list[$i] = count($remaining_problems);
				$this->m_total_problems_in_topic_list[$i] = count($total_problems);
				
				if ($this->m_remaining_problems_in_topic_list[$i] > 0)
				{
					array_push($this->m_remaining_selected_topics_list, $topic_id);
				}
			}
		}
		else
		{
			$topic_id = $this->m_selected_topics_list;
			$omitted_problem = new OmittedProblem($user_id, $topic_id);
			$this->m_omitted_problems_list[intval($topic_id)] = $omitted_problem->find();
			$remaining_problems = MProblem::get_all_problems_in_topic_with_exclusion($topic_id,$this->m_omitted_problems_list[intval($topic_id)]);
			$total_problems = MProblem::get_all_problems_in_topic_with_exclusion($topic_id);
			$this->m_remaining_problems_in_topic_list = count($remaining_problems);
			$this->m_total_problems_in_topic_list = count($total_problems);
		
			if ($this->m_remaining_problems_in_topic_list > 0)
			{
				array_push($this->m_remaining_selected_topics_list, $topic_id);
			}
		}
	}
	
	//picks a topic (by ID) and a problem in that topic (as an MProblem object)
	function pick_problem()
	{
		//pick random topic from list
		$picked_topic_index = 0;
		$length = count($this->m_remaining_selected_topics_list);
		if ($length > 0)
		{
			if ($length > 1)
			{
				$picked_topic_index = mt_rand(0,$length - 1);
			}
			$this->m_picked_topic = $this->m_remaining_selected_topics_list[$picked_topic_index];
			
			//pick random problem from topic with exclusion
			$picked_problem_index = 0;
			$topic_id = $this->m_picked_topic;
			$all_problems = MProblem::get_all_problems_in_topic_with_exclusion($topic_id,$this->m_omitted_problems_list[intval($topic_id)]);
			$num_problems = count($all_problems);
			if ($num_problems >= 1)
			{
				$picked_problem_index = mt_rand(0,$num_problems - 1);
				$this->m_picked_problem = $all_problems[$picked_problem_index];

			}
			//echo $picked_problem_index;
			//$this->m_picked_problem = $all_problems[$picked_problem_index];
		}
	}
}

//class to handle the updating of the database when a student submits an answer
Class MResponse
{
	var $m_maximum_recorded_time = 1800;//responses with solve times above this value will not be recorded in 'stats' and 'problems', but will be recorded in 'responses' and '12m_prob_ans' (the responses will be recorded, but this submission will not contribute to total time/total problems for a student's aggregate or a problem's aggregate (to prevent large average times)
	var $m_start_time;//timestamp of when student began problem
	var $m_end_time;//timestamp of when student submitted answer
	var $m_user_id;//integer (unique) user id
	var $m_problem_id;//integer (unique) problem id
	var $m_student_answer;//integer (1=A, 2=B, 3,4,...) student answer value
	
	function __construct($start_time, $end_time, $user_id, $problem_id, $student_answer)
	{
		$this->m_start_time = $start_time;
		$this->m_end_time = $end_time;
		$this->m_user_id = $user_id;
		$this->m_problem_id = $problem_id;
		$this->m_student_answer = $student_answer;

		$this->verify_problem_id();
	}
	
	function update_responses()
	{
		$this->verify_problem_id();

		global $dbmgr;
		$query =
			"INSERT INTO responses (start_time,   end_time,  user_id,  prob_id,  answer) ".
			"VALUES                (:start_time, :end_time, :user_id, :prob_id, :answer)";
		$bindings = array(
			":start_time" => date('Y-m-d H:i:s',$this->m_start_time),
			":end_time"   => date('Y-m-d H:i:s',$this->m_end_time),
			":user_id"    => $this->m_user_id,
			":prob_id"    => $this->m_problem_id,
			":answer"     => $this->m_student_answer
			);
		$dbmgr->exec_query( $query, $bindings );
	}
	
	function update_skips()
	{
		$this->verify_problem_id();

		global $dbmgr;
		$query =
			"INSERT INTO responses (  start_time,  end_time,  user_id,  prob_id,  answer) ".
			"VALUES                ( :start_time, :end_time, :user_id, :prob_id, :answer)";
		$bindings = array(
			":start_time" => date('Y-m-d H:i:s',$this->m_start_time),
			":end_time"   => date('Y-m-d H:i:s',$this->m_end_time),
			":user_id"    => $this->m_user_id,
			":prob_id"    => $this->m_problem_id,
			"answer"      => '0'
			);
		$dbmgr->exec_query( $query , $bindings );
	}
	
	function update_stats()
	{
		global $dbmgr;
		
		$solve_time = $this->m_end_time - $this->m_start_time;
		
		//determine if student answer is correct
		$current_problem = new MProblem($this->m_problem_id);
		$current_problem_answer = $current_problem->m_prob_correct;
		$student_answered_correctly = 0;
		if ($current_problem_answer == $this->m_student_answer)
		{
			$student_answered_correctly = 1;
		}
		
		//update stats table
		if ($solve_time <= $this->m_maximum_recorded_time)
		{
			$query =
				"UPDATE stats SET ".
				"tot_tries = tot_tries + 1 , ".
				"tot_correct = tot_correct + :student_answered_correctly, ".
				"tot_time = tot_time + :solve_time ".
				"WHERE user_id = :user_id";
			$bindings = array(
				":student_answered_correctly" => $student_answered_correctly,
				":solve_time"                 => $solve_time,
				":user_id"                    => $this->m_user_id);
			$dbmgr->exec_query( $query , $bindings );
		}
	}

	function update_problems()
	{
		global $dbmgr;
		
		$solve_time = $this->m_end_time - $this->m_start_time;
		
		//determine if student answer is correct
		$current_problem = new MProblem($this->m_problem_id);
		$current_problem_answer = $current_problem->m_prob_correct;
		$student_answered_correctly = 0;
		if ($current_problem_answer == $this->m_student_answer)
		{
			$student_answered_correctly = 1;
		}
		
		//update stats table
		if ($solve_time <= $this->m_maximum_recorded_time)
		{
			$query =
				"UPDATE problems SET ".
				"tot_tries = tot_tries + 1, ".
				"tot_correct = tot_correct + :student_answered_correctly, ".
				"tot_time = tot_time + :solve_time ".
				"WHERE id = :m_problem_id";
			$bindings = array(
				":student_answered_correctly" => $student_answered_correctly,
				":solve_time"                 => $solve_time,
				":m_problem_id"               => $this->m_problem_id);
			$dbmgr->exec_query( $query , $bindings );
		}
	}
	
	function update_12m_prob_ans()
	{
		global $dbmgr;
		$query =
			"UPDATE 12m_prob_ans SET count = count + 1 ".
			"WHERE prob_id = :prob_id AND ans_num = :ans_num ";
		$bindings = array(":prob_id" => $this->m_problem_id, "ans_num" => $this->m_student_answer);
		$dbmgr->exec_query( $query , $bindings );
	}

	function verify_problem_id()
	{
		if ($this->m_problem_id == Null)
		{
			error_log("ERROR in saving/updating MResponse: Null value for 'm_problem_id'");
			$backtrace = '';
			foreach (debug_backtrace() as $key => $value) {
					$backtrace .= "  -- {$key}: {$value['class']}.{$value['function']} ({$value['file']} at {$value['line']})";
			}
			error_log($backtrace);
		}
		elseif ($this->m_problem_id < 1)
		{
			error_log("ERROR in saving/updating MResponse: Invalid value for 'm_problem_id: {$this->m_problem_id}'");
			$backtrace = '';
			foreach (debug_backtrace() as $key => $value) {
					$backtrace .= "  -- {$key}: {$value['class']}.{$value['function']} ({$value['file']} at {$value['line']})";
			}
			error_log($backtrace);
		}
	}
	
}

Class MUserSummary
{
	//<OVERALL STATISTICS>
	var $m_tot_tries = 0;
	var $m_tot_correct = 0;
	var $m_tot_time = 0;
	var $m_num_users = 0;
	//</OVERALL STATSISTICS>
	
	//<HISTORY>
	var $m_problem_list = Array(); //NOTE: array of MProblem class (you get [correct answer/name/URL] from this)
	var $m_student_answer_list = Array(); //numeric format (1,2,...)
	var $m_start_time_list = Array(); //datetime format
	var $m_end_time_list = Array(); //datetime format
	var $m_solve_time_list = Array(); //solve time (in seconds)
	var $m_user_id_list = Array(); //list of user ids
	//</HISTORY>
	
	var $m_problems_list_id;//array of problem IDs (only use these IDs)
	
	function __construct($problems_list_id = Null, $all_users = 0)
	{
		global $usrmgr;
		global $dbmgr;
		
		$this->m_problems_list_id = $problems_list_id;
		$num_problems_in_selection = count($this->m_problems_list_id);

		$user_id = $usrmgr->m_user->id;
		
		//<GET RESPONSES>
		if ($this->m_problems_list_id == 'blank')
		{
			$num_responses = 0;
		}
		else
		{
			$bindings=array();
			$whole_thing=array(
				"select"=>array(
					"all"=>"SELECT * FROM responses WHERE answer <> 0",
					"user"=>"SELECT * FROM responses WHERE user_id=:user_id AND answer <> 0",
				),
				"numprob"=>array(
					"all"=>"SELECT COUNT(*) FROM responses WHERE answer <> 0",
					"user"=>"SELECT COUNT(*) FROM responses WHERE user_id=:user_id AND answer <> 0",
				),
				"numuser"=>array(
					"all"=>"SELECT COUNT(DISTINCT user_id) FROM responses WHERE answer <> 0",
					"user"=>"SELECT COUNT(DISTINCT user_id) FROM responses WHERE user_id=:user_id AND answer <> 0",
				),
			);
			$type = "all";
			if ($all_users == '' || $all_users == Null)
			{
				if ($all_users == 0)
				{
					$type = "user";
					$bindings[":user_id"]=$user_id;
				}
			}
			elseif ($all_users !== 0)
			{
				$search_user_id = 0;
				$search_username = $all_users;
				$type = "user";
				$res = $dbmgr->fetch_assoc("SELECT id FROM user WHERE username =:username",array(":username"=>$search_username));
				if (count($res) > 0)
				{
					$bindings[":user_id"]=$res[0]["id"];

				}
				else
				{
					$type = "user";
					$bindings[":user_id"]=-1;
				}
			}
			else
			{
					$type = "user";
					$bindings[":user_id"]=$user_id;
			}
			
			if ($this->m_problems_list_id != Null)
			{
				$additional_clause = array();
				for ($i=0; $i<$num_problems_in_selection; $i++)
				{
					$additional_clause[] = "prob_id=:prob_id_$i";
					$bindings[":prob_id_$i"]= $this->m_problems_list_id[$i];
				}
				$additional_clause = " AND ( " . implode( " OR " , $additional_clause ) . " ) ";
			}
			else
			{
				$additional_clause = "";
			}
			foreach (array("numprob","numuser") AS $key ) {
				$res = $dbmgr->fetch_num($whole_thing[$key][$type].$additional_clause,$bindings);
				$whole_thing[$key]["count"] =  $res[0][0];
			}

			$this->m_tot_tries = $whole_thing["numprob"]["count"];
			$this->m_num_users = $whole_thing["numuser"]["count"];
			
			if ($all_users == '' || $all_users == Null)
			{
				if ($all_users !== 0)
				{
					return;
				}
			}
			$res = $dbmgr->fetch_num($whole_thing["select"][$type].$additional_clause,$bindings);
			$num_res = count($res);
		}

		$res = $dbmgr->fetch_assoc($whole_thing["select"][$type].$additional_clause,$bindings);

		if ($num_res < 1)
		{
			//$this->m_tot_tries = 0;
			$this->m_tot_time = 0;
			$this->m_tot_correct = 0;
		}
		
		for ($i=0;$i<$num_res;$i++)
		{
			$this->m_problem_list[$i] = new MProblem($res[$i]['prob_id']);
			$this->m_student_answer_list[$i] = $res[$i]['answer'];
			$this->m_start_time_list[$i] = $res[$i]['start_time'];
			$this->m_end_time_list[$i] = $res[$i]['end_time'];
			$this->m_user_id_list[$i] = $res[$i]['user_id'];
            date_default_timezone_set('America/New_York');
			$this->m_solve_time_list[$i] = strtotime($this->m_end_time_list[$i]) - strtotime($this->m_start_time_list[$i]);
			
			//$this->m_tot_tries += 1;
			$this->m_tot_time += $this->m_solve_time_list[$i];
			if ($this->m_student_answer_list[$i] == $this->m_problem_list[$i]->m_prob_correct)
			{
				$this->m_tot_correct += 1;
			}
		}	
		//</GET RESPONSES>
	}
	
}

/**
* 
*/
class OmittedProblem
{
	var $m_user_id;
	var $m_topic_id;
	var $m_problem_id;
	
	function __construct($user_id, $topic_id = NULL, $problem_id = NULL)
	{
		$this->m_user_id = $user_id;
		$this->m_topic_id = $topic_id;
		$this->m_problem_id = $problem_id;
	}

	function find() {
		global $dbmgr;

		$query = "SELECT problem_id FROM omitted_problems WHERE ";

		$conditions = array('user_id = ?');
		$params = array($this->m_user_id);

		if ($this->m_topic_id) {
			$conditions[] = 'topic_id = ?';
			$params[] = $this->m_topic_id;
			if ($this->m_problem_id) {
				$conditions[] = 'problem_id = ?';
				$params[] = $this->m_problem_id;
			}
		}

		$query .= implode(' AND ', $conditions);
		return $dbmgr->fetch_column($query, $params, 0);
	}

	function count() {
		global $dbmgr;

		$query = "SELECT COUNT(*) FROM omitted_problems WHERE ";

		$conditions = array('user_id = ?');
		$params = array($this->m_user_id);

		if ($this->m_topic_id) {
			$conditions[] = "topic_id = ?";
			$params[] = $this->m_topic_id;
			if ($this->m_problem_id) {
				$conditions[] = "problem_id = ?";
				$params[] = $this->m_problem_id;
			}
		}

		$query .= implode(' AND ', $conditions);
		$res = $dbmgr->fetch_num($query, $params);
		$count = $res[0][0];

		return $count;
	}

	function add() {
		global $dbmgr;

		$query = "INSERT INTO omitted_problems (user_id, topic_id, problem_id) VALUES (?, ?, ?)";
		$params = array($this->m_user_id, $this->m_topic_id, $this->m_problem_id);

		$dbmgr->exec_query($query, $params);
	}

	function remove() {
		global $dbmgr;

		if ($this->m_user_id) {
			$query = "DELETE FROM omitted_problems WHERE ";
			$conditions = array('user_id = ?');
			$params = array($this->m_user_id);

			if ($this->m_topic_id) {
				$conditions[] = "topic_id = ?";
				$params[] = $this->m_topic_id;
				if ($this->m_problem_id) {
					$conditions[] = "problem_id = ?";
					$params[] = $this->m_problem_id;
				}
			}

			$query .= implode(' AND ', $conditions);
			$dbmgr->exec_query($query, $params);
		}
	}

}

?>
