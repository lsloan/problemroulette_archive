<html>
<body>
<?php
include_once 'config.inc.php';
if ($adminpw == '')
{
	echo "
		<form id='login' action='authsetup.php' method='post' accept-charset='UTF-8'>
		<fieldset >
		<legend>Set Password</legend>
		<input type='hidden' name='submitted' id='submitted' value='1'/>
		 
		<label for='enterpassword' >Enter Password:</label>&nbsp&nbsp&nbsp&nbsp
		<input type='password' name='enterpassword' id='enterpassword' maxlength='50' />
		<br/>
		<label for='confirmpassword' >Confirm Password:</label>
		<input type='password' name='confirmpassword' id='confirmpassword' maxlength='50' />
		<br/><br/>
		<input type='submit' name='Submit' value='Submit' />
		 
		</fieldset>
		</form>
	";
}
else
{
	echo "
		<form id='login' action='authsetup.php' method='post' accept-charset='UTF-8'>
		<fieldset >
		<legend>Login</legend>
		<input type='hidden' name='submitted' id='submitted' value='1'/>
		 
		<label for='password' >Password:</label>
		<input type='password' name='password' id='password' maxlength='50' />
		<br/><br/>
		<input type='submit' name='Submit' value='Submit' />
		 
		</fieldset>
		</form>
	";
}
?>
</body>
</html>