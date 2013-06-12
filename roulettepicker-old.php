<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!--This page contains the majority of inner workings of Problem Roulette-->
   
<html>
<head>
<?php
//Set login parameters for phpMyAdmin
$phpMyAdminHost = 'localhost';
$phpMyAdminUser = 'root';
$phpMyAdminPassword = 'password';
$phpMyAdminDatabase = 'problemroulette';

//Start session
session_start();

$exam = $_GET['exam'];
$examPretend = $exam;
$MaximumTime = 3600;

$substr = 'all';
$pos = strpos($exam, $substr);

//Determine what exam to pull questions from
if ($pos !== false){
	$examPrefix = substr($exam, 0, $pos);
	$examSelected = mt_rand(1,4);
	if ($examSelected == 1) {$examSuffix = 'm1';}
	if ($examSelected == 2) {$examSuffix = 'm2';}
	if ($examSelected == 3) {$examSuffix = 'm3';}
	if ($examSelected == 4) {$examSuffix = 'f';}
	$examPretend = $examPrefix . $examSuffix;
}

//If user has already submitted an answer, gets information from SQL and updates relevant tables
if(isset($_POST['submit'])) {
	$exam = $_POST['exam'];
	$examPretend = $_POST['examPretend'];
	if(isset($_SERVER['REMOTE_USER'])){
		$uniquename = $_SERVER['REMOTE_USER'];
	}
	else{
		$uniquename = 'anonymous';
	}
    $studentAnswer = $_POST['studentAnswer'];
	$Name = $_POST['Name'];
	$myurl = $_POST['problemURL'];
	$correctAnswer = $_POST['problemAnswer'];
	$A = $_POST['A'];
	$B = $_POST['B'];
	$C = $_POST['C'];
	$D = $_POST['D'];
	$E = $_POST['E'];
	$GiveUp = $_POST['GiveUp'];
	$TotalTries = $_POST['TotalTries'];
	$TotalTimedTries = $_POST['TotalTimedTries'];
	$TotalCorrect = $_POST['TotalCorrect'];
	$TotalTime = $_POST['TotalTime'];
	$AverageTime = $_POST['AverageTime'];
	$StartTime = $_POST['StartTime'];
	
	$EndTime = time();
	
	$$studentAnswer ++;
	$TotalTries ++;
	
	$Correct = 'N';
	if (strcasecmp($studentAnswer,$correctAnswer) == 0){
		$TotalCorrect ++;
		$Correct = 'Y';
	}
	$Accuracy = $TotalCorrect / $TotalTries;
	$StudentTime = $EndTime - $StartTime;
	if ($StudentTime < $MaximumTime){
		$TotalTimedTries ++;
		$TotalTime += $StudentTime;
	}
	$AverageTime = $TotalTime / $TotalTimedTries;
	
	$AFraction = $A / $TotalTries;
	$BFraction = $B / $TotalTries;
	$CFraction = $C / $TotalTries;
	$DFraction = $D / $TotalTries;
	$EFraction = $E / $TotalTries;
	$GiveUpFraction = $GiveUp / $TotalTries;
		
	if ($myurl == $_SESSION['Submitted']){
		$duplicate = 1;
	}
	else{
		$duplicate = 0;
	}
	
	if ($myurl != $_SESSION['Submitted']){
		$mysqliExam = new mysqli($phpMyAdminHost, $phpMyAdminUser, $phpMyAdminPassword, $phpMyAdminDatabase); # resolved conflict
		if ($mysqliExam->connect_errno) {
			echo "Failed to connect to MySQL: (' . $mysqli->connect_errno . ') " . $mysqliExam->connect_error;
		}
		if (!$studentAnswer) {	
			echo "<script language='javascript'>
			alert('You must select an answer');
			document.location.reload(true);
			</script>";
		}
		$updateQuery = "UPDATE $examPretend SET 
			$studentAnswer=${$studentAnswer}, 
			TotalTries=$TotalTries, 
			TotalTimedTries=$TotalTimedTries, 
			TotalCorrect=$TotalCorrect, 
			Accuracy=$Accuracy, 
			TotalTime=$TotalTime, 
			AverageTime=$AverageTime
		WHERE URL='$myurl'";
		
		$updateresult = $mysqliExam->query($updateQuery) or die($mysqliExam->error.__LINE__);
		
		$selectExamAverageTimeQuery = "SELECT AverageTime FROM $examPretend WHERE URL='$myurl'";
		$AverageTimeResult = $mysqliExam->query($selectExamAverageTimeQuery) or die($mysqliExam->error.__LINE__);
		
		if($AverageTimeResult->num_rows > 0) {
			while($rowAverageTime = $AverageTimeResult->fetch_assoc()) {
				$AverageTimeFromDatabase = $rowAverageTime["AverageTime"];
			}
		}
		
		$mysqliMaster = new mysqli($phpMyAdminHost, $phpMyAdminUser, $phpMyAdminPassword, $phpMyAdminDatabase);
		if ($mysqliMaster->connect_errno) {
			echo "Failed to connect to MySQL: (' . $mysqli->connect_errno . ') " . $mysqliMaster->connect_error;
		}
		
		$insertMasterQuery = "INSERT INTO Master (
			uniquename, 
			Name, 
			URL, 
			CorrectAnswer, 
			StudentAnswer, 
			Correct, 
			StartTime, 
			EndTime, 
			TotalTime)
		VALUES (
			'$uniquename', 
			'$Name', 
			'$myurl', 
			'$correctAnswer', 
			'$studentAnswer', 
			'$Correct', 
			$StartTime, 
			$EndTime, 
			$StudentTime)";
			
		$MasterQueryResult = $mysqliMaster->query($insertMasterQuery) or die($mysqliMaster->error.__LINE__);
		
		$mysqliStudent = new mysqli($phpMyAdminHost, $phpMyAdminUser, $phpMyAdminPassword, $phpMyAdminDatabase);
		if ($mysqliStudent->connect_errno) {
			echo "Failed to connect to MySQL: (' . $mysqli->connect_errno . ') " . $mysqliStudent->connect_error;
		}
		
		$studentQuery = "SELECT * FROM Student WHERE uniquename='$uniquename'";
		$resultStudent = $mysqliStudent->query($studentQuery) or die($mysqliStudent->error.__LINE__);
				
		if($resultStudent->num_rows > 0) {
			while($rowStudent = $resultStudent->fetch_assoc()) {
				$StudentTotalTries = $rowStudent["TotalTries"];
				$StudentTotalTimedTries = $rowStudent["TotalTimedTries"];
				$StudentTotalCorrect = $rowStudent["TotalCorrect"];
				$StudentTotalTime = $rowStudent["TotalTime"];
			}
			
			$StudentTotalTries ++;
			if (strcasecmp($studentAnswer,$correctAnswer) == 0){
				$StudentTotalCorrect ++;
			}
			if ($StudentTime < $MaximumTime){
				$StudentTotalTimedTries ++;
				$StudentTotalTime += $StudentTime;
			}
			$StudentAccuracy = $StudentTotalCorrect / $StudentTotalTries;
			$StudentAverageTime = $StudentTotalTime / $StudentTotalTimedTries;
			
			$updateStudentQuery = "UPDATE Student SET 
				TotalTries=$StudentTotalTries, 
				TotalTimedTries=$StudentTotalTimedTries, 
				TotalCorrect=$StudentTotalCorrect, 
				TotalTime=$StudentTotalTime,
				Accuracy=$StudentAccuracy, 
				AverageTime=$StudentAverageTime
			WHERE uniquename='$uniquename'";
			$updateStudentResult = $mysqliStudent->query($updateStudentQuery) or die($mysqliStudent->error.__LINE__);
				
		}
		
		else{
			$StudentTotalTries = 1;
			$StudentTotalTimedTries = 0;
			$StudentTotalCorrect = 0;
			$StudentTotalTime = 0;
			if (strcasecmp($studentAnswer,$correctAnswer) == 0){
				$StudentTotalCorrect = 1;
			}
			
			if ($StudentTime < $MaximumTime){
				$StudentTotalTimedTries ++;
				$StudentTotalTime += $StudentTime;
			}
			
			$StudentAccuracy = $StudentTotalCorrect / $StudentTotalTries;
			$StudentAverageTime = $StudentTotalTime / $StudentTotalTries;
			
			
			
			$insertStudentQuery = "INSERT INTO Student (
				uniquename, 
				TotalTries, 
				TotalCorrect, 
				TotalTime, 
				Accuracy, 
				AverageTime, 
				TotalTimedTries)
			VALUES (
				'$uniquename', 
				$StudentTotalTries, 
				$StudentTotalCorrect, 
				$StudentTotalTime, 
				$StudentAccuracy, 
				$StudentAverageTime, 
				$StudentTotalTimedTries)";
				
			$StudentQueryResult = $mysqliStudent->query($insertStudentQuery) or die($mysqliStudent->error.__LINE__);
		}	
	}
	$_SESSION['Submitted']=$myurl;
}

