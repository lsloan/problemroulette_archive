<?php
require_once(__DIR__.'/../setup.php');
if (!($usrmgr->m_user->voter || $usrmgr->m_user->admin)) {
    // TODO: Extract the error handling from the REST utility to standalone
    $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
    header($protocol . ' 403 Forbidden');
    echo "<h1>403 - Forbidden</h1>";
    exit;
}

$sql =<<<EOQ
SELECT DISTINCT
c.id course_id, c.name course_name, p.id problem_id, p.name problem_name, p.url problem_url, u.id user_id, u.username username, v.topic topic, v.created_at vote_date
FROM class c
INNER JOIN 12m_class_topic ct ON c.id = ct.class_id
INNER JOIN 12m_topic_prob tp ON ct.topic_id = tp.topic_id
INNER JOIN problems p ON tp.problem_id = p.id
INNER JOIN votes v ON p.id = v.problem_id
INNER JOIN user u ON v.user_id = u.id
EOQ;

$stdout = fopen('php://output', 'w');

if (!($_GET['download'] == 0)) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=voting-results.csv');
}

$rows = $dbmgr->fetch_assoc($sql);
fputcsv($stdout, array_keys($rows[0]));
foreach ($rows as $row) {
    fputcsv($stdout, array_values($row));
}

?>
