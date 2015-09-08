<?php
require_once(dirname(__FILE__).'/../setup.php');
if (!$usrmgr->m_user->admin) {
    // TODO: Extract the error handling from the REST utility to standalone
    $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
    header($protocol . ' 403 Forbidden');
    echo "<h1>403 - Forbidden</h1>";
    exit;
}

$importer = new Importer;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $importer->run();
}
else {
    $importer->show_form();
}

class Importer {
    var $course_id = null;
    var $tfile = null;
    var $vfile = null;
    var $vf = null;
    var $tf = null;
    var $topics = array();
    var $errors = array();
    var $db = null;

    function __construct() {
        global $dbmgr;
        $this->db = $dbmgr;
    }
    
    function init() {
        $this->course_id = !empty($_POST['course_id']) ? $_POST['course_id'] : null;
        $this->tfile = !empty($_FILES['topics_csv']['tmp_name']) ? $_FILES['topics_csv']['tmp_name'] : null;
        $this->vfile = !empty($_FILES['problems_csv']['tmp_name']) ? $_FILES['problems_csv']['tmp_name'] : null;
        $this->tf = false;
        $this->vf = false;
        if (!empty($this->tfile)) {
            $this->tf = fopen($this->tfile, "r");
        }
        if (!empty($this->vfile)) {
            $this->vf = fopen($this->vfile, "r");
        }

        if ($this->course_id == null || $this->tfile == null || $this->vfile == null) {
            $this->cleanup();
            $this->redirect(array('required' => 1));
        }

        if ($this->tf === false || $this->vf === false) {
            $this->cleanup();
            $this->redirect(array('bad_file' => 1));
        }

$this->new_topic =<<<EOQ
INSERT INTO topic (name) VALUES(?)
EOQ;

$this->new_ct =<<<EOQ
INSERT INTO 12m_class_topic (class_id, topic_id)
VALUES(?, ?)
EOQ;

$this->new_tp =<<<EOQ
INSERT INTO 12m_topic_prob (problem_id, topic_id)
VALUES(?, ?)
EOQ;

    }

    function makeTopics() {
        while (($row = fgetcsv($this->tf)) !== false) {
            list($topic, $key) = $row;

            $id = $this->db->handle_insert($this->new_topic, array($topic));
            if ($id == null) {
                $this->errors[] = "Could not create topic $key: $topic";
            } else {
                $this->topics[$key] = array('name' => $topic, 'id' => $id);
                $this->db->handle_insert($this->new_ct, array($this->course_id, $id));
            }
        }
    }

    function linkProblems() {
        while (($row = fgetcsv($this->vf)) !== false) {
            list($key, $problem_id) = $row;
            $topic_id = isset($this->topics[$key]) ? $this->topics[$key]['id'] : null;
            if ($topic_id == null) {
                $this->errors[] = "Cannot find $key, so $problem_id cannot be assigned.";
            } else {
                $this->db->handle_insert($this->new_tp, array($problem_id, $topic_id));
            }
        }
    }

    function showConfirmation() {
        $_SESSION['import-message'] = 'Import completed.';
        $_SESSION['import-errors'] = $this->errors;
        $this->redirect();
    }

    function run() {
        $this->init();
        $this->makeTopics();
        $this->linkProblems();
        $this->showConfirmation();
    }

    function redirect($params = array()) {
        header("Location: " . $this->selfUrl($params));
        exit;
    }

    function selfUrl($params = array()) {
        $query = "";
        if (count($params) > 0) {
            $query = "?";
            foreach ($params as $key => $value) {
                $query .= $key . "=" .$value;
            }
        }
            
        return $_SERVER['PHP_SELF'] . $query;
    }

    function cleanup() {
        if ($this->tf !== false) {
            fclose($tf);
        }
        
        if ($this->vf !== false) {
            fclose($vf);
        }
    }

    function show_form() {
        $message = isset($_SESSION['import-message']) ? $_SESSION['import-message'] : "";
        $errors = isset($_SESSION['import-errors']) ? $_SESSION['import-errors'] : array();
        unset($_SESSION['import-message']);
        unset($_SESSION['import-errors']);
        $courses = MCourse::get_all_courses();

ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Problem Topic Import</title>
<body>
<h1>Import New Topics for Problems</h1>
<?php if (isset($_GET['bad_file'])): ?>
<h3>There was an error with one of the uploaded files. Please try again.</h3>
<?php endif; ?>
<?php if ($message != ""): ?>
<h3><?= $message ?></h3>
<?php endif; ?>

<?php if (count($errors) >0): ?>
<h3>Import Errors:</h3>
<pre style="border: 1px solid gray;">
<?php foreach ($errors as $error): ?>
<?= $error . "\n" ?>
<?php endforeach; ?>
</pre>
<?php endif; ?>

<p><em>All fields are required.</em></p>
<form method="post" enctype="multipart/form-data">
<div>
<label for="course_id">Course ID: </label>
<select id="course_id" name="course_id">
<?php foreach($courses as $course): ?>
    <option value="<?= $course->m_id ?>"><?= $course->m_name; ?></option>
<?php endforeach; ?>
</select>
</div>
<div>
<label for="topics_csv">Topic CSV file: </label>
<input id="topics_csv" type="file" name="topics_csv" required>
</div>
<div>
<label for="problems_csv">Problems CSV file: </label>
<input id="problems_csv" type="file" name="problems_csv" required>
</div>
<div>
<input type="submit" name="submit" value="Submit"/>
</div>
</form>
</body>
</html>
<?php print(ob_get_clean());
    }

}

