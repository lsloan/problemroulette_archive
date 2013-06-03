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
        " . $this->m_body->Deliver() . "
	</body>
	<!--close body-->
<html>";
		return $str;
	}
    //" . $this->m_body->Deliver() . "
}

class VPageTabs{
	// properties
	var $m_head;
	var $m_body;

	// constructor
	function VPageTabs($head, $nav, $content){
		$this->m_head = $head;
		$this->m_nav = $nav;
		$this->m_content = $content;
	}

	function Deliver(){
		$str 	= "
<html>
<!--open head-->
	<head>
    "
	.$this->m_head->Deliver().
	"</head>
<!--close head-->
<!--open body-->
	<body>
<div id='wrapper'>
    <div id='tabContainer'>
        <div class='tabs'>"
            .$this->m_nav->Deliver().
        "</div>
        <div class='tabscontent'>
            <div class='tabpage'>"
            .$this->m_content->Deliver().
            "</div>
        </div> 
    </div>
</div>
	</body>
	<!--close body-->
<html>";
		return $str;
	}
    //" . $this->m_body->Deliver() . "
}

class VTabNav
{	
	function __construct($nav)
	{
        $this->m_nav = $nav;
	}
	
	function Deliver()
	{
        $selected = 'Problems';
        $str = "<ul>";
		foreach($this->m_nav->m_pages as $tab=>$url)
        {
            $tabStyle = '';
            if($this->m_nav->m_selected == $tab)
                $tabStyle = 'tabActiveHeader';
            $str .= "<li class='".$tabStyle."'><a href='".$url."'>".$tab."</a></li>";
        }
        $str .= "</ul>";
        return $str;
    }
}

class VStaff
{
	#m_model
	
	function __construct()
	{
	}
	
	function Deliver()
	{
        return "hi, this is the staff page... this well soon be more then one page";
    }
}

class VStatistics
{
	#m_model
	
	function __construct()
	{
	}
	
	function Deliver()
	{
        return "hi, this is the statistics page"; 
    }
}

class VProblems
{
	#m_model
	
	function __construct()
	{
	}
	
	function Deliver()
	{
        return "hi, this is the problems page"; 
    }
}

class VHome
{
	#m_model
	
	function __construct()
	{
	}
	
	function Deliver()
	{
        return "hi, this is the home page"; 
    }
}

class VProblemEditReview
{
	#m_model
	
	function __construct($model)
	{
		$this->m_model = $model;
	}
	
	function Deliver()
	{
		$str = '';
		$str.="
			<form name = 'myForm' onsubmit='return validateForm()' method='POST' action='url'>
			<table border='1'>
			  <TR>
				<TD>Problem Name</TD>
				<TD>Problem Name Goes Here</TD>
			  </TR>
			  <TR>
				<TD>Problem URL</TD>
				<TD>Problem URL Goes Here</TD>
			  </TR>
			  <TR>
				<TD>Class Name</TD>
				<TD>Class Name Goes Here</TD>
			  </TR>
			  <TR>
				<TD>Topic Name</TD>
				<TD>Topic Name Goes Here</TD>
			  </TR>
			  <TR>
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
