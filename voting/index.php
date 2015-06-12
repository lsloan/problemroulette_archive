<?php
require_once(__DIR__.'/../setup.php');
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
                        <li class="active"><a class="changeTopics" data-course="6" href="#">Physics 135</a></li>
                        <li><a class="changeTopics" data-course="4"href="#">Physics 140</a></li>
                        <li><a class="changeTopics" data-course="7"href="#">Physics 235</a></li>
                        <li><a class="changeTopics" data-course="5"href="#">Physics 240</a></li>

                        <!--
						<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
						<li><a href="#">Link</a></li>
                        -->
                        <!--
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
						  role="button" aria-expanded="false">Subjects <span 
						  class="caret"></span></a>
						  <ul class="dropdown-menu" role="menu">
						  	<li><a href="#">Chemistry 130</a></li>
						  	<li class="divider"></li>
						  	<li><a hred"#">EECS 314</a></li>
						  	<li class="divider"></li>
						  	<li><a hred"#">MCDB 310</a></li>
						  	<li class="divider"></li>
							<li class="divider"></li>
							<li><a href="#">Statistics 250</a></li>
						  </ul>
						</li>
                        -->
					  </ul>
					  
                      <!--
					  <ul class="nav navbar-nav navbar-right">
						<li><a href="#">Link</a></li>
						<li class="dropdown">
						
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
						  role="button" aria-expanded="false">Dropdown <span 
						  class="caret"></span></a>
						  
						  <ul class="dropdown-menu" role="menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
						  </ul>
						</li>
					  </ul>
                      -->
					</div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>
			</div>		
		</header>
		
		<!-- Rows -->
		<div class="container">
			<div class="row">
				
				<!-- <button class="changeTopics" data-course="4">Course 4</button>
				<button class="changeTopics" data-course="5">Course 5</button>
				<button class="changeTopics" data-course="6">Course 6</button> -->
				<!-- Left Side -->
				<div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
					<div class="panel panel-primary">
						<!-- <div class="panel-heading">
							<h3 class="titles">Question</h3>
						</div> -->
						<div class="panel-body leftFrame">
							<iframe src="" 
							width="100%" height="540px"></iframe>
						</div>	
					</div>
				</div>
				
				<!-- Right Size -->
				<div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
					<form class="form-horizontal" id="frmTopics">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 id="topics-title" class="titles">Select All That Apply</h3>
							</div>
							<div class="panel-body panel-text rightFrame">
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic01"> Interactions and Motion
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic02"> The Momentum Principle
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic03"> The Fundamental Interactions
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic04"> Contact Interactions
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic05"> Rate of Change of Momentum
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic06"> The Energy Principle
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic07"> Internal Energy
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic08"> Multiparticle Systems
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic09"> Collisions
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic10"> Angular Momentum
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic11"> Electric Fields
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic12"> Electric Potential
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic13"> Magnetic Fields
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic14"> Circuits
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic15"> Magnetic Force
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input class="checkbox" type="checkbox" value="topic16"> Faraday's Law
									</label>
								</div>
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
