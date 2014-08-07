$(document).ready(function()
{
  /* problem submit, skip button hiding to avoid double clicks
     on the problem submit page, in views.php */
  $('#submit_answer, #skip').click(function() {
    $(this).css('visibility', 'hidden');
  });

});
