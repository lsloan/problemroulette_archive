<?php
// Very simple status script. It doesn't leak any real diagnostic information but times a basic query.
require_once("setup.php");

$start = microtime(true);
$sql = "SELECT COUNT(*) FROM migrations";
$rs = $dbmgr->fetch_column($sql);

// Always round up.
$duration = ceil((microtime(true) - $start) * 1000);

header('Content-Type', 'text/plain');
echo $duration;
