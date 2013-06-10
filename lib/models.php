<?php

// MODEL OBJECTS
class MProblem
{
	var $m_prob_id;			#ID of problem
	var $m_prob_name;		#Name of problem
	var $m_prob_url;		#URL of problem
	var $m_prob_topic_id;	#topic of problem
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
		$res = $dbmgr->exec_query($selectquery);
        $res = $dbmgr->fetch_assoc($selectquery);
		$this->m_prob_id = $prob_id;
		$this->m_prob_name = $res[0]['name'];
		$this->m_prob_url = $res[0]['url'];
		$this->m_prob_topic_id = $res[0]['topic_id'];
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
        $res = $dbmgr->exec_query($insertquery);
        $query = "select * from problems;";
        $res = $dbmgr->fetch_assoc($query);
        print_r($res);
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
	
	public static function get_all_problems_in_topic($topic_id)
	{
		global $dbmgr;
		$selectquery = "SELECT * 
		FROM 12m_topic_prob
		WHERE topic_id = ".$topic_id."";
		$res = $dbmgr->exec_query($selectquery);
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		#$all_prob_ids_in_topic = array();
		$all_problems_in_topic = array();
		for ($i=0; $i<$numrows; $i++)
		{
			#$all_prob_ids_in_topic[$i] = $res[$i]['problem_id'];
			$all_problems_in_topic[$i] = new MProblem($res[$i]['problem_id']);
		}
		
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
		$res = $dbmgr->exec_query($selectquery);
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_problems_in_topic = array();
		for ($i=0; $i<$numrows; $i++)
		{
			#HEY! NO CONSTRUCTOR! HOW ARE YOU CALLING NEW MProblem LIKE THAT?
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
		
		$res = $dbmgr->exec_query($insertquery);
        $query = "select * from class;";
        $res = $dbmgr->fetch_assoc($query);
        print_r($res);
	}
	
	public static function get_all_courses()
	{
		global $dbmgr;
		$selectquery = "SELECT * FROM class";
		$res = $dbmgr->exec_query($selectquery);
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
		$res = $dbmgr->exec_query($selectquery);
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
	
	public static function get_all_topics()
	{
		global $dbmgr;
		$selectquery = "SELECT * FROM topic";
		$res = $dbmgr->exec_query($selectquery);
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
		WHERE class_id = ".$course_id."";
		$res = $dbmgr->exec_query($selectquery);
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
		$res = $dbmgr->exec_query($selectquery);
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

class MTabNav
{
    var $m_selected = 'Home';

	function __construct($selected)
    {
        $this->m_selected = $selected;

        $this->m_pages = array(
        'Home' => $GLOBALS["DOMAIN"] . 'home.php', 
        'Problems' => $GLOBALS["DOMAIN"] . 'problems.php', 
        'Statistics' => $GLOBALS["DOMAIN"] . 'statistics.php', 
        'Staff Access' => $GLOBALS["DOMAIN"] . 'staff.php'
        );
    }
}

class MCourseTopicNav
{
    var $m_courses;

	function __construct()
    { 
        $this->m_courses = MCourse::get_all_courses_with_topics();
    }
}

?>
