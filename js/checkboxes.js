$(document).ready(function() {
  $('.topic-selector input:checkbox.group').click(function() {
	if ($(this).prop('checked')) {
	  $(this).parents('tr').addClass('checked');  
	} else {
	  $(this).parents('tr').removeClass('checked');
	}
	var count = 0;
	$('.topic-selector input:checkbox').each(function() {
	  if ($(this).prop('checked')) {
		count++;
	  }
	})
	if (count > 0) {
		$('#use-selected-top').removeClass('disabled');
		$('#use-selected').removeClass('disabled');
		$('#reset-topics-top').removeClass('disabled');
		$('#reset-topics').removeClass('disabled');
		$('#use-selected-top').attr('href','javascript:document.topic_selector.submit();');
		$('#use-selected').attr('href','javascript:document.topic_selector.submit();');
		$('#reset-topics-top').attr('href',"javascript:reset_topic_checkboxes();");
		$('#reset-topics').attr('href',"javascript:reset_topic_checkboxes();");
	} else {
		$('#use-selected-top').addClass('disabled');
		$('#use-selected').addClass('disabled');
		$('#reset-topics-top').addClass('disabled');
		$('#reset-topics').addClass('disabled');
		$('#use-selected-top').attr('href','javascript:void(0);');
		$('#use-selected').attr('href','javascript:void(0);');
		$('#reset-topics-top').attr('href','javascript:void(0);');
		$('#reset-topics').attr('href','javascript:void(0);');
	}
  });
  
  $('.topic-selector input:checkbox.group').each(function() {
	if ($(this).prop('checked')) {
	  $(this).parents('tr').addClass('checked');  
	} else {
	  $(this).parents('tr').removeClass('checked');
	}
	var count = 0;
	$('.topic-selector input:checkbox').each(function() {
	  if ($(this).prop('checked')) {
		count++;
	  }
	})
	if (count > 0) {
		$('#use-selected-top').removeClass('disabled');
		$('#use-selected').removeClass('disabled');
		$('#reset-topics-top').removeClass('disabled');
		$('#reset-topics').removeClass('disabled');
		$('#use-selected-top').attr('href','javascript:document.topic_selector.submit();');
		$('#use-selected').attr('href','javascript:document.topic_selector.submit();');
		$('#reset-topics-top').attr('href',"javascript:reset_topic_checkboxes();");
		$('#reset-topics').attr('href',"javascript:reset_topic_checkboxes();");
	} else {
		$('#use-selected-top').addClass('disabled');
		$('#use-selected').addClass('disabled');
		$('#reset-topics-top').addClass('disabled');
		$('#reset-topics').addClass('disabled');
		$('#use-selected-top').attr('href','javascript:void(0);');
		$('#use-selected').attr('href','javascript:void(0);');
		$('#reset-topics-top').attr('href','javascript:void(0);');
		$('#reset-topics').attr('href','javascript:void(0);');
	}
  });

  $('a#statistics-tab').click(function() {
	 $.get('statistics.php', function(data) {
		$('#statistics').html(data);
	 });
  })});
  
$(document).ready(function(){
    $('#select_all_checkboxes').click(function(){
        if($(this).prop('checked')){
            $('.group').parents('tr').addClass('checked');
        }
        else{
            $('.group').parents('tr').removeClass('checked');
        }
		
		var count = 0;
		$('.topic-selector input:checkbox').each(function() {
		  if ($(this).prop('checked')) {
			count++;
		  }
		})
		if (count > 0) {
			$('#use-selected-top').removeClass('disabled');
			$('#use-selected').removeClass('disabled');
			$('#reset-topics-top').removeClass('disabled');
			$('#reset-topics').removeClass('disabled');
			$('#use-selected-top').attr('href','javascript:document.topic_selector.submit();');
			$('#use-selected').attr('href','javascript:document.topic_selector.submit();');
			$('#reset-topics-top').attr('href',"javascript:reset_topic_checkboxes();");
			$('#reset-topics').attr('href',"javascript:reset_topic_checkboxes();");
		} else {
			$('#use-selected-top').addClass('disabled');
			$('#use-selected').addClass('disabled');
			$('#reset-topics-top').addClass('disabled');
			$('#reset-topics').addClass('disabled');
			$('#use-selected-top').attr('href','javascript:void(0);');
			$('#use-selected').attr('href','javascript:void(0);');
			$('#reset-topics-top').attr('href','javascript:void(0);');
			$('#reset-topics').attr('href','javascript:void(0);');
		}
    })
});
		  
function toggle(source) {
  checkboxes = document.getElementsByName('topic_checkbox_submission[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
	//checkboxes[i].parents('tr').addClass('checked');
  }
}

function reset_topic_checkboxes(){
	var are_you_sure_checkboxes=confirm('Resetting a topic will allow all its problems to be randomly selected again. This may result in repeated problems. Would you like to continue?');
	if (are_you_sure_checkboxes != true)
	{
		return false;
	}

	document.topic_selector.action = "";
	document.topic_selector.submit();
}

function reset_topic(topic_id){
	var are_you_sure=confirm('Resetting a topic will allow all its problems to be randomly selected again. This may result in repeated problems. Would you like to continue?');
	if (are_you_sure!=true)
	{
		return false;
	}

	$.post('selections.php',{topic_link_submission: topic_id},function(response){
		window.location = '';
	});
}