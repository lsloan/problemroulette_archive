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

/*   
#
# KEEP AROUND...for a rainy day
#
global $dbmgr; 
//get all of the tables
$tables = array();
$result = $dbmgr->fetch_num('SHOW TABLES');
foreach($result as $row)
{
    $tables[] = $row[0];
}
//cycle through them
$return = "";
foreach($tables as $table)
{
    $result = $dbmgr->fetch_num('SELECT * FROM '.$table);
    $num_fields = count($result);
    
    $return .= 'DROP TABLE '.$table.';';
    $row2 = $dbmgr->fetch_num('SHOW CREATE TABLE '.$table);
    $return .= "\n\n".$row2[0][1].";\n\n";
    
    foreach($result as $res)
    {
        $return .= 'INSERT INTO '.$table.' VALUES(';
        foreach($res as $ii) 
        {
            $ii = addslashes($ii);
            $ii = ereg_replace("\n","\\n",$ii);

            if (isset($ii)) 
            { 
                $return .= '"'.$ii.'",' ; 
            } 
            else 
            { 
                $return .= '"",'; 
            }
        }
        $return = substr($return, 0, -1);
        $return .= ");\n";
    }
    $return .= "\n\n\n";
}
$file = $GLOBALS["DIR_DOWNLOADS"].'down.sql';
file_put_contents($file, $return);
*/



?>
