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
			$str .= "\n<link rel='stylesheet' href='".$css."' type='text/css' media='screen'></link>";
		}
        if($this->m_javafile != NULL)
		foreach((array)$this->m_javafile as $java){
			$str .= "\n<script type='text/JavaScript' src='".$java."'></script>";
		}
    $str .= "
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='css/bootstrap.min.css' rel='stylesheet' media='screen'>
    ";
		return $str;
	}
}

class VPageTabs{
	// properties
	var $m_head;
    var $m_nav;
    var $m_nav2;
    var $m_content;

	// constructor
	function __construct($head, $nav, $nav2, $content){
		$this->m_head = $head;
		$this->m_nav = $nav;
		$this->m_nav2 = $nav2;
		$this->m_content = $content;
	}

	function Deliver(){
		$str 	= "
<html lang='en'>
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
        <div class='course_topic_nav'>"
            .$this->m_nav2->Deliver(). 
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

class VCourseTopicNav
{	
	function __construct($nav)
	{
        $this->m_nav = $nav;
	}
	
	function Deliver()
	{
        $str = "<div id='accordion'>";
        foreach($this->m_nav->m_courses as $cc)
        {
            $str .= "<h3>". $cc->m_name ."</h3><div>";
            foreach($cc->m_topics as $tt) 
            {
                $str .= "<input type='checkbox' name='".$tt->m_id."' value='".$tt->m_id."'>".$tt->m_name."<br>";
            }
            $str .= "</div>";
        }
        $str .= "</div>";
        return $str;
    }
}
/*
            <h3>Section 2</h3>
                <div>
                content
                </div>
            <h3>Section 3</h3>
                <div>
                content
                <ul>
                <li>List item one</li>
                <li>List item two</li>
                <li>List item three</li>
                </ul>
                </div>
            <h3>Section 4</h3>
                <div>
                </div>
*/

class VStaff
{
	#m_model
	
	function __construct()
	{
	}
	
	function Deliver()
	{
        $str = "
        <link rel='stylesheet' href='http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' />
        <script src='http://code.jquery.com/jquery-1.9.1.js'></script>
        <script src='http://code.jquery.com/ui/1.10.3/jquery-ui.js'></script>
        <script>
            $(function() {
                $( '#accordion' ).accordion({
                collapsible: true
                });
            });
        </script>
        ";
        
        $str .= "hi, this is the staff page... this well soon be more then one page";
        return $str;
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