//If user has not submitted an answer, find a random problem from the selected exam and display it
else {
	$SessionHistoryArray = explode(" ", $_SESSION['SessionHistory']); 
	$SessionHistoryQueryString = "";
if (count($SessionHistoryArray) > 1){
	$SessionHistoryQueryString = " WHERE URL NOT LIKE '$SessionHistoryArray[0]'";
	for ($i = 1; $i < (count($SessionHistoryArray) - 1); $i++) {
		$SessionHistoryQueryString .= " AND URL NOT LIKE '$SessionHistoryArray[$i]'";
	}
}

	$mysqli = new mysqli($phpMyAdminHost, $phpMyAdminUser, $phpMyAdminPassword, $phpMyAdminDatabase);
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (' . $mysqli->connect_errno . ') " . $mysqli->connect_error;
	}
	$query = "SELECT * FROM ".$examPretend."".$SessionHistoryQueryString." ORDER BY RAND() LIMIT 0,1";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$GetName = $row["Name"];
			$myurl = $row["URL"];
			$correctAnswer = $row["Answer"];
			$GetA = $row["A"];
			$GetB = $row["B"];
			$GetC = $row["C"];
			$GetD = $row["D"];
			$GetE = $row["E"];
			$GetGiveUp = $row["GiveUp"];
			$GetTotalTries = $row["TotalTries"];
			$GetTotalTimedTries = $row["TotalTimedTries"];
			$GetTotalCorrect = $row["TotalCorrect"];
			$GetTotalTime = $row["TotalTime"];
			$GetAverageTime = $row["AverageTime"];
			
			$_SESSION['SessionHistory'] .= "$myurl ";

		}
	}	
	else {
		echo "
		<script language=javascript>
		parent.location.href='Congratulations.php';
		</script>
		";
	}
	$GetStartTime = time();
}
?>

