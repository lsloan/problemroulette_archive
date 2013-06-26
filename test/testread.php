<?php 

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
		$mysqliCreate = new mysqli("localhost", "root", "", "prexpansion");
		if ($mysqliCreate->connect_errno) {
			echo "Failed to connect to MySQL: (' . $mysqli->connect_errno . ') " . $mysqliCreate->connect_error;
		}
	
		$insertquery = "INSERT INTO problems 
			(class_id,
			topic,
			name,
			url,
			correct,
			ans_count)
		VALUES 
		($prob_class_id,
		$prob_topic,
		$prob_name,
		$prob_url,
		$prob_correct,
		$prob_ans_count
		)";
	
		$CreateQueryResult = $mysqliCreate->query($insertquery) or die($mysqliCreate->error.__LINE__);
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

class VProblemEditReview
{
	#m_model
	
	function __construct($model)
	{
		$this->m_model = $model;
	}
	
	function DumpProblemEditForm()
	{
		$str = '';
		$str.='
			<html>
			<body>
			<form name = "myForm" onsubmit="return validateForm()" method="POST" action=".">
			<table border="1">
			  <TR>
				<TD>Number of answer choices</TD>
				<TD>
				  <input id="ans_count" type="text" name="ans_count" size="1">
				</TD>
			  </TR>
			  <TR>
				<TD>Correct answer choice (1,2,3,...)</TD>
				<TD><input id="correct" type="text" name="correct" size="1"></TD>
			  </TR>
			</table>
			<p><input type="submit" id="submit" value="Submit" name="submit"></p>
			</form>

			<script language="javascript">
			function validateForm()
			{
			var x=document.forms["myForm"]["ans_count"].value;
			var y=document.forms["myForm"]["correct"].value;
			if (x==null || x=="" || y==null || y=="")
			  {
			  alert("Form must be filled out");
			  return false;
			  }
			}
			</script>

			</body>
			</html>
		';
		echo $str;
	}
	
}

$test_prob = new MProblem();

$testProblemEditReview = new VProblemEditReview($test_prob);
$testProblemEditReview->DumpProblemEditForm();

#$row = 1;
#if (($handle = fopen("test.csv", "r")) !== FALSE) {
#	$test_prob = new MProblem();
#    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
#        $num = count($data);
#		
#		$name = $data[0];
#		$url = $data[1];
#		$class_id = $data[2];
#		$topic = $data[3];
#		$ans_count = $data[4];
#		$correct = $data[5];
#		
#		$test_prob->create($name,$url,$class_id,$topic,$ans_count,$correct);
#		
#       $row++;
#    }
#    fclose($handle);
#}



?>