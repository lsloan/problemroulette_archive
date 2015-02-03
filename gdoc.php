<?php

if (isset($_GET['url']))
{
  $prob_url = $_GET['url'];
  ob_start(); ?>
  <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta http-equiv="content-type" content="text/html; charset=windows-1252">
        <title>Problem</title>
        <script src="js/jquery-1.10.1.js"></script>
        <style type="text/css">
          body {
            font-family: arial, sans, sans-serif;
            margin: 0;
          }

          iframe {
            border: 0;
            frameborder: 0;
            height: 100%;
            width: 100%;
          }

          #header, #footer {
            background: #f0f0f0;
            padding: 10px 10px;
          }

          #header {
            border-bottom: 1px #ccc solid;
          }

          #footer {
            border-top: 1px #ccc solid;
            border-bottom: 1px #ccc solid;
            font-size: 13;
          }

          #contents {
            margin: 6px;
          }

          .dash {
            padding: 0 6px;
          }
        </style>            
            
    </head>
    <body>

      <div id="problem">
      </div>


      <script type="text/javascript">
      $(document).ready(function(){
        $('div#problem').load("<?= $prob_url ?> div#contents");
      });
      </script>
    </body>
  <?php echo ob_get_clean();

}
else
{
  http_response_code(403);
  echo "No URL provided";
}

?>