<script src="imageload.js"></script>
</head>
<body onLoad="loadImages();<?php if(!isset($_POST['submit'])){ echo "loadFrame('".$myurl."')"; }?>">
<div id="hidepage" style="position: absolute; left:5px; top:5px; background-color: #FFFFCC; layer-background-color: #FFFFCC; height: 100%; width: 90%;"> 
<table width=100%><tr><td>Page loading ... Please wait.</td></tr></table></div>

<form method="post" action="">
	
<?php
//What to display if answer has not been submitted yet
if(!isset($_POST['submit'])){
	echo "
	<script language='javascript'>
	window.parent.document.getElementById('frameset').rows ='105px,*';
	</script>
	<input type='button' value=' Home ' onClick='javascript:parent.location=&quot;index.html&quot;'>
	<input type='button' value='Show/Hide Problem URL' onClick='showURL()'>
	<label id='labelURL' style='visibility:hidden'><a href=".$myurl." target='_blank'>".$myurl."</a></label><br/>
	<input type='radio' name='studentAnswer' id='answerA' value='A' onClick='javascript:document.getElementById(&quot;submitButton&quot;).disabled=false'/> A&nbsp;&nbsp;&nbsp;&nbsp;
	<input type='radio' name='studentAnswer' id='answerB' value='B' onClick='javascript:document.getElementById(&quot;submitButton&quot;).disabled=false'/> B&nbsp;&nbsp;&nbsp;&nbsp;
	<input type='radio' name='studentAnswer' id='answerC' value='C' onClick='javascript:document.getElementById(&quot;submitButton&quot;).disabled=false'/> C&nbsp;&nbsp;&nbsp;&nbsp;
	<input type='radio' name='studentAnswer' id='answerD' value='D' onClick='javascript:document.getElementById(&quot;submitButton&quot;).disabled=false'/> D&nbsp;&nbsp;&nbsp;&nbsp;
	<input type='radio' name='studentAnswer' id='answerE' value='E' onClick='javascript:document.getElementById(&quot;submitButton&quot;).disabled=false'/> E&nbsp;&nbsp;&nbsp;&nbsp;
	<input type='radio' name='studentAnswer' id='answerGiveUp' value='GiveUp' onClick='javascript:document.getElementById(&quot;submitButton&quot;).disabled=false'/> Give Up <br/>
	<input type='hidden' name='Name' value='".$GetName."'>
	<input type='hidden' name='problemURL' value='".$myurl."'>
	<input type='hidden' name='problemAnswer' value='".$correctAnswer."'>
	<input type='hidden' name='A' value='".$GetA."'>
	<input type='hidden' name='B' value='".$GetB."'>
	<input type='hidden' name='C' value='".$GetC."'>
	<input type='hidden' name='D' value='".$GetD."'>
	<input type='hidden' name='E' value='".$GetE."'>
	<input type='hidden' name='GiveUp' value='".$GetGiveUp."'>
	<input type='hidden' name='TotalTries' value='".$GetTotalTries."'>
	<input type='hidden' name='TotalTimedTries' value='".$GetTotalTimedTries."'>
	<input type='hidden' name='TotalCorrect' value='".$GetTotalCorrect."'>
	<input type='hidden' name='TotalTime' value='".$GetTotalTime."'>
	<input type='hidden' name='AverageTime' value='".$GetAverageTime."'>
	<input type='hidden' name='StartTime' value='".$GetStartTime."'>
	<input type='hidden' name='exam' value='".$exam."'>
	<input type='hidden' name='examPretend' value='".$examPretend."'>
	<input type='submit' name='submit' value=' Submit ' disabled='true' id='submitButton'>&nbsp;&nbsp;&nbsp;&nbsp; or &nbsp;&nbsp;&nbsp;&nbsp;
	<input type='button' value=' Skip ' onClick='window.location=&quot;redirect.php?exam=$exam&quot;'>
	<script language='javascript'>
	if (document.getElementById('answerA').checked) 
		{window.location.reload();}
	if (document.getElementById('answerB').checked) 
		{window.location.reload();}
	if (document.getElementById('answerC').checked) 
		{window.location.reload();}
	if (document.getElementById('answerD').checked) 
		{window.location.reload();}
	if (document.getElementById('answerE').checked) 
		{window.location.reload();}
	if (document.getElementById('answerGiveUp').checked) 
		{window.location.reload();}
	</script>
	"
	;
}

