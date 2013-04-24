<html>
<body>
<?php
include_once 'config.inc.php';

if ($adminpw == '')
{
	 if(empty($_POST['enterpassword']))
	{
		echo '<script language=javascript> alert("Password is empty!");
		window.location.href = "setup.php";</script>';
	}
	 if(empty($_POST['confirmpassword']))
	{
		echo '<script language=javascript> alert("Password is empty!");
		window.location.href = "setup.php";</script>';
	}
	$enterpassword = trim($_POST['enterpassword']);
	$confirmpassword = trim($_POST['confirmpassword']);
	 
	if($enterpassword != $confirmpassword)
	{
		echo '<script language=javascript> alert("Passwords do not match!");
		window.location.href = "setup.php";</script>';
	}
	else
	{
		$configfile = 'config.inc.php';
		$fh = fopen($configfile,'w') or die("Can't open file");
		$stringwrite = "<?php $"."adminpw = '".$enterpassword."'; ?>";
		fwrite($fh,$stringwrite);
		fclose($fh);
	}
}
else
{
	 if(empty($_POST['password']))
	{
		echo '<script language=javascript> alert("Password is empty!");
		window.location.href = "setup.php";</script>';
	}
	$password = trim($_POST['password']);
	 
	if($password != $adminpw)
	{
		echo '<script language=javascript> alert("Password is incorrect!");
		window.location.href = "setup.php";</script>';
	}
}
?>

logged in
</body>
</html>