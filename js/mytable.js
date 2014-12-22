$(document).ready(function() 
{ 
	$("#historyTable").tablesorter({
		sortList: [[1,1]]
	}); 
		
	//when you change the CORRECT/INCORRECT dropdown
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
	
	//when user chooses course, re-submit form with new course selection
	$('select.dropdown-course').change(function(){
        if (document.dropdown_course_form)
        {
            document.dropdown_course_form.submit();
        }
	});
	
	//when user chooses topic, re-submit form with new topic selection
	$('select.dropdown-topic').change(function(){
        if (document.dropdown_topic_form)
        {
            document.dropdown_topic_form.submit();
        }
	});
	
	
	//when page loads
	//logic to display the appropriate topics for course selection on stats.php
	var dropdown_history_course = $("select.dropdown-course").val();
    if (dropdown_history_course && (dropdown_history_course != '-1'))
    {
        if ($('select.dropdown-course').val() == 'all' || $('select.dropdown-course').val() == 0)//if 'all courses is selected'
        {
            //remove all topic selections then add back 'All Topics'
            $("select.dropdown-topic option").remove();
            $("select.dropdown-topic").append(elem_init[0]);
            
            $('select.dropdown-topic option[value="all"]').prop('selected','selected');
            $('select.dropdown-topic').prop('disabled','disabled');
        }
        
        else//if the course selection is anything other than 'all courses'
        {
            //store the selected topic (to fix error in FireFox)
            var selected_topic = null;
            $('select.dropdown-topic option').each(function() {
                if(this.selected)
                {
                    selected_topic = $(this).val();
                }
            });
            
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
            
            //select the topic which the user selected (in Firefox, it resets when you delete all topics and append the appropriate ones back)
            $('select.dropdown-topic option[value='+selected_topic+']').prop('selected','selected');
                
            //remove the 'disabled' attribute from the topic selector if user selects course choice other than 'all courses'
            $('select.dropdown-topic').removeAttr('disabled');
        }
    }

	
	
	$('#clear_search_username').click(function(){
		$('#input_search_username').val('');
		document.search_username.submit();
	});
	
	
	
});