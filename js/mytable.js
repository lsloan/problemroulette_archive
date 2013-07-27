$(document).ready(function() 
{ 
	$("#historyTable").tablesorter({
		sortList: [[1,1]]
	}); 
	
	/*$('select.dropdown-num-rows').change(function(){
		var num_rows = $("select.dropdown-num-rows").val();
		
		if (num_rows == 'All')
		{
			//show all rows the moment 'all' is chosen
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			
			//show all rows if sorted while 'all' is chosen
			$("table").bind("sortStart",function() { 
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			}).bind("sortEnd",function() { 
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			});

		}
		
		else
		{
			//hide rows > (selected_num_rows) on page load
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			$('tbody tr:gt('+(num_rows-1)+')').css('display', 'none');
			
			//hide all rows for sorting, then show all rows <= (selected_num_rows) after sorting is done
			$("table").bind("sortStart",function() { 
				$('tbody tr:gt(0)').css('display', 'none');
				$('tbody tr:nth-child(1)').css('display', 'none');
			}).bind("sortEnd",function() { 
				$('tbody tr:lt('+num_rows+')').css('display', 'table-row');
			});
		}
	});*/
	
	$('select.dropdown-correct').change(function(){
		var correct = $("select.dropdown-correct").val();
	
		if (correct == 'correct')
		{
			//show all rows
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			
			//show only correct answers
			$('#historyTable > tbody  > tr').each(function()
			{
				if ($('.cell-student-answer', this).html() != $('.cell-correct-answer', this).html())
				{
					$(this).css('display','none');
				}
			});
		}
		
		else if (correct == 'incorrect')
		{
			//show all rows
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			
			//show only incorrect answers
			$('#historyTable > tbody  > tr').each(function()
			{
				if ($('.cell-student-answer', this).html() == $('.cell-correct-answer', this).html())
				{
					$(this).css('display','none');
				}
			});
		}
		
		else//if student wants to view both correct and incorrect responses
		{
			//show all rows
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
		}
	});
	
	//store all topic options in elem_init and determine length
	var elem_init = $("select.dropdown-topic option");
	var num_all_topics = elem_init.length;
	
	//allow topic filtering after course has been selected
	$('select.dropdown-course').change(function(){		
		var dropdown_history_course = $("select.dropdown-course").val();
		
		//$.post('stats.php',{dropdown_course: dropdown_history_course},function(response){
			//window.location = '';
		//});
		document.dropdown_course_form.submit();
		
		if ($('select.dropdown-course').val() == 'all')
		{
			$('select.dropdown-topic option[value="all"]').prop('selected','selected');
			$('select.dropdown-topic').prop('disabled','disabled');
		}
		
		else//if the course selection is anything other than 'all courses'
		{			
			//remove all topic selections then add back 'All Topics'
			$("select.dropdown-topic option").remove();
			$("select.dropdown-topic").append(elem_init[0]);
		
			//get topics in course
			var course_id = $('select.dropdown-course').val();
			var topics_in_course_string = $("#"+course_id+"").val();
			var topics_in_course = topics_in_course_string.split(",");
			var num_topics = topics_in_course.length;
			
			//hide all topics
			//$("select.dropdown-topic option").css('display','none');------OLD CODE
			//$("select.dropdown-topic option").hide();------OLD CODE
			//$("select.dropdown-topic option").addClass('invis');-------OLD CODE
			
			//show all topics in course
			for (var i=0; i<num_topics; i++)
			{
				//$("select.dropdown-topic option[value='"+topics_in_course[i]+"']").css('display','block');-------OLD CODE
				//$("select.dropdown-topic option[value='"+topics_in_course[i]+"']").show();-------OLD CODE
				for (var j=0; j<num_all_topics; j++)
				{
					if (elem_init[j].value == topics_in_course[i])
					{
						$('select.dropdown-topic').append(elem_init[j]);
					}
				}
			}
			
			//select 'all topics' in case user is switching from another course
			$("select.dropdown-topic option[value='all']").prop('selected','selected');
			
			//remove the 'disabled' attribute from the topic selector
			$('select.dropdown-topic').removeAttr('disabled');
		}
	});
	
	
	
	
	
	
		var dropdown_history_course = $("select.dropdown-course").val();
		
		if ($('select.dropdown-course').val() == 'all')
		{
			$('select.dropdown-topic option[value="all"]').prop('selected','selected');
			$('select.dropdown-topic').prop('disabled','disabled');
		}
		
		else//if the course selection is anything other than 'all courses'
		{			
			//remove all topic selections then add back 'All Topics'
			$("select.dropdown-topic option").remove();
			$("select.dropdown-topic").append(elem_init[0]);
		
			//get topics in course
			var course_id = $('select.dropdown-course').val();
			var topics_in_course_string = $("#"+course_id+"").val();
			var topics_in_course = topics_in_course_string.split(",");
			var num_topics = topics_in_course.length;
						
			//show all topics in course
			for (var i=0; i<num_topics; i++)
			{
				for (var j=0; j<num_all_topics; j++)
				{
					if (elem_init[j].value == topics_in_course[i])
					{
						$('select.dropdown-topic').append(elem_init[j]);
					}
				}
			}
			
			//select 'all topics' in case user is switching from another course
			$("select.dropdown-topic option[value='all']").prop('selected','selected');
			
			//remove the 'disabled' attribute from the topic selector
			$('select.dropdown-topic').removeAttr('disabled');
		}

	
});