<?php
function GrabAllArgs()
{
    $a = array();
    if( count( $_POST ) ){
        $a = array_merge( $a, $_POST );
    }
    if( count( $_GET ) ){
        $a = array_merge( $a, $_GET );
    }
    return $a;
}

function MakeArray($variable) {
  if (! is_array($variable)) {
    $variable = array($variable);
  }
  return $variable;
}

// this creates options for a select input. $array contains  key, value
// where the key is the option label and the value is the option value.
function MakeSelectOptions($array, $curvalue=1, $letters=false)
{ $str = '';
  $alphabet = Array('0', 'A','B','C','D','E','F','G','H','I','J');
  foreach ($array as $key => $value)
  {
    $str .= "<option";
    if ($key == $curvalue || $key == $alphabet[$curvalue]) {
      $str .= " selected='selected' ";
    }
    if ($letters == 'letters') {
      $str .= " value='".$value."'> ". $alphabet[$value]." </option>";
    }
    else {
      $str .= " value='".$value."'> ".$key." </option>";
    }
  }
  return $str;
}

// curlabel is array of current topics the problem is in, $array is the entire list of topics
function MakeSelectTopicOptions($array, $curvalue)
{ $str = '';
  foreach ($array as $key => $value)
  {
    $str .= "<option";
    if (in_array($key, $curvalue)) {
      $str .= " selected='selected' ";
    }
    $str .= " value='".$value."'> ".$key." </option>";
  }
  return $str;
}
// creates an array of key,value to use in creating the options for selecting how many answer choices
function AnswerNumbers($num_values = NULL)
{ //set $num_values = to the number of answer number choices allowed
  if ($num_values == NULL) { $num_values = 10; }
  $array = array();
  for ($i=1; $i<$num_values+1; $i++) {
    $array[$i] = $i;
  }
  return $array;
}

if (!function_exists('http_response_code')) {
  function http_response_code($code = NULL) {

    if ($code !== NULL) {

      switch ($code) {
        case 100: $text = 'Continue'; break;
        case 101: $text = 'Switching Protocols'; break;
        case 200: $text = 'OK'; break;
        case 201: $text = 'Created'; break;
        case 202: $text = 'Accepted'; break;
        case 203: $text = 'Non-Authoritative Information'; break;
        case 204: $text = 'No Content'; break;
        case 205: $text = 'Reset Content'; break;
        case 206: $text = 'Partial Content'; break;
        case 300: $text = 'Multiple Choices'; break;
        case 301: $text = 'Moved Permanently'; break;
        case 302: $text = 'Moved Temporarily'; break;
        case 303: $text = 'See Other'; break;
        case 304: $text = 'Not Modified'; break;
        case 305: $text = 'Use Proxy'; break;
        case 400: $text = 'Bad Request'; break;
        case 401: $text = 'Unauthorized'; break;
        case 402: $text = 'Payment Required'; break;
        case 403: $text = 'Forbidden'; break;
        case 404: $text = 'Not Found'; break;
        case 405: $text = 'Method Not Allowed'; break;
        case 406: $text = 'Not Acceptable'; break;
        case 407: $text = 'Proxy Authentication Required'; break;
        case 408: $text = 'Request Time-out'; break;
        case 409: $text = 'Conflict'; break;
        case 410: $text = 'Gone'; break;
        case 411: $text = 'Length Required'; break;
        case 412: $text = 'Precondition Failed'; break;
        case 413: $text = 'Request Entity Too Large'; break;
        case 414: $text = 'Request-URI Too Large'; break;
        case 415: $text = 'Unsupported Media Type'; break;
        case 500: $text = 'Internal Server Error'; break;
        case 501: $text = 'Not Implemented'; break;
        case 502: $text = 'Bad Gateway'; break;
        case 503: $text = 'Service Unavailable'; break;
        case 504: $text = 'Gateway Time-out'; break;
        case 505: $text = 'HTTP Version not supported'; break;
        default:
            exit('Unknown http status code "' . htmlentities($code) . '"');
        break;
      }

      $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

      header($protocol . ' ' . $code . ' ' . $text);

      $GLOBALS['http_response_code'] = $code;

    } else {

        $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

    }

    return $code;

  }
}

if(! defined("DIRECTORY_SEPARATOR")) {
  define("DIRECTORY_SEPARATOR", '/');
}
if(! defined("PATH_SEPARATOR")) {
  define("PATH_SEPARATOR", ':');
}
if(! defined("SCANDIR_SORT_ASCENDING")) {
  define("SCANDIR_SORT_ASCENDING", 0);
}
if(! defined("SCANDIR_SORT_DESCENDING")) {
  define("SCANDIR_SORT_DESCENDING", 1);
}
if(! defined("SCANDIR_SORT_NONE")) {
  define("SCANDIR_SORT_NONE", 2);
}

function prob_list_sorter($a,$b) {
  return strcasecmp($a->m_prob_name, $b->m_prob_name);
}

 function getCourseId() {
  global $usrmgr;
  $course_id = $usrmgr->m_user->selected_course_id;
  return $course_id;
}

 function getCourseName($courseId) {
  $course=  MCourse::get_course_by_id($courseId);
  return $course->m_name;
}

function getTopicName($topicId) {
  $topic = MTopic::get_topic_by_id($topicId);
  return $topic->m_name;
}

function isInTopicsView(){
  $isInTopicsView=false;
 if(isset($_POST['course_submission'])){
   $isInTopicsView= true;
 }
  return $isInTopicsView;
}

function getProblem($problemId) {
  return MProblem::find($problemId);
}

function getUserName() {
  global $usrmgr;
  return $usrmgr->m_user->username;
}

function getSelectedTopicList() {
  global $usrmgr;
  return $usrmgr->m_user->selected_topics_list;
}

function getUserId() {
  global $usrmgr;
  return $usrmgr->m_user->id;
}

function getAttemptCount($problem_id) {
  global $usrmgr;
  $count=intval(MResponse::get_total_attempts_for_user_for_problem($usrmgr->m_user->id, $problem_id));
  return $count;
}


?>
