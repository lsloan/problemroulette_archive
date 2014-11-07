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

function is_stat_file($var)
{
    return preg_match('/problem_roulette_[A-Za-z0-9_]+\.sql/', $var);
}

global $usrmgr;
global $dbmgr;

$researcher = $usrmgr->m_user->researcher;

if($researcher == 1)
{
    $json_response = false;

    if (isset($_POST['start_export'])) {
        $terms = NULL;
        $classes = NULL;
        if(isset($_POST['semester'])) {
            $terms = $_POST['semester'];
        }
        if(isset($_POST['course'])) {
            $classes = $_POST['course'];
        }

        MStatsFile::start_export($terms, $classes);

        header('Location:stats_export.php');

    } elseif (isset($_POST['delete_file'])) {
        $filename = $_POST['filename'];
        error_log("Deleting file: ".$filename." (probably not an error)");
        $file_deleted = MStatsFile::delete_file($filename);

        $json_response = array( 'deleted' => $file_deleted );
    }

    if($json_response) {
        echo json_encode($json_response);
    } else {
        $semesters = MSemester::get_semesters_and_response_counts();
        $courses = MCourse::get_courses_and_response_counts();

        $files = array_filter(scandir($GLOBALS["DIR_DOWNLOADS"],  SCANDIR_SORT_DESCENDING), "is_stat_file");


        // page construction
        $head = new CHeadCSSJavascript("Stats Export", array(), array());
        $tab_nav = new VTabNav(new MTabNav('Stats Export'));
        $content = new VStatsExport($semesters, $courses, $files);
        $page = new VPageTabs($head, $tab_nav, $content);

        # delivery the html
        echo $page->Deliver();

    }
} else {
    http_response_code(403);
    echo "<p>Prohibited.  Please contact physics.sso@umich.edu if you are getting this message in error.</p><p><a href=\"selections.php\">Return to Problem Roulette</a></p>";
}

?>
