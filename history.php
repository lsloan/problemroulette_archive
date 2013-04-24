<!--This page shows a user's history-->
<html>
<head>
<script src="trackingcode.js"></script>
<script language="JavaScript">
var url = document.referrer.substring(0,document.referrer.indexOf('picker')) + ".php";
if(document.referrer.indexOf('picker') < 0){
	url = "index.html";
}
</script>
<font size="5">
<?php 
$uniquename = $_SERVER['REMOTE_USER'];
echo $uniquename;
?>
's history (click to sort)<br/>
</font>

<form method="POST" action="">
<input type="button" value=" Close Window " onClick="window.close()">
Show&nbsp;

<select name="DropDown" id="DropDown" onChange="this.form.submit();">
<option value="10" id="10" <?php if ($_GET['numRows']==10){echo "selected='selected'";}?>>10</option>
<option value="25" id="25" <?php if ($_GET['numRows']==25){echo "selected='selected'";}?>>25</option>
<option value="50" id="50" <?php if ($_GET['numRows']==50){echo "selected='selected'";}?>>50</option>
<option value="All" id="AllRows" <?php if ($_GET['numRows']=='All'){echo "selected='selected'";}?>>All</option>
</select>

rows&nbsp;&nbsp;&nbsp;&nbsp;Select Class&nbsp;

<select name="SelectClass" id="SelectClass" onChange="this.form.submit();">
<option value="All Classes" id="AllClasses" <?php if ($_GET['Class']=='All Classes'){echo "selected='selected'";}?>>All Classes</option>
<option value="140" id="140" <?php if ($_GET['Class']==140){echo "selected='selected'";}?>>Physics 140</option>
<option value="240" id="240" <?php if ($_GET['Class']==240){echo "selected='selected'";}?>>Physics 240</option>
<option value="135" id="135" <?php if ($_GET['Class']==135){echo "selected='selected'";}?>>Physics 135</option>
<option value="235" id="235" <?php if ($_GET['Class']==235){echo "selected='selected'";}?>>Physics 235</option>
</select>

&nbsp;&nbsp;&nbsp;&nbsp;Select Exam&nbsp;

<select name="SelectExam" id="SelectExam" onChange="this.form.submit();">
<option value="All Exams" id="AllExams" <?php if ($_GET['Exam']=='All Exams'){echo "selected='selected'";}?>>All Exams</option>
<option value="Midterm 1" id="m1" <?php if ($_GET['Exam']=="Midterm 1"){echo "selected='selected'";}?>>Midterm 1</option>
<option value="Midterm 2" id="m2" <?php if ($_GET['Exam']=="Midterm 2"){echo "selected='selected'";}?>>Midterm 2</option>
<option value="Midterm 3" id="m3" <?php if ($_GET['Exam']=="Midterm 3"){echo "selected='selected'";}?>>Midterm 3</option>
<option value="Final Exam" id="f" <?php if ($_GET['Exam']=="Final Exam"){echo "selected='selected'";}?>>Final Exam</option>
</select>

</form>

<script language="javascript">
var numRowsSelected = document.getElementById('10');
<?php if ($_POST['DropDown']==10) {echo "numRowsSelected.selected='selected'";}?>

var numRowsSelected = document.getElementById('25');
<?php if ($_POST['DropDown']==25) {echo "numRowsSelected.selected='selected'";}?>

var numRowsSelected = document.getElementById('50');
<?php if ($_POST['DropDown']==50) {echo "numRowsSelected.selected='selected'";}?>

var numRowsSelected = document.getElementById('AllRows');
<?php if ($_POST['DropDown']=="All") {echo "numRowsSelected.selected='selected'";}?>

var classSelected = document.getElementById('AllClasses');
<?php if ($_POST['SelectClass']=="All Classes") {echo "classSelected.selected='selected'";}?>

var classSelected = document.getElementById('140');
<?php if ($_POST['SelectClass']==140) {echo "classSelected.selected='selected'";}?>

var classSelected = document.getElementById('240');
<?php if ($_POST['SelectClass']==240) {echo "classSelected.selected='selected'";}?>

var classSelected = document.getElementById('135');
<?php if ($_POST['SelectClass']==135) {echo "classSelected.selected='selected'";}?>

var classSelected = document.getElementById('235');
<?php if ($_POST['SelectClass']==235) {echo "classSelected.selected='selected'";}?>

var examSelected = document.getElementById('m1');
<?php if ($_POST['SelectExam']=='Midterm 1') {echo "examSelected.selected='selected'";}?>

var examSelected = document.getElementById('m2');
<?php if ($_POST['SelectExam']=='Midterm 2') {echo "examSelected.selected='selected'";}?>

