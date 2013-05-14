<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEADER>
<script src="trackingcode.js"></script>
<script language="JavaScript">
var url = document.referrer.substring(0,document.referrer.indexOf('picker')) + ".php?exam=<?php echo $_GET['exam']; ?>";
</script>
</HEADER>
<BODY onLoad="window.parent.location=url">
</BODY>
</HTML>