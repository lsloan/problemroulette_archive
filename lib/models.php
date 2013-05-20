<?php

// MODEL OBJECTS
class MProblem
{
	var $m_prob_id;			#ID of problem
	var $m_prob_name;		#Name of problem
	var $m_prob_url;		#URL of problem
	var $m_prob_class_id;	#Class id for problem
	var $m_prob_topic;		#topic of problem
	var $m_prob_ans_count;	#Number of answers for problem
	var $m_prob_correct;	#Correct answer choice for problem
	
	function Prob()
	{
		
	}
	
	function create($prob_name, $prob_url, $prob_class_id, $prob_topic, $prob_ans_count, $prob_correct)
	{	
        global $dbmgr; 
		$insertquery = "
        INSERT INTO problems(
            class_id,
			topic,
			name,
			url,
			correct,
			ans_count
        )VALUES(
            '".$prob_class_id."',
            '".$prob_topic."',
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

?>
