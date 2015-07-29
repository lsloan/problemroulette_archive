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
        $oldtopics = (array_key_exists('oldtopics', $params)) ? $params['oldtopics'] : '';
        $oldtopics = $this->scrub_topics($oldtopics);

        $query = $this->getProblemsInCourseQuery(count($oldtopics));
        $bindings = array_merge(array($course_id), $oldtopics);

        $problems = $dbmgr->fetch_assoc($query, $bindings);
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

    function scrub_topics($topics) {
        $raw_topics = explode(',', $topics);
        $topics = array();
        foreach ($raw_topics as $topic) {
            $id = intval($topic);
            if ($id != 0) {
                $topics[] = $id;
            }
        }
        return $topics;
    }

    function getProblemsInCourseQuery($count) {
        $sql = $this->problems_in_course;

        if ($count > 0) {
            $sql .= ' AND tp.topic_id IN ';
            $in = '(?' . str_repeat(',?', $count - 1) . ')';
            $sql .= $in;
        }

        return $sql;
    }

}

$resource = new ProblemsResource();
$resource->expose();


