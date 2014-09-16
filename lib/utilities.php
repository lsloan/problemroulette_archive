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

function MakeSelections($curval)
{
  for ($j=1; $j<11; $j++)
  {
    $str .= "<option";
    if ($j == $curval) {
      $str .= " selected='selected' ";
    }
    $str .= " value='".$j."'> ".$j." </option>";
  }
  return $str;
}

?>
