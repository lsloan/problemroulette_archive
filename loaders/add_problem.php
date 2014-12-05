<?php
require_once("setup.php");
// These loaders are disabled and should be removed.
exit(1);
//COURSE TO ADD TO
//WWWWWWWWWWWWWWWWWWWWWWWWWWWW(OPTIONAL)
$course = "Chemistry 130";
//WWWWWWWWWWWWWWWWWWWWWWWWWWWW
$course_id = 10;

$row = 1;
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
if (($handle = fopen("csvProbs/Chem130_Practice_Exam.csv","r")) !== FALSE)
{
	while (($data = fgetcsv($handle,10000,", ")) !== FALSE)
	{
		$num = count($data);
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
		$topic_id = (49 - 100 + $data[0]);
		//$topic_id = (49 - 43 + $data[0]);
		$name = $data[1];
		$url = $data[2];
		$correct = $data[4];
		$ans_count = $data[3];
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWW

		//SEARCH TO SEE IF PROBLEM EXISTS
		$url = $data[2];
		$res = $dbmgr->prepare("select * from problems where url=:url")
			->execute(array(':url',$url))->fetch_all(MYSQLI_ASSOC);
		$num = count($res);
		$p_id = $res[0]['id'];
		$ans_cnt = $res[0]['ans_count'];

		//CREATE NEW PROBLEM
		$new_prob = new MProblem();
		$new_prob->create($name,$url,$ans_count,$correct);

		//GET NEW PROBLEM ID
		$query = "SELECT * FROM problems ORDER BY id DESC";
		$res=$dbmgr->fetch_assoc($query);
		$problem_id = $res[0]['id'];

		//GENERATE BLANK 12M_PROB_ANS FOR PROBLEM
		for ($i=0;$i<$ans_count;$i++)
		{
			$query =
				"INSERT INTO 12m_prob_ans (prob_id, ans_num) ".
				"VALUES (:problem_id, :ans_num)";
			$bindings = array(
				":problem_id"  => $problem_id,
				":ans_num"     => ($i+1));

			$dbmgr->exec_query( $query , $bindings );
		}

		//FILL IN 12M_TOPIC_PROB
		$query =
			"INSERT INTO 12m_topic_prob (topic_id, problem_id) ".
			"VALUES (:topic_id, :problem_id)";
		$bindings = array(
			":topic_id"   => $topic_id,
			":problem_id" => $problem_id);
		$dbmgr->exec_query( $query , $bindings );

		$row++;
	}
	fclose($handle);
}

?>
