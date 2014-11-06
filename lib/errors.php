<?php

register_shutdown_function( 'shutdownHandle' );

function shutdownHandle() {

   $e = error_get_last();

    if (
        in_array(
            $e['type'],
            array(
                E_PARSE,
                E_ERROR,
                E_COMPILE_ERROR,
                E_COMPILE_WARNING,
                E_USER_ERROR
            )
        )
    ) {
       ob_clean();
       header( 'HTTP/1.1 500 Internal Server Error' );
       echo '<div class="error-page">'
       echo '<h1>Internal Server Error</h1>';
       echo "<img class='logo' src='img/PR.jpg' width='200px' alt='Problem Roulette'/>";
       echo '<p>';
       echo 'Please contact <a href="mailto:physics.sso@umich.edu">physics.sso@umich.edu</a> with any problems.';
       echo '</p>';
       // whatever type of output you want including mail(), public message, etc.
       // in this case debug info
       print_r($e);
       echo '</div>'
       exit(1);
   }

}

?>
