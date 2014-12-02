<?php

register_shutdown_function( 'shutdownHandler' );

function shutdownHandler() {

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
       $message = "Error (type " . $e['type'] . ") in " . $e['file'] . " at line " . $e['line'] . ": " . $e['message'];
       error_log($message);
       header( 'HTTP/1.1 500 Internal Server Error' );
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Internal Server Error</title>
<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="error-page">
        <h1>Internal Server Error</h1>
        <img class='logo' src='img/PR.jpg' width='200px' alt='Problem Roulette'/>
        <p>
            Please try again or contact <a href="mailto:physics.sso@umich.edu">physics.sso@umich.edu</a> with any problems.
        </p>
        <p><a href="<?= $GLOBALS['DOMAIN'] ?>">Problem Roulette Home</a></p>
    </div>
    <?php if (ini_get('display_errors') == 1): ?>
        <pre><?php echo htmlentities(print_r($e,true));?></pre>
    <?php endif; ?>
</body>
</html>
<?php
    exit(1);
   }
}
