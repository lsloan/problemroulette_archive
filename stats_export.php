<?php
require_once("setup.php");

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
    $file_response = false;

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
    } elseif (isset($_GET['download'])) {
        $file_response = true;
    }

    if($json_response) {
        echo json_encode($json_response);
    } elseif($file_response) {
        $filename = $_GET['download'];
        error_log("Handling download of file ".$filename);
        //content type
        header('Content-type: text/plain');
        //open/save dialog box
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        //read from server and write to buffer
        readfile($GLOBALS["DIR_STATS"].$filename);
        error_log("Done handling download of file ".$filename);

    } else {
        $semesters = MSemester::get_semesters_and_response_counts();
        $courses = MCourse::get_courses_and_response_counts();

        $files = array_filter(scandir($GLOBALS["DIR_STATS"],  SCANDIR_SORT_DESCENDING), "is_stat_file");

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
