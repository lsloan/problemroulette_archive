<?php
require_once(__DIR__.'/../rest.php');

class CoursesResource extends Resource {
    function get($params) {
        $courses = $this->db->fetch_assoc('SELECT id, name FROM class');
        return array('courses' => $courses);
    }
}

$resource = new CoursesResource();
$resource->expose();

