<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!--This page congratulates a user who completes all 
available problems for an exam in a single session and 
offers the chance to reset his/her session (since repeat 
problems in a given session are skipped automatically-->   

<html>
<head>
<title>
Physics Problem Roulette
</title>
Congratulations!
</head>
<body>
<p>
You've completed or skipped all the problems currently available for this exam!
</p>
<p>
To revisit these problems randomly, select "Reset Session" and navigate back to the problems page<br/>
To revisit specific problems you struggled with, select "View History"<br/>
To return to the home screen, select "Home"
</p>
<form>
<input type='button' value=' Reset Session ' onClick="javascript:location='ResetSession.php'">
<input type='button' value=' View History ' onClick='javascript:window.open(&quot;history.php&quot;)'>
<input type='button' value=' Home ' onClick='javascript:location=&quot;index.html&quot;'>