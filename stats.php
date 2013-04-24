<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
   
<!--This page shows the user his/her overall statistics-->
   
<html>
<head>
<script src="trackingcode.js"></script> <!--tracking code for Google Analytics-->

<script language="JavaScript">
var url = document.referrer.substring(0,document.referrer.indexOf('picker')) + ".php";
if(!document.referrer){
	url = "index.html";
}
</script>
<font size="7"><U>
<?php 
$phpMyAdminHost = 'localhost';
$phpMyAdminUser = 'root';
$phpMyAdminPassword = 'password';
$phpMyAdminDatabase = 'problemroulette';

$uniquename = $_SERVER['REMOTE_USER'];
echo $uniquename;
?>
's stats
</U></font>
</head>
<body>
<?php
$mysqliStudent = new mysqli($phpMyAdminHost, $phpMyAdminUser, $phpMyAdminPassword, $phpMyAdminDatabase);
if ($mysqliStudent->connect_errno) {
	echo "Failed to connect to MySQL: (' . $mysqli->connect_errno . ') " . $mysqliStudent->connect_error;
}

$studentQuery = "SELECT * FROM Student WHERE uniquename='$uniquename'";
$resultStudent = $mysqliStudent->query($studentQuery) or die($mysqliStudent->error.__LINE__);

if($resultStudent->num_rows > 0) {
	while($rowStudent = $resultStudent->fetch_assoc()) {
		$StudentTotalTries = $rowStudent["TotalTries"];
		$StudentTotalCorrect = $rowStudent["TotalCorrect"];
		$StudentAccuracy = $rowStudent["Accuracy"];
		$StudentAverageTime = $rowStudent["AverageTime"];
		$StudentAccuracyInPercent = $StudentAccuracy * 100;
	}
}
echo "
	<font size='5'> <br/>
	You have attempted ".$StudentTotalTries." problems and you got ".$StudentTotalCorrect." correct.<br/>
	Your accuracy is ".$StudentAccuracyInPercent."%. <br/>
	Your average time per problem is ".$StudentAverageTime." seconds.
	</font> <br/>
"
?>
<form>
<input type="button" value=" Close Window " onClick="window.close()">
</form>
</body>
</html>
