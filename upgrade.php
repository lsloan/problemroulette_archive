<?php

// All registered migrations, mapping the class name to the file in migrations/.
// Each item should have name and file keys. For example:
//   array('name' => 'Example', 'file' => 'example.php')
$migrations = array(
    array('name' => 'AddAnsCorrectToResponses', 'file' => 'add_ans_correct_to_responses.php'),
    array('name' => 'AddSemestersTable', 'file' => 'add_semesters_table.php'),
    array('name' => 'AddIndexesForStatsExport', 'file' => 'add_indexes_for_stats_export.php'),
    array('name' => 'AddResearcherToUser', 'file' => 'add_researcher_to_user.php'),
    array('name' => 'AddGlobalAlertsTable', 'file' => 'add_global_alerts_table.php'),
    array('name' => 'AddAdminToUser', 'file' => 'add_admin_to_user.php'),
    array('name' => 'CombineDuplicateProblemData', 'file' => 'combine_duplicate_problem_data.php'),
    array('name' => 'DropTempTables', 'file' => 'drop_temp_tables.php'),
    array('name' => 'FixStatsInProblemsTable', 'file' => 'fix_stats_in_problems_table.php'),
    array('name' => 'FixTotTimeInProblemsTable', 'file' => 'fix_tot_time_in_problems_table.php'),
    array('name' => 'AddCreatedAtToUser', 'file' => 'add_created_at_to_user.php'),
    array('name' => 'AddUniqueIndexForUserUsername', 'file' => 'add_unique_index_for_user_username.php'),
    array('name' => 'AddUniqueIndexForResponses', 'file' => 'add_unique_index_for_responses.php'),
    array('name' => 'AddRatingScalesTable', 'file' => 'add_rating_scales_table.php'),
    array('name' => 'AddRatingsTable', 'file' => 'add_ratings_table.php'),
    array('name' => 'AddDisableRatingToClass', 'file' => 'add_disable_rating_to_class.php'),
    array('name' => 'AddUniqueIndexForResponses', 'file' => 'add_unique_index_for_responses.php'),
    array('name' => 'AddDelaySolutionToClass', 'file' => 'add_delay_solution_to_class.php'),
    array('name' => 'AddInactiveToTopic', 'file' => 'add_inactive_to_topic.php'),
    array('name' => 'EnsureTalliesForAllPossibleAnswers', 'file' => 'ensure_tallies_for_all_possible_answers.php'),
    array('name' => 'AddTopicToResponses', 'file' => 'add_topic_to_responses.php'),
    array('name' => 'SetTopicIdInOldResponses', 'file' => 'set_topic_id_in_old_responses.php'),
    array('name' => 'AddVotesTable', 'file' => 'add_votes_table.php'),
    array('name' => 'AddVoterToUser', 'file' => 'add_voter_to_user.php'),
    array('name' => 'AddCaliperEventsTable', 'file' => 'add_caliper_events_table.php'),
);
require_once("setup.php");
require_once($GLOBALS["DIR_LIB"]."migration.php");

$Q = array();
$Q['setup'] =<<<SQL
CREATE TABLE IF NOT EXISTS migrations (
    name VARCHAR(255) NOT NULL PRIMARY KEY,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
SQL;

$Q['last_run'] =<<<SQL
SELECT name, timestamp FROM migrations
ORDER BY timestamp DESC
LIMIT 1
SQL;

$Q['all_run'] =<<<SQL
SELECT name, timestamp FROM migrations
ORDER BY timestamp ASC
SQL;

$Q['record'] =<<<SQL
INSERT INTO migrations (name)
VALUES(?)
SQL;

$dbmgr->exec_query($Q['setup']);

$last_run = $dbmgr->fetch_assoc($Q['last_run']);
$last_run = $last_run[0];
$newest = end((array_values($migrations)));
echo "<pre>\n";

if (!isset($last_run['name']) || $last_run['name'] != $newest['name']) {

    $all_run = $dbmgr->fetch_column($Q['all_run']);
    $remaining = array();

    foreach($migrations as $m) {
        if (false === array_search($m['name'], $all_run)) {
            $remaining[] = $m;
        }
    }

    foreach($remaining as $m) {
        require_once("migrations/" . $m['file']);
        $class = new ReflectionClass($m['name']);
        $mig = $class->newInstance();
        $mig->run();
        $dbmgr->exec_query($Q['record'], array($m['name']));
        sleep(1); //to ensure different timestamps
    }
}

echo "\n</pre>\n";

?>
All migrations have been run.
