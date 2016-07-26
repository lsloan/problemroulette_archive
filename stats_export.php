<?php
require_once("setup.php");
require_once("lib/exporter.php");

$courses = MCourse::get_courses_and_response_counts();
$exporter = new Exporter(
	'stats_export.php', 'start_export', 'VStatsExport', $courses,
	'Export User Stats', '/problem_roulette_[A-Za-z0-9_]+\.(sql|csv)/'
);
$exporter->run();