var examSelected = document.getElementById('m3');
<?php if ($_POST['SelectExam']=='Midterm 3') {echo "examSelected.selected='selected'";}?>

var examSelected = document.getElementById('f');
<?php if ($_POST['SelectExam']=='Final Exam') {echo "examSelected.selected='selected'";}?>
</script>


<form method="GET" action="">
<input type="hidden" id="numRows" name="numRows" value="2">
</form>
</head>

<body>
<?php
$mysqliMaster = new mysqli("webapps-db.web.itd", "problemroulette", "GilbertWhitaker", "problemroulette");
if ($mysqliMaster->connect_errno) {
	echo "Failed to connect to MySQL: (' . $mysqli->connect_errno . ') " . $mysqliMaster->connect_error;
}

$sortBy = "EndTime";
$sortOrder = "DESC";
$showRows = 10;
$Class = "All Classes";
$Exam = "All Exams";

if (isset($_GET['Exam'])) {
	$Exam = $_GET['Exam'];
}

if (isset($_POST['SelectExam'])) {
	$Exam = $_POST['SelectExam'];
}

if (isset($_GET['Class'])) {
	$Class = $_GET['Class'];
}

if (isset($_POST['SelectClass'])) {
	$Class = $_POST['SelectClass'];
}

if (isset($_GET['numRows'])) {
	$showRows = $_GET['numRows'];
}

if (isset($_POST['DropDown'])) {
	$showRows = $_POST['DropDown'];
}

if (isset($_GET['sortBy'])){
	$sortBy = $_GET["sortBy"];
}

if (isset($_GET['sortOrder'])){
	$sortOrder = $_GET["sortOrder"];
}

$ClassQuery = $Class;
$ExamQuery = $Exam;

if ($Exam=='All Exams'){
	$ExamQuery = '';
}

if ($Class=='All Classes'){
	$ClassQuery = '';
}

if ($showRows != 'All'){
	$selectMasterQuery = "SELECT * FROM Master WHERE uniquename='$uniquename' and Name LIKE '%$ClassQuery%' and Name LIKE '%$ExamQuery%' ORDER BY $sortBy $sortOrder LIMIT 0,$showRows";
}
else{
	$selectMasterQuery = "SELECT * FROM Master WHERE uniquename='$uniquename' and Name LIKE '%$ClassQuery%' and Name LIKE '%$ExamQuery%' ORDER BY $sortBy $sortOrder";
}

$selectMasterQueryResult = $mysqliMaster->query($selectMasterQuery) or die($mysqliMaster->error.__LINE__);

