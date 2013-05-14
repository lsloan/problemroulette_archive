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

?>
