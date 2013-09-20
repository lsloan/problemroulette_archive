<?php
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
$args = GrabAllArgs();
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");

session_start();
$_SESSION['sesstest'] = 1;

// redirect if not staff!
global $usrmgr;
$staff = $usrmgr->m_user->staff;
if ($staff != 1) 
    header('Location: ' . $GLOBALS["DOMAIN"]);

$cmd = "mysqldump --user='" . $GLOBALS["SQL_USER"] . "' --password='" . $GLOBALS["SQL_PASSWORD"] . "' --host='" . $GLOBALS["SQL_SERVER"] ."' ". $GLOBALS["SQL_DATABASE"] . " > " . $GLOBALS["DIR_DOWNLOADS"] . "down.sql";

exec($cmd);

$file = $GLOBALS["DIR_DOWNLOADS"].'down.sql';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}
?>
