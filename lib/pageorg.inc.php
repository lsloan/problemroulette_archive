<?php

class CHeadCSSJavascript{
	// properties
	var $m_title;
	var $m_cssfile;
	var $m_javafile;

	function CHeadCSSJavascript($title, $cssfile, $javafile){
		$this->m_title = $title;
		$this->m_javafile = $javafile;
		$this->m_cssfile = $cssfile;
	}

	function Deliver(){
		$str   = "\n<title>".$this->m_title."</title>";
        if($this->m_cssfile != NULL)
		foreach((array)$this->m_cssfile as $css){
			$str .= "\n<link rel=\"stylesheet\" href=\"".$css."\" type=\"text/css\" ></link>";
		}
        if($this->m_javafile != NULL)
		foreach((array)$this->m_javafile as $java){
			$str .= "\n<script type=\"text/JavaScript\" src=\"".$java."\"></script>";
		}
    $str .= "
      <meta
      name=\"problemroulette\"
      content=\"practice, problems, physics\"/>
    ";
		return $str;
	}
}

class CPageBasic{
	// properties
	var $m_head;
	var $m_body;

	// constructor
	function CPageBasic($head, $body){
		$this->m_head = $head;
		$this->m_body = $body;
	}

	function Deliver(){
		$str 	= "
<html>
<!--open head-->
	<head>"
	.$this->m_head->Deliver().
	"</head>
<!--close head-->
<!--open body-->
	<body>
        " . $this->m_body->DumpProblemEditForm() . "
	</body>
	<!--close body-->
<html>";
		return $str;
	}
    //" . $this->m_body->Deliver() . "
}

class VProblemEditReview
{
	#m_model
	
	function __construct($model)
	{
		$this->m_model = $model;
	}
	
	function DumpProblemEditForm()
	{
		$str = '';
		$str.="
			<form name = 'myForm' onsubmit='return validateForm()' method='POST' action='.'>
			<table border='1'>
			  <TR>
				<TD>Number of answer choices</TD>
				<TD>
				  <input id='ans_count' type='text' name='ans_count' size='1'>
				</TD>
			  </TR>
			  <TR>
				<TD>Correct answer choice (1,2,3,...)</TD>
				<TD><input id='correct' type='text' name='correct' size='1'></TD>
			  </TR>
			</table>
			<p><input type='submit' id='submit' value='Submit' name='submit'></p>
			</form>

			<script language='javascript'>
			function validateForm()
			{
			var x=document.forms['myForm']['ans_count'].value;
			var y=document.forms['myForm']['correct'].value;
			if (x==null || x=='' || y==null || y=='')
			  {
			  alert('Form must be filled out');
			  return false;
			  }
			}
			</script>
		";
		return $str;
	}
	
}
?>
