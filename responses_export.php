<?php
require_once("setup.php");

function is_responses_file($var)
{
    return preg_match('/responses_[A-Za-z0-9_]+\.(sql|csv)/', $var);
}

global $usrmgr;
global $dbmgr;

$researcher = $usrmgr->m_user->researcher;
$staff = $usrmgr->m_user->staff;

if($researcher == 1 || $staff == 1) {
    $json_response = false;
    $file_response = false;
    $format = 'sql';

    if (isset($_POST['start_export'])) {
        $terms = NULL;
        $classes = NULL;
        $format = 'sql';
        if(isset($_POST['semester'])) {
            $terms = $_POST['semester'];
        }
        if(isset($_POST['course'])) {
            $classes = $_POST['course'];
        }
        if(isset($_POST['format'])) {
            $format = $_POST['format'];
        }

        MStatsFile::export_responses($terms, $classes, $format);
        header('Location:responses_export.php');

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
        // send arg of true to include skips in responses
        $semesters = MSemester::get_semesters_and_response_counts(true);
        $courses = MCourse::get_courses_and_response_counts(true);

        $files = array_filter(scandir($GLOBALS["DIR_STATS"],  SCANDIR_SORT_DESCENDING), "is_responses_file");

        // page construction
        $head = new CHeadCSSJavascript("Export Responses Stats", array(), array());
        $tab_nav = new VTabNav(new MTabNav('Export Responses Stats'));
        $content = new VResponsesExport($semesters, $courses, $files);
        $page = new VPageTabs($head, $tab_nav, $content);

        echo $page->Deliver();
    }
} else {
    http_response_code(403);
    echo "<p>Prohibited.  Please contact physics.sso@umich.edu if you are getting this message in error.</p><p><a href=\"selections.php\">Return to Problem Roulette</a></p>";
}

?>
