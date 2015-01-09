<?php
////////////////////////////////////////////////////////////////////////////////
//  logger.php
//------------------------------------------------------------------------------
//  Application Logger Module
//
//

class AppLogger
{
  var $destination;

  function AppLogger($destination)
  {
    $this->destination = $destination;
  }

  function msg($message)
  {
    error_log(date('c').' '.$message."\n", 3, $this->destination);
  }
}

?>