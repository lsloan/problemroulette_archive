<?php
require_once(dirname(__FILE__).'/../rest.php');

// Physics 140 = 4
// Physics 240 = 5
// Physics 135 = 6
// Physics 235 = 7

$TOPICS =<<<EOF
{
	"4": [
        {"value": "vecAlgebra", "label": "Vector algebra"},
        {"value": "oneDimMotion", "label": "1D motion"},
        {"value": "transMotion", "label": "Translational motion"},
        {"value": "circMotion", "label": "Circular motion"},
        {"value": "forces", "label": "Forces"},
        {"value": "newtonsLaws", "label": "Newton's laws"},
        {"value": "work", "label": "Work"},
        {"value": "mechEnergy", "label": "Mechanical energy"},
        {"value": "linMomentum", "label": "Linear momentum"},
        {"value": "collisions", "label": "Collisions"},
        {"value": "centerMass", "label": "Center of mass"},
        {"value": "rotMotion", "label": "Rotational motion"},
        {"value": "torque", "label": "Torque"},
        {"value": "angMomentum", "label": "Angular momentum"},
        {"value": "rollMotion", "label": "Rolling motion"},
        {"value": "staticEquil", "label": "Static equilibrium"},
        {"value": "stressStrain", "label": "Stress and strain"},
        {"value": "periodicMotion", "label": "Periodic motion"},
        {"value": "mechWaves", "label": "Mechanical waves"},
        {"value": "gravitation", "label": "Gravitation"},
        {"value": "fluidMech", "label": "Fluid mechanics"},
        {"value": "fluidStatics", "label": "Fluid statics"},
        {"value": "other", "label": "Other"}
	],
	"5": [
        {"value": "elecFields", "label": "Electric fields"},
        {"value": "elecDipoles", "label": "Electric dipoles"},
        {"value": "gaussLaw", "label": "Gauss' Law"},
        {"value": "elecPot", "label": "Electric Potential"},
        {"value": "elecPotEnergy", "label": "Electric Potential Energy"},
        {"value": "capacitance", "label": "Capacitance"},
        {"value": "currResistPower", "label": "Current, resistance, and power"},
        {"value": "circuits", "label": "Circuits"},
        {"value": "magForce", "label": "Magnetic force"},
        {"value": "biotSavartLaw", "label": "Biot-Savart law"},
        {"value": "ampereLaw", "label": "Ampere's law"},
        {"value": "magMaterials", "label": "Magnetic materials"},
        {"value": "inducedEMF", "label": "Induced EMF"},
        {"value": "inductance", "label": "Inductors and inductance"},
        {"value": "rlcCircuits", "label": "RL, LC, RLC circuits"},
        {"value": "acCircuits", "label": "AC circuits"},
        {"value": "maxwellEqns", "label": "Maxwell's Equations"},
        {"value": "displaceCurr", "label": "Displacement current"},
        {"value": "emWaves", "label": "Electromagnetic waves"},
        {"value": "polarization", "label": "Polarization"},
        {"value": "refracReflec", "label": "Refraction and Reflection"},
        {"value": "other", "label": "Other"}
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


