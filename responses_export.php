<?php
require_once("setup.php");
require_once("lib/exporter.php");

$courses = MCourse::get_courses_and_response_counts(true);
$exporter = new Exporter(
	'responses_export.php', 'export_responses', 'VResponsesExport', $courses,
	'Export Responses Stats', '/responses_[A-Za-z0-9_]+\.(sql|csv)/'
);
$exporter->run();

