<?php
require_once(dirname(__FILE__).'/../rest.php');

class ProblemsResource extends Resource {

    function init() {
$this->problems_in_course =<<<SQL
SELECT p.id, p.name, p.url
FROM problems p
INNER JOIN 12m_topic_prob tp ON p.id = tp.problem_id
INNER JOIN 12m_class_topic ct ON tp.topic_id = ct.topic_id
WHERE ct.class_id = ?
SQL;
    }

    function get($path, $params) {
        global $dbmgr;
        $this->checkPath($path);
        $this->checkParams($params);

        $course_id = $params['course_id'];
        (array_key_exists('oldtopics', $params)) ? $oldtopics = $params['oldtopics'] : $oldtopics = '';
        if ($oldtopics == '') {
            $problems = $this->db->fetch_assoc($this->problems_in_course, array($course_id));
        } else {
            $query = "SELECT p.id, p.name, p.url ".
                     "FROM problems p INNER JOIN 12m_topic_prob tp ON p.id = tp.problem_id ".
                     "INNER JOIN 12m_class_topic ct ON tp.topic_id = ct.topic_id ".
                     "WHERE ct.class_id = :cid AND tp.topic_id IN ($oldtopics)";
            $bindings = array(":cid" => $course_id);
            $problems = $dbmgr->fetch_assoc($query, $bindings);
        }
        return array('course_id' => $course_id, 'problems' => $problems);
    }

    function checkPath($path) {
        if (count($path) !== 0) {
            $this->error(400, "Only the complete problem list may be retrieved.");
        }
    }

    function checkParams($params) {
        if (!isset($params['course_id']) || intval($params['course_id']) <= 0) {
            $this->error(400, "The `course_id` parameter is required.");
        }
    }

}

$resource = new ProblemsResource();
$resource->expose();


