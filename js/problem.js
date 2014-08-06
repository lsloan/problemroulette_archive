$(document).ready(function()
{
  /* problem submit, skip button hiding to avoid double clicks
     on the problem submit page, in views.php */
  $("#submit_answer").click(function() {
      document.getElementById('submit_answer').style.visibility='hidden' ;
      document.ans_form.submit();
    });

  $("#skip").click(function() {
      document.getElementById('skip').style.visibility='hidden' ;
    });

});
