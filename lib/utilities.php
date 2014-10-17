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
// where the key is the option label and the value is the option value
function MakeSelectOptions($array, $curlabel)
{
  foreach ($array as $key => $value)
  {
    $str .= "<option";
    if ($key == $curlabel) {
      $str .= " selected='selected' ";
    }
    $str .= " value='".$value."'> ".$key." </option>";
  }
  return $str;
}

// creates an array of key,value to use in creating the options for selecting how many answer choices
function AnswerNumbers()
{ //set $num_values = to the number of answer number choices allowed
  $num_values = 10;
  $array = array();
  for ($i=1; $i<$num_values+1; $i++) {
    $array[$i] = $i;
  }
  return $array;
}

?>
