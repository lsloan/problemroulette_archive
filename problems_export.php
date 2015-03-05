<?php
require_once("setup.php");

function is_problems_file($var)
{
    return preg_match('/problems_[A-Za-z0-9_]+\.sql/', $var);
}

global $usrmgr;
global $dbmgr;

$researcher = $usrmgr->m_user->researcher;
$staff = $usrmgr->m_user->staff;

if($researcher == 1 || $stafs == 1)
{
    $json_response = false;
    $file_response = false;

    if (isset($_POST['start_export'])) {
        $classes = NULL;
        if(isset($_POST['course'])) {
            $classes = $_POST['course'];
        }

        MStatsFile::export_problems($classes);

        header('Location:problems_export.php');

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
        $courses = MCourse::get_courses_and_problem_counts();

        $files = array_filter(scandir($GLOBALS["DIR_STATS"],  SCANDIR_SORT_DESCENDING), "is_problems_file");

        // page construction
        $head = new CHeadCSSJavascript("Export Problem Stats", array(), array());
        $tab_nav = new VTabNav(new MTabNav('Export Problem Stats'));
        $content = new VProblemsExport($courses, $files);
        $page = new VPageTabs($head, $tab_nav, $content);

        # delivery the html
        echo $page->Deliver();

    }
} else {
    http_response_code(403);
    echo "<p>Prohibited.  Please contact physics.sso@umich.edu if you are getting this message in error.</p><p><a href=\"selections.php\">Return to Problem Roulette</a></p>";
}

?>
