<?php

// MODEL OBJECTS
class MProblem
{
	var $m_prob_id;			#ID of problem
	var $m_prob_name;		#Name of problem
	var $m_prob_url;		#URL of problem
	var $m_prob_class_id;	#Class id for problem
	var $m_prob_topic_id;		#topic of problem
	var $m_prob_ans_count;	#Number of answers for problem
	var $m_prob_correct;	#Correct answer choice for problem
	
	function Prob()
	{
		
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
        $res = $dbmgr->fetch_num($query);
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
	
}

Class MCourse
{
    var $id;
    var $name;
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
        $res = $dbmgr->fetch_num($query);
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
}

Class MTopic
{
    var $id;
    var $names;
    var $questions; // Topics have an array of questions
	
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
		".$whereclause."";
		$res = $dbmgr->exec_query($selectquery);
		$res = $dbmgr->fetch_assoc($selectquery);
		$numrows = count($res);
		$all_topics_in_course = array();
		for ($i=0; $i<$numrows; $i++)
		{
			$all_topics_in_course[$i] = new MTopic($res[$i]['id'],$res[$i]['name']);
		}
		return $all_topics_in_course;
	}
}

class MNav
{
    var $m_Courses = Array(); // array of Course objects
}

?>
