<?php

// All registered migrations, mapping the class name to the file in migrations/.
// Each item should have name and file keys. For example:
//   array('name' => 'Example', 'file' => 'example.php')
$migrations = array(
);

// paths
require_once("./paths.inc.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
$GLOBALS["dbmgr"] = new CDbMgr();
// user manager
require_once( $DIR_LIB."usrmgr.php" );
$GLOBALS["usrmgr"] = new UserManager();
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
require_once($GLOBALS["DIR_LIB"]."migration.php");
$args = GrabAllArgs();
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");

session_start();

global $dbmgr;


$Q = array();
$Q['setup'] =<<<SQL
CREATE TABLE IF NOT EXISTS migrations (
    name VARCHAR(255) NOT NULL PRIMARY KEY,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
SQL;

$Q['last_run'] =<<<SQL
SELECT name, timestamp FROM migrations
ORDER BY timestamp DESC
LIMIT 1
SQL;

$Q['all_run'] =<<<SQL
SELECT name, timestamp FROM migrations
ORDER BY timestamp ASC
SQL;

$Q['record'] =<<<SQL
INSERT INTO migrations (name)
VALUES(?)
SQL;

$dbmgr->exec_query($Q['setup']);

$last_run = $dbmgr->fetch_assoc($Q['last_run']);
$last_run = $last_run[0];
$newest = end((array_values($migrations)));

if (!isset($last_run['name']) || $last_run['name'] != $newest['name']) {

    $all_run = $dbmgr->fetch_column($Q['all_run']);
    $remaining = array();

    foreach($migrations as $m) {
        if (false == array_search($m, $all_run)) {
            $remaining[] = $m;
        }
    }

    foreach($remaining as $m) {
        require_once("migrations/" . $m['file']);
        $class = new ReflectionClass($m['name']);
        $mig = $class->newInstance();
        $mig->run();
        $dbmgr->exec_query($Q['record'], array($m['name']));
        sleep(1); //to ensure different timestamps
    }
}

?>
All migrations have been run.
