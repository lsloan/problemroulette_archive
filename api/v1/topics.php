<?php
require_once(dirname(__FILE__).'/../rest.php');

$TOPICS =<<<EOF
{
	"4": [
		{ "value": "motion", "label": "Interactions and Motion" },
		{ "value": "momentum", "label": "The Momentum Principle" },
		{ "value": "interactions", "label": "The Fundamental Interactions" },
		{ "value": "contact", "label": "Contact Interactions" },
		{ "value": "force", "label": "Rate of Change of Momentum" },
		{ "value": "energy", "label": "The Energy Principle" },
		{ "value": "internal", "label": "Internal Energy" },
		{ "value": "multiparticle", "label": "Multiparticle Systems" },
		{ "value": "collisions", "label": "Collisions" },
		{ "value": "angMomentum", "label": "Angular Momentum" },
		{ "value": "elecFields", "label": "Electric Fields" },
		{ "value": "elecPot", "label": "Electric Potential" },
		{ "value": "magFields", "label": "Magnetic Fields" },
		{ "value": "circuits", "label": "Circuits" },
		{ "value": "magForces", "label": "Magnetic Force" },
		{ "value": "faraday", "label": "Faraday's Law" }
	],
	"5": [
		{ "value": "motion", "label": "Interactions and Motion x" },
		{ "value": "momentum", "label": "The Momentum Principle x" },
		{ "value": "interactions", "label": "The Fundamental Interactions x" },
		{ "value": "contact", "label": "Contact Interactions x" },
		{ "value": "force", "label": "Rate of Change of Momentum x" },
		{ "value": "energy", "label": "The Energy Principle x" },
		{ "value": "internal", "label": "Internal Energy x" },
		{ "value": "multiparticle", "label": "Multiparticle Systems x" },
		{ "value": "collisions", "label": "Collisions x" },
		{ "value": "angMomentum", "label": "Angular Momentum x" },
		{ "value": "elecFields", "label": "Electric Fields x" }
	],
	"6": [
		{ "value": "motion", "label": "Interactions and Motion" },
		{ "value": "momentum", "label": "The Momentum Principle" },
		{ "value": "interactions", "label": "The Fundamental Interactions" },
		{ "value": "contact", "label": "Contact Interactions" },
		{ "value": "force", "label": "Rate of Change of Momentum" },
		{ "value": "energy", "label": "The Energy Principle" },
		{ "value": "internal", "label": "Internal Energy" },
		{ "value": "multiparticle", "label": "Multiparticle Systems" },
		{ "value": "collisions", "label": "Collisions" },
		{ "value": "angMomentum", "label": "Angular Momentum" },
		{ "value": "elecFields", "label": "Electric Fields" },
		{ "value": "elecPot", "label": "Electric Potential" },
		{ "value": "magFields", "label": "Magnetic Fields" },
		{ "value": "circuits", "label": "Circuits" },
		{ "value": "magForces", "label": "Magnetic Force" },
		{ "value": "faraday", "label": "Faraday's Law" },
		{ "value": "extra1", "label": "Extra1" },
		{ "value": "extra2", "label": "Extra2" }
	],
	"7": [
		{ "value": "motion", "label": "Interactions and Motion" },
		{ "value": "momentum", "label": "The Momentum Principle" },
		{ "value": "interactions", "label": "The Fundamental Interactions" },
		{ "value": "contact", "label": "Contact Interactions" },
		{ "value": "force", "label": "Rate of Change of Momentum" },
		{ "value": "energy", "label": "The Energy Principle" },
		{ "value": "internal", "label": "Internal Energy" },
		{ "value": "multiparticle", "label": "Multiparticle Systems" }
	]
}
EOF;

$TOPICS = json_decode($TOPICS);

class TopicsResource extends Resource {

    function get($path, $params) {
        global $TOPICS;
        $this->checkPath($path);
        $this->checkParams($params);

        $course_id = $params['course_id'];
        $topics = $TOPICS->{$course_id};
        if ($topics == null) {
            $topics = array();   
        }
        return array('course_id' => $course_id, 'topics' => $topics);
    }

    function checkPath($path) {
        if (count($path) !== 0) {
            $this->error(400, "Only the complete topic list may be retrieved.");
        }
    }

    function checkParams($params) {
        if (!isset($params['course_id']) || intval($params['course_id']) <= 0) {
            $this->error(400, "The `course_id` parameter is required.");
        }
    }

}

$resource = new TopicsResource();
$resource->expose();


