<?php
require_once(dirname(__FILE__).'/../setup.php');
if (!($usrmgr->m_user->voter || $usrmgr->m_user->admin)) {
    // TODO: Extract the error handling from the REST utility to standalone
    $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
    header($protocol . ' 403 Forbidden');
    echo "<h1>403 - Forbidden</h1>";
    exit;
}
?>
<!doctype html>
<html>

	<head>	
		<title>Problem Roulette</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<!-- Styles -->
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/main.css" rel="stylesheet">   	
	</head>
	
	<body>
		
		<header class="navbar" role="banner">
			<div class="container">
				<nav class="navbar navbar-default">
				  <div class="container-fluid">
				  
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
					  <button type="button" class="navbar-toggle collapsed" 
					  data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
					  <a class="navbar-brand" href="<?= $GLOBALS['DOMAIN'] ?>">Problem Roulette</a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					  <ul class="nav navbar-nav">
                        <li><a class="changeTopics" data-course="6" href="#">Physics 135</a></li>
                        <li><a class="changeTopics" data-course="4" href="#">Physics 140</a></li>
                        <li><a class="changeTopics" data-course="7" href="#">Physics 235</a></li>
                        <li><a class="changeTopics" data-course="5" href="#">Physics 240</a></li>
                        <li><a class="changeTopics" data-course="10" data-oldtopics="50,51,52,53,54,55,56,57,58,59,60,63,64" href="#">Chem 130 - non-exams</a></li>
					  </ul>
					</div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>
			</div>		
		</header>
		
		<!-- Rows -->
		<div class="container">
			<div class="row">
				
                <div id="select-course">
                    <h2>Thank you for voting on problem topics.</h2>
                    <h4>Select a course from above to begin.</h4>
                </div>

                <div id="no-problems" class="hidden">
                    <h2>You have voted on all problems in this course.</h2>
                    <h4>Select a different course from above to continue.</h4>
                </div>

				<!-- Left Side -->
				<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 hidden voting-ui">
					<div class="panel panel-primary">
						<!-- <div class="panel-heading">
							<h3 class="titles">Question</h3>
						</div> -->
						<div class="panel-body leftFrame">
						</div>	
					</div>
				</div>
				
				<!-- Right Size -->
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 hidden voting-ui">
					<form class="form-horizontal" id="frmTopics">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 id="topics-title" class="titles">Select All That Apply</h3>
							</div>
							<div class="panel-body panel-text rightFrame">
							</div>
							<div class="panel-footer">
								<div class="btn-group btn-group-sm">
									<!-- <button type="button" class="btn btn-default" id="btnPrev">Previous</button> -->
									<button type="button" class="btn btn-primary" id="btnNext">Next</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>		
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		
		<!-- Bootstrap -->
		<script src="js/bootstrap.min.js"></script>
		
		<!-- ES6-Promise -->
		<script src="<?= $GLOBALS['DOMAIN_JS'] . 'es6-promise.min.js' ?>"></script>

        <script type="text/javascript">
            var PR_API_URL = "<?= $GLOBALS['DOMAIN'] . 'api/v1' ?>";
        </script>
		
		<!-- PR-API -->
		<script src="<?= $GLOBALS['DOMAIN_JS'] . 'pr-api.js' ?>"></script>
		
		<!-- Other Files -->
		<script src="js/index.js" type="text/javascript"></script>
		
	</body>	
</html>
