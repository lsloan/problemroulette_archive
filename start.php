<!DOCTYPE html>
<html>
  <head>
    <title>Problem Roulette</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.css" rel="stylesheet" media="screen">
    <link href="css/styles.css" rel="stylesheet" media="screen">
    <script src="trackingcode.js"></script>
    <script src="js/jquery-1.10.1.js"></script>
     <script type="text/javascript">
        $(document).ready(function() {
          $(".topic-selector input:checkbox").click(function() {
            if ($(this).prop('checked')) {
              $(this).parents("li").addClass("checked");  
            } else {
              $(this).parents("li").removeClass("checked");
            }
            var count = 0;
            $(".topic-selector input:checkbox").each(function() {
              if ($(this).prop("checked")) {
                count++;
              }
            })
            if (count > 0) {
                $("#use-selected").removeClass("disabled");
            } else {
                $("#use-selected").addClass("disabled");
            }
          });

          $('a#statistics-tab').click(function() {
             $.get('statistics.php', function(data) {
                $("#statistics").html(data);
             });
          });

        });


        /*
        $('a#statistics-tab').on('shown', function (e) {
          e.target // activated tab
          e.relatedTarget // previous tab
          alert("stats tab!");
        });
        */

     </script>
  </head>
  <body>
    <div id="wrap">
      <div class="container">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#problems" data-toggle="tab">Problems</a></li>
          <li><a id="statistics-tab" href="#statistics" data-toggle="tab">Statistics</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="problems">