//What to display if user has submitted an answer
else{
	if ($duplicate==1){
		echo "
		<script language='javascript'>
		window.parent.document.getElementById('frameset').rows ='105px,*';
		</script>
		<input type='button' value=' Home ' onClick='javascript:parent.location=&quot;index.html&quot;'>
		<input type='button' value='Show/Hide Problem URL' onClick='showURL()'>
		<label id='labelURL' style='visibility:hidden'><a href=".$myurl." target='_blank'>".$myurl."</a></label><br/>
		<input type='button' name='Next' value=' Next ' onClick='window.location=&quot;redirect.php?exam=$exam&quot;'>
		";
		if(isset($_SERVER['REMOTE_USER'])){
			echo "
			<input type='button' value=' Show My Stats ' onClick='javascript:window.open(&quot;stats.php&quot;)'>
			<input type='button' value=' View History ' onClick='javascript:window.open(&quot;history.php&quot;)'>
			";
			}
		echo "<br/>
		You've already submitted an answer. You can only submit once."
		;
	}
	
	else{
		echo "
		<script language='javascript'>
		window.parent.document.getElementById('frameset').rows ='270px,*';
		</script>
		<input type='button' value=' Home ' onClick='javascript:parent.location=&quot;index.html&quot;'>
		<input type='button' value='Show/Hide Problem URL' onClick='showURL()'>
		<label id='labelURL' style='visibility:hidden'><a href=".$myurl." target='_blank'>".$myurl."</a></label><br/>
		<input type='button' name='Next' value=' Next ' onClick='window.location=&quot;redirect.php?exam=$exam&quot;'>
		";
		if(isset($_SERVER['REMOTE_USER'])){
			echo "
			<input type='button' value=' Show My Stats ' onClick='javascript:window.open(&quot;stats.php&quot;)'>
			<input type='button' value=' View History ' onClick='javascript:window.open(&quot;history.php&quot;)'>
			";
			}
		echo "<br/>
		Your Time: ".$StudentTime."&nbsp;seconds&nbsp;&nbsp;&nbsp;Average Time: ".$AverageTimeFromDatabase."&nbsp;seconds<br/>
		Your Answer: ".$studentAnswer."&nbsp;&nbsp;&nbsp;Correct Answer: ".$correctAnswer."<br/>
		<img src='https://chart.googleapis.com/chart?cht=bvs&chd=t:".$AFraction.",".$BFraction.",".$CFraction.",".$DFraction.",".$EFraction.",".$GiveUpFraction."&chs=300x150&chbh=30,12,20&chxt=x,y&chxl=0:|A|B|C|D|E|Gave%20Up&chds=a&chm=N*p1,000055,0,-1,13&chco=FFCC33&chtt=Total%20Responses'></img>
		"
		;
	}
}
?>

</form>
</body>
<script src="testpicker.js?<?php time() ?>"></script>
</html>
