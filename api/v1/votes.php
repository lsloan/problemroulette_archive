<?php
require_once(dirname(__FILE__).'/../rest.php');

class VotesResource extends Resource {

    function init() {
$this->votes_in_course =<<<SQL
SELECT v.id, v.problem_id, v.user_id, v.topic,
DATE_FORMAT(v.created_at, '%Y-%m-%dT%TZ') created_at
FROM votes v
INNER JOIN 12m_topic_prob tp ON v.problem_id = tp.problem_id
INNER JOIN 12m_class_topic ct ON tp.topic_id = ct.topic_id
WHERE ct.class_id = ? AND v.user_id = ?
SQL;

$this->save_vote =<<<SQL
INSERT INTO votes
(problem_id, user_id, topic)
VALUES(?, ?, ?)
SQL;

$this->clear_vote =<<<SQL
DELETE FROM votes
WHERE problem_id = ? AND user_id = ?
SQL;
    }

    function get($path, $params) {
        $this->checkPath($path);
        $this->checkParams($params);

        $course_id = $params['course_id'];
        $user_id = $this->current_user->id;
        $votes = $this->getVotes($course_id, $user_id);

        return array('course_id' => $course_id, 'votes' => $votes);
    }

    function getVotes($course_id, $user_id) {
        $rs = $this->db->fetch_assoc($this->votes_in_course, array($course_id, $user_id));

        $votes = array();
        foreach ($rs as $k => $vote) {
            $problem_id = $vote['problem_id'];
            if (!isset($votes[$problem_id])) {
                $votes[$problem_id] = array(
                    'problem_id' => $problem_id,
                    'user_id'    => $vote['user_id'],
                    'topics'     => array(),
                    'created_at' => $vote['created_at']
                );
            }
            $votes[$problem_id]['topics'][] = $vote['topic'];
        }
        return array_values($votes);
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

        $this->saveVote($problem_id, $user_id, $topics);
        return array('success' => true);
    }

    function saveVote($problem_id, $user_id, $topics) {
        $this->db->exec_query($this->clear_vote, array($problem_id, $user_id));

        foreach ($topics as $topic) {
            $this->db->exec_query($this->save_vote, array($problem_id, $user_id, $topic));
        }
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

