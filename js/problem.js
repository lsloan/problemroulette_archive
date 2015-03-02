$(document).ready(function()
{
  /* problem submit, skip button hiding to avoid double clicks
     on the problem submit page, in views.php */
  $('#submit_answer, #skip').on('click', function(eventObj) {
    eventObj.preventDefault();
    $(this).attr('disabled', true);
    var submit_or_skip = $(this).attr('name');
    $('#submit_or_skip').attr('name', submit_or_skip);
    $('#submit_or_skip').attr('value', 1);
    $(this).closest('form').submit();
  });
  $('.ans-choice').on('click', function(eventObj) {
    var checked = $('input.ans-choice:checked');
    if(checked.size() > 0) {
      $('#submit_answer').removeAttr('disabled');
    } else {
      $('#submit_answer').attr('disabled', true);
    }
  });
  $('body').on('click', 'li.disabled_problem_tab', function(eventObj){
    return false;
  });
  $('body').on('click', 'li.disabled_problem_tab a', function(eventObj){
    eventObj.preventDefault();
    return false;
  });
  $('body').on('click', 'input#ratings-form-submit', function(eventObj){
    eventObj.preventDefault();
    var params = $(this).closest('form').serializeArray();
    var url = $(this).closest('form').attr('action');
    $.ajax(url, {
      type: 'POST',
      dataType: 'json',
      data: params,
      success: function(data){
        alert('data');
      },
      error : function(jqXHR, textStatus, errorThrown){
        alert("An unknown error occurred while trying to remove the file.  Please refresh your browser and try again.");
      }
    });

  });
});
