<?php
require_once("setup.php");

class Exporter {
    function __construct($returnUrl, $exportMethod, $viewClass, $courseCounts, $navLabel, $filePattern) {
        $this->returnUrl     = $returnUrl;
        $this->exportMethod  = $exportMethod;
        $this->viewClass     = $viewClass;
        $this->courseCounts  = $courseCounts;
        $this->navLabel      = $navLabel;
        $this->filePattern   = $filePattern;
    }

    protected function fileMatches($file) {
        return preg_match($this->filePattern, $file);
    }

    protected function checkAccess() {
        global $usrmgr;
        global $dbmgr;

        $researcher = $usrmgr->m_user->researcher;
        $staff = $usrmgr->m_user->staff;

        return ($researcher == 1 || $staff == 1);
    }

    protected function startExport() {
        $terms = NULL;
        $classes = NULL;
        $format = 'sql';
        if (isset($_POST['semester'])) {
            $terms = $_POST['semester'];
        }
        if (isset($_POST['course'])) {
            $classes = $_POST['course'];
        }
        if (isset($_POST['format'])) {
            $format = $_POST['format'];
        }

        call_user_func(array('MStatsFile', $this->exportMethod), $terms, $classes, $format);
        header('Location: ' . $this->returnUrl);
    }

    protected function deleteFile() {
        $filename = $_POST['filename'];
        error_log("Deleting file: " . $filename . " (probably not an error)");
        $file_deleted = MStatsFile::delete_file($filename);

        header('Content-Type: application/javascript');
        echo json_encode(array('deleted' => $file_deleted));
        exit;
    }

    protected function downloadFile() {
        $filename = $_GET['download'];
        $base = realpath($GLOBALS["DIR_STATS"]);
        $path = realpath($base . '/' . $filename);
        if (strpos($path, $base) === 0) {
            header('Content-type: text/plain');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            readfile($path);
            exit;
        } else {
            global $usrmgr;
            error_log("WARNING: Bad filename attempted for download by '" . $usrmgr->GetUserId() . "': " . $filename);
            $this->showError();
        }
    }

    protected function showView() {
        $semesters = MSemester::get_semesters_and_response_counts(true);
        $files = array_filter(scandir($GLOBALS["DIR_STATS"],  SCANDIR_SORT_DESCENDING), array($this, 'fileMatches'));

        $head = new CHeadCSSJavascript($this->navLabel, array(), array());
        $tab_nav = new VTabNav(new MTabNav($this->navLabel));
        $content = new $this->viewClass($semesters, $this->courseCounts, $files);
        $page = new VPageTabs($head, $tab_nav, $content);

        echo $page->Deliver();
    }

    protected function showError() {
        http_response_code(403);
        echo "<p>Prohibited.  Please contact physics.sso@umich.edu if you are getting this message in error.</p><p><a href=\"selections.php\">Return to Problem Roulette</a></p>";
    }

    function run() {
        if (!$this->checkAccess()) {
            $this->showError();
        } elseif (isset($_POST['start_export'])) {
            $this->startExport();
        } elseif (isset($_POST['delete_file'])) {
            $this->deleteFile();
        } elseif (isset($_GET['download'])) {
            $this->downloadFile();
        } else {
            $this->showView();
        }
    }
}

