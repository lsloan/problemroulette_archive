<?php
require_once(dirname(__FILE__).'/../rest.php');

class CoursesResource extends Resource {
    function get($path, $params) {
        $this->checkPath();

        $courses = $this->db->fetch_assoc('SELECT id, name FROM class');
        return array('courses' => $courses);
    }

    function checkPath($path) {
        if (count($path) !== 0) {
            $this->error(400, "Only the complete course list may be retrieved.");
        }
    }
}

$resource = new CoursesResource();
$resource->expose();

