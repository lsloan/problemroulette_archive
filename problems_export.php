<?php
require_once("setup.php");
require_once("lib/exporter.php");

$courses = MCourse::get_courses_and_problem_counts();
$exporter = new Exporter(
	'problems_export.php', 'export_problems', 'VProblemsExport', $courses,
	'Export Problem Stats', '/problems_[A-Za-z0-9_]+\.(sql|csv)/'
);
$exporter->run();

