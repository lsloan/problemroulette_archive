$(document).ready(function(){
  tinymce.init({
    selector: "textarea#question_body",
    plugins: "tiny_mce_wiris",
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | tiny_mce_wiris_formulaEditor"
  });
  tinymce.init({
    selector: "textarea#question_answer_body_0",
    plugins: "tiny_mce_wiris",
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | tiny_mce_wiris_formulaEditor"
  });
  $('body').on('click', 'button#add-answer', function(eventObj){
    eventObj.preventDefault();
    var new_answer = $('tbody#question_answer_template').html();
    var answer_count = $('tbody#question_answers tr').size();
    var new_answer_id = 'question_answer_body_' + answer_count;
    $('tbody#question_answers').append(new_answer);
    $('tbody#question_answers .next_question_answer_body').attr('id',new_answer_id);
    tinymce.init({
      selector: "textarea#" + new_answer_id,
      plugins: "tiny_mce_wiris",
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | tiny_mce_wiris_formulaEditor"
    });
    $('tbody#question_answers .next_question_answer_body').removeClass('next_question_answer_body');
  });
});