echo "<table border='1'>
	<tr>
	<td align='center' "; if ($sortBy == 'Name') {echo "bgcolor=#99AAFF ";} else {echo "bgcolor=#C9C9FF ";} 
	echo "onClick='window.location=&quot;".$_SERVER["PHP_SELF"]."?numRows=$showRows&Class=$Class&Exam=$Exam&sortBy=Name&sortOrder=";
	if ($sortOrder == 'ASC' and $sortBy == 'Name'){echo 'DESC';} else {echo 'ASC';} echo "&quot;'> <b>Name";
	if ($sortOrder == 'ASC' and $sortBy == 'Name'){echo '&nbsp;&darr;';} 
	elseif ($sortOrder == 'DESC' and $sortBy == 'Name') {echo '&nbsp;&uarr;';} echo "</b> </td>
	
	<td align='center' "; if ($sortBy == 'EndTime') {echo "bgcolor=#99AAFF ";} else {echo "bgcolor=#C9C9FF ";} 
	echo "onClick='window.location=&quot;".$_SERVER["PHP_SELF"]."?numRows=$showRows&Class=$Class&Exam=$Exam&sortBy=EndTime&sortOrder=";
	if ($sortOrder == 'DESC' and $sortBy == 'EndTime'){echo 'ASC';} else {echo 'DESC';} echo "&quot;'> <b>Date";
	if ($sortOrder == 'ASC' and $sortBy == 'EndTime'){echo '&nbsp;&darr;';} 
	elseif ($sortOrder == 'DESC' and $sortBy == 'EndTime') {echo '&nbsp;&uarr;';} echo "</b> </td>
	
	<td align='center' "; if ($sortBy == 'StudentAnswer') {echo "bgcolor=#99AAFF ";} else {echo "bgcolor=#C9C9FF ";} 
	echo "onClick='window.location=&quot;".$_SERVER["PHP_SELF"]."?numRows=$showRows&Class=$Class&Exam=$Exam&sortBy=StudentAnswer&sortOrder=";
	if ($sortOrder == 'ASC' and $sortBy == 'StudentAnswer'){echo 'DESC';} else {echo 'ASC';} echo "&quot;'> <b>Your Answer";
	if ($sortOrder == 'ASC' and $sortBy == 'StudentAnswer'){echo '&nbsp;&darr;';} 
	elseif ($sortOrder == 'DESC' and $sortBy == 'StudentAnswer') {echo '&nbsp;&uarr;';} echo "</b> </td>
	
	<td align='center' "; if ($sortBy == 'CorrectAnswer') {echo "bgcolor=#99AAFF ";} else {echo "bgcolor=#C9C9FF ";} 
	echo "onClick='window.location=&quot;".$_SERVER["PHP_SELF"]."?numRows=$showRows&Class=$Class&Exam=$Exam&sortBy=CorrectAnswer&sortOrder=";
	if ($sortOrder == 'ASC' and $sortBy == 'CorrectAnswer'){echo 'DESC';} else {echo 'ASC';} echo "&quot;'> <b>Correct Answer";
	if ($sortOrder == 'ASC' and $sortBy == 'CorrectAnswer'){echo '&nbsp;&darr;';} 
	elseif ($sortOrder == 'DESC' and $sortBy == 'CorrectAnswer') {echo '&nbsp;&uarr;';} echo "</b> </td>
	
	<td align='center' "; if ($sortBy == 'Correct') {echo "bgcolor=#99AAFF ";} else {echo "bgcolor=#C9C9FF ";} 
	echo "onClick='window.location=&quot;".$_SERVER["PHP_SELF"]."?numRows=$showRows&Class=$Class&Exam=$Exam&sortBy=Correct&sortOrder=";
	if ($sortOrder == 'ASC' and $sortBy == 'Correct'){echo 'DESC';} else {echo 'ASC';} echo "&quot;'> <b>Correct";
	if ($sortOrder == 'ASC' and $sortBy == 'Correct'){echo '&nbsp;&darr;';} 
	elseif ($sortOrder == 'DESC' and $sortBy == 'Correct') {echo '&nbsp;&uarr;';} echo "</b> </td>
	
	<td align='center' "; if ($sortBy == 'TotalTime') {echo "bgcolor=#99AAFF ";} else {echo "bgcolor=#C9C9FF ";} 
	echo "onClick='window.location=&quot;".$_SERVER["PHP_SELF"]."?numRows=$showRows&Class=$Class&Exam=$Exam&sortBy=TotalTime&sortOrder=";
	if ($sortOrder == 'DESC' and $sortBy == 'TotalTime'){echo 'ASC';} else {echo 'DESC';} echo "&quot;'> <b>Time";
	if ($sortOrder == 'ASC' and $sortBy == 'TotalTime'){echo '&nbsp;&darr;';} 
	elseif ($sortOrder == 'DESC' and $sortBy == 'TotalTime') {echo '&nbsp;&uarr;';} echo "</b> </td>
	</tr>";

	$i = 0;
if($selectMasterQueryResult->num_rows > 0) {
	while($row = $selectMasterQueryResult->fetch_assoc()) {
		$Name = $row["Name"];
		$URL = $row["URL"];
		$CorrectAnswer = $row["CorrectAnswer"];
		$Correct = $row["Correct"];
		$StudentAnswer = $row["StudentAnswer"];
		$Timestamp = $row["EndTime"];
		$TotalTime = $row["TotalTime"];
		$Date = date("D, F j, Y, g:i a",$Timestamp);
		
		if (($i % 2) == 0) {
			echo "<tr>
				<td align='left' bgcolor=#DDDDEE> <a href='$URL' target='_blank'>$Name</a> </td>
				<td align='center' bgcolor=#DDDDEE> $Date </td>
				<td align='center' bgcolor=#DDDDEE> $StudentAnswer </td>
				<td align='center' bgcolor=#DDDDEE> $CorrectAnswer </td>
				<td align='center' bgcolor=#DDDDEE> $Correct </td>
				<td align='center' bgcolor=#DDDDEE> $TotalTime s </td>
				</tr>
				";
		}
		else {
			echo "<tr>
				<td align='left' bgcolor=#F0F0FF> <a href='$URL' target='_blank'>$Name</a> </td>
				<td align='center' bgcolor=#F0F0FF> $Date </td>
				<td align='center' bgcolor=#F0F0FF> $StudentAnswer </td>
				<td align='center' bgcolor=#F0F0FF> $CorrectAnswer </td>
				<td align='center' bgcolor=#F0F0FF> $Correct </td>
				<td align='center' bgcolor=#F0F0FF> $TotalTime s </td>
				</tr>
				";
		}
		
		$i ++;
	}
}
echo "</table>";
?>
</body>
</html>