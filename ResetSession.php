<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!--This page resets the users php session if necessary-->
   
<html>
<head>
<?php 
session_start();
session_destroy();
?>
<title>
Physics Problem Roulette
</title>
</head>
<body onLoad="javascript:location='index.html'">
</body>
</html>