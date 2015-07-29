<?php
require_once(dirname(__FILE__).'/../rest.php');

// Physics 140 = 4
// Physics 240 = 5
// Physics 135 = 6
// Physics 235 = 7
// Chemistry 130 = 10

$TOPICS =<<<EOF
{
    "4": [
        {"value": "vecAlgebra", "label": "Vector algebra"},
        {"value": "oneDimKinematics", "label": "1D kinematics"},
        {"value": "higherDimKinematics", "label": "2D/3D kinematics"},
        {"value": "relMotion", "label": "Relative motion"},
        {"value": "projMotion", "label": "Projectile motion"},
        {"value": "circMotion", "label": "Circular motion"},
        {"value": "forces", "label": "Forces"},
        {"value": "newtonsLaws", "label": "Newton's laws"},
        {"value": "workKinEnergy", "label": "Work and kinetic energy"},
        {"value": "consForcePotEnergy", "label": "Conservative forces and potential energy"},
        {"value": "mechEnergy", "label": "Mechanical energy"},
        {"value": "linMomentum", "label": "Linear momentum"},
        {"value": "collisoins", "label": "Collisions"},
        {"value": "cenMass", "label": "Center of mass"},
        {"value": "rotKinematics", "label": "Rotational kinematics"},
        {"value": "momInertia", "label": "Moment of inertia"},
        {"value": "torqueRotDyn", "label": "Torque and rotational dynamics"},
        {"value": "angMomentum", "label": "Angular momentum"},
        {"value": "rollMotion", "label": "Rolling motion"},
        {"value": "staticEquil", "label": "Static equilibrium"},
        {"value": "stressStrain", "label": "Stress and strain"},
        {"value": "periodicMotion", "label": "Periodic motion"},
        {"value": "mechWaves", "label": "Mechanical waves"},
        {"value": "sound", "label": "Sound"},
        {"value": "gravitation", "label": "Gravitational force / potential energy"},
        {"value": "orbitsKepLaws", "label": "Orbits / Kepler's laws"},
        {"value": "spherMassDist", "label": "Shell theorem / spherical mass distributions"},
        {"value": "fluidStatics", "label": "Fluid statics / bouyancy"},
        {"value": "fluidDynamics", "label": "Fluid dynamics"},
        {"value": "flagged", "label": "Flag this"}
    ],
    "5": [
        {"value": "coulombLaws", "label": "Coulomb's law"},
        {"value": "elecFields", "label": "Electric fields"},
        {"value": "elecDipoles", "label": "Electric dipoles"},
        {"value": "fluxGaussLaw", "label": "Flux and Gauss' law"},
        {"value": "elecPotential", "label": "Electric potential"},
        {"value": "elecPotEnergy", "label": "Electric potential energy"},
        {"value": "capacitance", "label": "Capacitance"},
        {"value": "currResistPower", "label": "Current, resistance, and power"},
        {"value": "dcCircuits", "label": "DC circuits"},
        {"value": "rcCircuits", "label": "RC circuits"},
        {"value": "magForce", "label": "Magnetic force"},
        {"value": "hallEffect", "label": "Hall effect"},
        {"value": "biotSavartLaw", "label": "Biot-Savart law"},
        {"value": "ampereLaw", "label": "Ampere's law"},
        {"value": "magMaterials", "label": "Magnetic materials"},
        {"value": "indEMFFaradayLaw", "label": "Induced EMF and Faraday's law"},
        {"value": "inductance", "label": "Inductors and inductance"},
        {"value": "rlcCircuits", "label": "RL, LC, RLC circuits"},
        {"value": "acCircuits", "label": "AC circuits"},
        {"value": "impedanceReactance", "label": "Impedance and reactance"},
        {"value": "resonance", "label": "Resonance"},
        {"value": "transformers", "label": "Transformers"},
        {"value": "maxwellsEqns", "label": "Maxwell's equations"},
        {"value": "displaceCurr", "label": "Displacement current"},
        {"value": "emWaves", "label": "Electromagnetic waves"},
        {"value": "radEnergyMomentum", "label": "Radiation energy and momentum"},
        {"value": "polarization", "label": "Polarization"},
        {"value": "refractionReflection", "label": "Refraction and reflection"},
        {"value": "lensMirrors", "label": "Lenses and mirrors"},
        {"value": "optInstrmt", "label": "Optical instruments"},
        {"value": "flagged", "label": "Flag this"}
    ],
    "6": [
        {"value": "scaling", "label": "Scaling"},
        {"value": "vectors", "label": "Vectors"},
        {"value": "oneDimKinematics", "label": "1D kinematics"},
        {"value": "twoDimKinematics", "label": "2D kinematics"},
        {"value": "projMotion", "label": "Projectile motion"},
        {"value": "circMotion", "label": "Circular motion"},
        {"value": "relMotion", "label": "Relative motion"},
        {"value": "newtonsLaws", "label": "Newton’s Laws"},
        {"value": "workKinEnergy", "label": "Work and kinetic energy"},
        {"value": "consForcePotEnergy", "label": "Conservative forces and potential energy"},
        {"value": "mechEnergy", "label": "Mechanical energy"},
        {"value": "staticEquil", "label": "Static equilibrium"},
        {"value": "elasticity", "label": "Elasticity"},
        {"value": "linMomentum", "label": "Linear Momentum"},
        {"value": "consLinMomentum", "label": "Conservation of linear momentum"},
        {"value": "friction", "label": "Friction"},
        {"value": "collisions", "label": "Collisions "},
        {"value": "perdiodicMotion", "label": "Periodic motion"},
        {"value": "thermalProp", "label": "Thermal properties of matter"},
        {"value": "kinTheoryGas", "label": "Kinetic theory of gases"},
        {"value": "statMechEntropy", "label": "Statistical mechanics and entropy"},
        {"value": "diffusionOsmosis", "label": "Diffusion and osmosis"},
        {"value": "thermalTransport", "label": "Thermal transport"},
        {"value": "thermodynamics", "label": "Thermodynamics"},
        {"value": "fluidStatics", "label": "Fluid statics / bouyancy"},
        {"value": "fluidMotionWOFriction", "label": "Fluid motion without friction"},
        {"value": "fluidMotionWFriction", "label": "Fluid motion with friction"},
        {"value": "fluidSurfaceTension", "label": "Fluid surface tension"},
        {"value": "flagged", "label": "Flag this"}
    ],
    "7": [
        {"value": "coulombLaw", "label": "Coulomb's law"},
        {"value": "conductorInsulator", "label": "Conductors and insulators"},
        {"value": "elecFields", "label": "Electric fields"},
        {"value": "elecDipoles", "label": "Dipoles"},
        {"value": "elecPot", "label": "Electric potential and potential energy"},
        {"value": "elecPotGradField", "label": "Electric potential gradients and fields"},
        {"value": "capacitorDielectric", "label": "Capacitors and dielectrics"},
        {"value": "currCurrDens", "label": "Current and curent density "},
        {"value": "resistance", "label": "Resistance and resistivity"},
        {"value": "dcCircuits", "label": "DC circuits"},
        {"value": "rcCircuitsExpDecayGrowth", "label": "RC circuits / exponential decay and growth"},
        {"value": "magFieldsMovCharges", "label": "Magnetic fields and moving charges"},
        {"value": "massSpectrometer", "label": "Mass spectrometers"},
        {"value": "motorGenerator", "label": "Motors and generators"},
        {"value": "inductionFaradayLaw", "label": "Induction and Faraday's law"},
        {"value": "displaceCurr", "label": "Displacement current"},
        {"value": "medicalImaging", "label": "Medical imaging "},
        {"value": "waveProp", "label": "Wave properties"},
        {"value": "waveSuperposInterference", "label": "Wave superposition and interference"},
        {"value": "diffraction", "label": "Diffraction "},
        {"value": "standWavesInstruments", "label": "Standing waves and musical instruments"},
        {"value": "soundWaves", "label": "Sound waves"},
        {"value": "emWaves", "label": "Electromagnetic waves"},
        {"value": "imagingOptInstruments", "label": "Imaging and optical instruments"},
        {"value": "propagationLight", "label": "Propagation of light"},
        {"value": "nucPhysRadDecay", "label": "Nuclear physics and radioactive decay "},
        {"value": "cosmologyAstrobiology", "label": "Cosmology and astrobiology "},
        {"value": "atomsQuantumMech", "label": "Atoms and quantum mechanics"},
        {"value": "flagged", "label": "Flag this"}
    ],
    "10": [
        {"value": "measureCalc", "label": "Measurements and Calculations"},
        {"value": "chemFound", "label": "Chemical Foundations"},
        {"value": "atomicStrucPeriod", "label": "Atomic Structure and Periodicity"},
        {"value": "bonding", "label": "Bonding"},
        {"value": "molecStructOrbit", "label": "Molecular Structure and Orbitals"},
        {"value": "stoich", "label": "Stoichiometry"},
        {"value": "chemReactSoln", "label": "Types of Chemical Reactions and Solution Stoichiometry"},
        {"value": "chemEnergy", "label": "Chemical Energy"},
        {"value": "gases", "label": "Gases"},
        {"value": "liquidSolid", "label": "Liquids and Solids"},
        {"value": "chemEquilib", "label": "Chemical Equilibrium"},
        {"value": "acidBase", "label": "Acids and Bases"},
        {"value": "flagged", "label": "Flag this"}
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


