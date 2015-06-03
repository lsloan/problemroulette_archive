<?php
require_once(__DIR__.'/../rest.php');

class VotesResource extends Resource {

    function init() {
$this->votes_in_course =<<<SQL
SELECT v.id, v.problem_id, v.user_id, v.topics,
DATE_FORMAT(v.created_at, '%Y-%m-%dT%TZ') created_at, DATE_FORMAT(v.updated_at, '%Y-%m-%dT%TZ') updated_at
FROM votes v
INNER JOIN 12m_topic_prob tp ON v.problem_id = tp.problem_id
INNER JOIN 12m_class_topic ct ON tp.topic_id = ct.topic_id
WHERE ct.class_id = ? AND v.user_id = ?
SQL;

// With the ON DUPLICATE KEY syntax, the topics must be supplied both as
// the initial value and the update value, the 3rd and 4th parameters.
$this->save_vote =<<<SQL
INSERT INTO votes
(problem_id, user_id, topics, created_at, updated_at)
VALUES(?, ?, ?, NULL, NULL)
ON DUPLICATE KEY UPDATE
created_at=created_at, updated_at = now(), topics = ?
SQL;
    }

    function get($path, $params) {
        $this->checkPath($path);
        $this->checkParams($params);

        $course_id = $params['course_id'];
        $user_id = $this->current_user->id;
        $votes = $this->db->fetch_assoc($this->votes_in_course, array($course_id, $user_id));
        foreach ($votes as $k => $vote) {
            $topics = json_decode($vote['topics']);
            if ($topics === null) {
                $topics = array();
            }
            $votes[$k]['topics'] = $topics;
        }
        return array('course_id' => $course_id, 'votes' => $votes);
    }

    function post($path, $params) {
        $this->checkPath($path);
        $this->checkPostParams($params);

        $problem_id = $params['problem_id'];
        $topics = $params['topics'];
        $user_id = $this->current_user->id;

        if (!is_array($topics)) {
            $topics = array($topics);
        }
        $topics = json_encode($topics);

        $this->db->exec_query($this->save_vote, array($problem_id, $user_id, $topics, $topics));
        return array('success' => true);
    }

    function checkPath($path) {
        if (count($path) !== 0) {
            $this->error(400, "Only the complete vote list (for the current user) may be retrieved.");
        }
    }

    function checkParams($params) {
        if (!isset($params['course_id']) || intval($params['course_id']) <= 0) {
            $this->error(400, "The `course_id` parameter is required.");
        }
    }

    function checkPostParams($params) {
        $required = array('problem_id', 'topics');

        if (!isset($params['problem_id']) || intval($params['problem_id']) <= 0) {
            $this->error(400, "The `problem_id` parameter is required.");
        }

        if (!isset($params['topics'])) {
            $this->error(400, "The `topics[]` parameter is required.");
        }
    }

}

$resource = new VotesResource();
$resource->expose();

