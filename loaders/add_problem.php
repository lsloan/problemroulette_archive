<?php
// paths
require_once("./paths.inc.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
$GLOBALS["dbmgr"] = new CDbMgr();
// user manager
require_once( $DIR_LIB."usrmgr.php" );
$GLOBALS["usrmgr"] = new UserManager();
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
$args = GrabAllArgs();
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");

global $dbmgr;
//COURSE TO ADD TO
//WWWWWWWWWWWWWWWWWWWWWWWWWWWW(OPTIONAL)
$course = "Chemistry 130";
//WWWWWWWWWWWWWWWWWWWWWWWWWWWW
$course_id = 10;

$row = 1;
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
if (($handle = fopen("csvProbs/Chem130_Ch6.csv","r")) !== FALSE)
{
	while (($data = fgetcsv($handle,10000,", ")) !== FALSE)
	{
		$num = count($data);
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
		$topic_id = (49 - 100 + $data[0]);
		$name = $data[1];
		$url = $data[2];
		$correct = $data[4];
		$ans_count = $data[3];
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWW

		//SEARCH TO SEE IF PROBLEM EXISTS
		$selectquery = "SELECT * FROM problems WHERE url='".$url."'";
		$res=$dbmgr->fetch_assoc($selectquery);
		$num = count($res);
		$p_id = $res[0]['id'];
		$ans_cnt = $res[0]['ans_count'];

		//if ($num == 0)
		//{
			//CREATE NEW PROBLEM
			$new_prob = new MProblem();
			$new_prob->create($name,$url,$ans_count,$correct);

			//GET NEW PROBLEM ID
			$selectquery = "SELECT * FROM problems ORDER BY id DESC";
			$res=$dbmgr->fetch_assoc($selectquery);
			$problem_id = $res[0]['id'];

			//GENERATE BLANK 12M_PROB_ANS FOR PROBLEM
			for ($i=0;$i<$ans_count;$i++)
			{
				$insertquery = "INSERT INTO 12m_prob_ans VALUES (Null,'".$problem_id."','".($i+1)."','0')";
				$dbmgr->exec_query($insertquery);
			}

			//FILL IN 12M_TOPIC_PROB
			$insertquery = "INSERT INTO 12m_topic_prob VALUES (Null,'".$topic_id."','".$problem_id."')";
			$dbmgr->exec_query($insertquery);
		//}
		/*else
		{
			//UPDATE PROBLEM IN PROBLEM TABLE
			$updatequery = "UPDATE problems SET name=$name, correct=$correct, ans_count=$ans_count WHERE id=$p_id";
			$dbmgr->exec_query($updatequery);

			//UPDATE 12M_TOPIC_PROB IF NECESSARY
			$selectquery = "SELECT * FROM 12m_topic_prob WHERE problem_id=$p_id";
			$res=$dbmgr->fetch_assoc($selectquery);
			if (count($res) > 0)
			{
				$t_id = $res[0]['topic_id'];
				if ($topic_id !== $t_id)
				{
					$updatequery = "UPDATE 12m_topic_prob SET topic_id=$topic_id WHERE problem_id=$p_id";
					$dbmgr->exec_query($updatequery);
				}
			}

			//UPDATE 12M_PROB_ANS IF NECESSARY
			if ($ans_count !== $ans_cnt)
			{
				//DELETE CURRENT ROWS IN 12M_PROB_ANS FOR PROBLEM
				$deletequery = "DELETE FROM 12m_prob_ans WHERE prob_id=$p_id";
				$dbmgr->exec_query($deletequery);

				//CREATE NEW ROWS IN 12M_PROB_ANS FOR PROBLEM
				for ($i=0;$i<$ans_count;$i++)
				{
					$insertquery = "INSERT INTO 12m_prob_ans VALUES (Null,'".$problem_id."','".($i+1)."','0')";
					$dbmgr->exec_query($insertquery);
				}
			}
		}*/

		$row++;
	}
	fclose($handle);
}

?>