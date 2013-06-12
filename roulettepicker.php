<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!--This page contains the majority of inner workings of Problem Roulette-->
   
<html>
<head>

</head>

<body >
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
