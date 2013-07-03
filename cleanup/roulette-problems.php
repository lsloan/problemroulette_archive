<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!--This file is the page you are on while working problems.
It is just the back bone. The main code is in roulettepicker.php-->
   
<html>
<head>
<title>Random Physics Problems</title>
<script src="trackingcode.js"></script>
</head>

<frameset rows="105px,*" id="frameset">
<frame id="picker" src="roulettepicker.php?exam=<?php echo $_GET['exam'];?>&<?php time() ?>"/>
<frame id="problem" src="https://docs.google.com/document/pub?id=13p0LLEVbufdhCgAXoEpWXRz31YZjO3h6T3zvkbk5rcc"> 
	
</frame>
</frameset>
</html>