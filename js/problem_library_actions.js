$(document).ready(function() 
{
	// control of the add |problem,topic,class| related buttons, forms
	$('button#add_course, button#add_topic, button#add_problem').click(function(){
		// show the form
		$($(this).data('form')).show();
		// show the x and cancel buttons to be able to hide the form
		$($(this).data('remove')).show();
		$($(this).data('cancel')).show();
	});

    $('button#edit_topic').click(function() {
        var $option = $('#PL_dropdown_topic option:selected');
        var id = $option.val();
        var name = $option.text().trim();
        $('#edit_topic_id').val(id);
        $('#edit_topic_name').val(name);
        $('#edit_topic_form').show();
    });

    if ($('#PL_dropdown_topic').val() != 'all') {
        $('#edit_topic').show();
    }

	$('a.hide_add_form, button.remove-add-CTP-form').click(function(){
		// hide the form (which also hides the cancel button)
		$($(this).data('form')).hide();
		// hide the x button
		$($(this).data('remove')).hide();
	});

	
    //ADD PROBLEM//store all topic options in elem_init and determine length
    var elem_init = $("#topic_for_new_problem option");
    var PL_elem_init = $("#PL_dropdown_topic option");
    var num_all_topics = elem_init.length;
    var PL_num_all_topics = PL_elem_init.length;
    
	//Confirm Submission for new Course
	$('#add_course_form').submit(function(){
		var num_courses = document.getElementById('num_courses').firstChild.data;
		for (var i=0;i<num_courses;i++)
		{
			var temp = document.getElementById('course' + i).firstChild.data;
			var temp2 = jQuery.trim($('#add_course_name').val());
			if (temp == temp2)
			{
				alert('There is already a course named ' + temp + '. Please choose a different name.');
				return false;
			}
		}
		
		var validate = true;
		if(jQuery.trim($('#add_course_name').val()).length == 0) validate = false;
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test($('#add_course_name').val().replace(/\s/g, ''))) {validate = false;}
		if (!validate) 
		{
			alert('Not a valid input');
			return false;
		}
		
		var confirm_add_course=confirm('You are about to create a new course visible to all users. Are you sure you wish to proceed?');
		if (confirm_add_course != true)
		{
			return false;
		}
	});
	
	//Confirm Submission for new Topic
	$('#add_topic_form').submit(function(){
		var confirm_add_topic=confirm('You are about to create a new topic visible to all users. Are you sure you wish to proceed?');
		if (confirm_add_topic != true)
		{
			return false;
		}
	});

	
	//Set Submit button to disabled by default (COURSE)
	$('#submit_add_course').attr("disabled","disabled");
	$('#add_course_name').addClass('input-error');
	//Enable Submit button if input is valid (alphanumeric + spaces)
	$('#add_course_name').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#add_course_name').val()).length == 0) 
		{
			validation = false;
			$('#add_course_name').addClass('input-error');
		}
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test($('#add_course_name').val().replace(/\s/g, ''))) 
		{
			validation = false;
			$('#add_course_name').addClass('input-error');
		}
		if(validation) 
		{
			$("#submit_add_course").removeAttr("disabled");
			$('#add_course_name').removeClass('input-error');
		}
		else 
		{
			$('#submit_add_course').attr("disabled","disabled");
			$('#add_course_name').addClass('input-error');
		}
	});

	//Set Submit button to disabled by default (TOPIC)
	$('#submit_add_topic').attr("disabled","disabled");
	$('#add_topic_name').addClass('input-error');
	//Enable Submit button if input is valid (alphanumeric + spaces)
	$('#add_topic_name, #edit_topic_name').keyup(function(){
		var validation = true;
		var submit = '#submit_add_topic';
		if (this.name == '#edit_topic_name') {
			submit = '#submit_edit_topic';
		}
		if(jQuery.trim($(this).val()).length == 0)
		{
			validation = false;
			$(this).addClass('input-error');
		}
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test($(this).val().replace(/\s/g, '')))
		{
			validation = false;
			$(this).addClass('input-error');
		}
		if(validation)
		{
			$(this).removeClass('input-error');
		}
		if ((this.name =='#add_topic_name') && $('#course_for_new_topic').val() == 0)
		{
			validation = false;
		}
		if(validation)
		{
			$(submit).removeAttr("disabled");
		}
		else 
		{
			$(submit).attr("disabled","disabled");
		}
	});
	$('#course_for_new_topic').change(function(){
		var validation = true;
		if ($('#course_for_new_topic').val() == 0) 
		{
			validation = false;
			$('#course_for_new_topic').addClass('input-error');
		}
		else
		{
			$('#course_for_new_topic').removeClass('input-error');
		}
		if(jQuery.trim($('#add_topic_name').val()).length == 0) validation = false;
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test($('#add_topic_name').val().replace(/\s/g, ''))) {validation = false;}
		if(validation) $("#submit_add_topic").removeAttr("disabled");
		else $('#submit_add_topic').attr("disabled","disabled");
	});
    
    //ADD PROBLEM//When user selects course, determine the associated topics and populate topic dropdown
    $('#course_for_new_problem').change(function(){
        //Determine whether selection is valid
		if ($('#course_for_new_problem').val() == 0) 
		{
			$('#course_for_new_problem').addClass('input-error');
		}
		else
		{
			$('#course_for_new_problem').removeClass('input-error');
		}
                
            $("#topic_for_new_problem option").remove();
            for (var i=0; i<num_all_topics; i++)
            {
                $("#topic_for_new_problem").append(elem_init[i]);
            }

        //if 'all courses' is selected
        if ($('#course_for_new_problem').val() == '0')
        {
            //remove all topic selections then add back 'All Topics'
            $("#topic_for_new_problem option").remove();
            $("#topic_for_new_problem").append(elem_init[0]);
            
            $('#topic_for_new_problem option[value="0"]').prop('selected','selected');
            $('#topic_for_new_problem').prop('disabled','disabled');
        }
        else
        {
            //store the selected topic (to fix error in FireFox)
            var selected_topic = null;
            $('#topic_for_new_problem option').each(function() {
                if(this.selected)
                {
                    selected_topic = $(this).val();
                }
            });
            
            //remove all topic selections then add back 'All Topics'
            $("#topic_for_new_problem option").remove();
            $("#topic_for_new_problem").append(elem_init[0]);
        
            //get topics in course
            var course_id = $('#course_for_new_problem').val();
            var topics_in_course_string = $("#"+course_id+"").val();
            var topics_in_course = topics_in_course_string.split(",");
            var num_topics = topics_in_course.length;
            $("#topic_for_new_problem").attr('size', num_topics+1);
            //show all topics in course
            for (var i=0; i<num_topics; i++)
            {
                for (var j=0; j<num_all_topics; j++)
                {
                    if (elem_init[j].value == topics_in_course[i])
                    {
                        $('#topic_for_new_problem').append(elem_init[j]);
                    }
                }
            }
            
            //select the topic which the user selected (in Firefox, it resets when you delete all topics and append the appropriate ones back)
            $('#topic_for_new_problem option[value='+selected_topic+']').prop('selected','selected');
                
            //remove the 'disabled' attribute from the topic selector if user selects course choice other than 'all courses'
            $('#topic_for_new_problem').removeAttr('disabled');
        }
        
        //Determine if topic choice is valid
		if ($('#topic_for_new_problem').val() == 0) 
		{
			$('#topic_for_new_problem').addClass('input-error');
		}
		else
		{
			$('#topic_for_new_problem').removeClass('input-error');
		}
        
        //Enable submit button if every field is valid or disable it if every field is not valid
        if (add_problem_validation())
        {
            $('#submit_add_problem').removeAttr('disabled');
        }
        else
        {
            $('#submit_add_problem').attr('disabled','disabled');
        }
        
    });
	
    //ADD PROBLEM//When user selects topic, determine if it is a valid choice (not the 'select one' option)
    $('#topic_for_new_problem').change(function(){
		if ($('#topic_for_new_problem').val() == 0) 
		{
			$('#topic_for_new_problem').addClass('input-error');
		}
		else
		{
			$('#topic_for_new_problem').removeClass('input-error');
		}
        
        //Enable submit button if every field is valid or disable it if every field is not valid
        if (add_problem_validation())
        {
            $('#submit_add_problem').removeAttr('disabled');
        }
        else
        {
            $('#submit_add_problem').attr('disabled','disabled');
        }
    });
    
    //ADD PROBLEM//Verify that problem name is valid
	$('#add_problem_name').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#add_problem_name').val()).length == 0) 
		{
			validation = false;
			$('#add_problem_name').addClass('input-error');
		}
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test($('#add_problem_name').val().replace(/\s/g, ''))) 
		{
			validation = false;
			$('#add_problem_name').addClass('input-error');
		}
		if(validation) 
		{
			$('#add_problem_name').removeClass('input-error');
		}
		else 
		{
			$('#add_problem_name').addClass('input-error');
		}
        
        //Enable submit button if every field is valid or disable it if every field is not valid
        if (add_problem_validation())
        {
            $('#submit_add_problem').removeAttr('disabled');
        }
        else
        {
            $('#submit_add_problem').attr('disabled','disabled');
        }

	});

    //ADD PROBLEM//Verify that problem URL is valid
	$('#add_problem_url').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#add_problem_url').val()).length == 0) 
		{
			validation = false;
			$('#add_problem_url').addClass('input-error');
		}
		if(validation) 
		{
			$('#add_problem_url').removeClass('input-error');
		}
		else 
		{
			$('#add_problem_url').addClass('input-error');
		}
        
        //Enable submit button if every field is valid or disable it if every field is not valid
        if (add_problem_validation())
        {
            $('#submit_add_problem').removeAttr('disabled');
        }
        else
        {
            $('#submit_add_problem').attr('disabled','disabled');
        }
	});

    //ADD PROBLEM//Verify that number of answers is valid
	$('#add_problem_num_ans').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#add_problem_num_ans').val()).length == 0) 
		{
			validation = false;
			$('#add_problem_num_ans').addClass('input-error');
		}
        //Check that number of answers is a number and an integer
        if(!$.isNumeric(jQuery.trim($('#add_problem_num_ans').val())) || jQuery.trim($('#add_problem_num_ans').val()).indexOf('.') >= 0 || jQuery.trim($('#add_problem_num_ans').val()) < 1)
        {
            validation = false;
            $('#add_problem_num_ans').addClass('input-error');
        }
        //Check that correct answer index is not greater than number of answers
        if(parseInt(jQuery.trim($('#add_problem_cor_ans').val()),10) > parseInt(jQuery.trim($('#add_problem_num_ans').val()),10))
        {
            $('#add_problem_cor_ans').addClass('input-error');
        }
        else
        {
            if(jQuery.trim($('#add_problem_cor_ans').val()).length == 0) 
            {
                $('#add_problem_cor_ans').addClass('input-error');
            }
            else if(!$.isNumeric(jQuery.trim($('#add_problem_cor_ans').val())))
            {
                $('#add_problem_cor_ans').addClass('input-error');
            }
            else
            {
                $('#add_problem_cor_ans').removeClass('input-error');
            }
        }
		if(validation) 
		{
			$('#add_problem_num_ans').removeClass('input-error');
		}
		else 
		{
			$('#add_problem_num_ans').addClass('input-error');
		}
        
        //Enable submit button if every field is valid or disable it if every field is not valid
        if (add_problem_validation())
        {
            $('#submit_add_problem').removeAttr('disabled');
        }
        else
        {
            $('#submit_add_problem').attr('disabled','disabled');
        }

	});
    
    //ADD PROBLEM//Verify that correct answer choice is valid
	$('#add_problem_cor_ans').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#add_problem_cor_ans').val()).length == 0) 
		{
			validation = false;
			$('#add_problem_cor_ans').addClass('input-error');
		}
        if(!$.isNumeric(jQuery.trim($('#add_problem_cor_ans').val())) || jQuery.trim($('#add_problem_cor_ans').val()).indexOf('.') >= 0 || jQuery.trim($('#add_problem_cor_ans').val()) < 1)
        {
            validation = false;
            $('#add_problem_cor_ans').addClass('input-error');
        }
        //Check that correct answer index is not greater than number of answers
        if(parseInt(jQuery.trim($('#add_problem_cor_ans').val()),10) > parseInt(jQuery.trim($('#add_problem_num_ans').val()),10))
        {
            validation = false;
            $('#add_problem_cor_ans').addClass('input-error');
        }
		if(validation) 
		{
			$('#add_problem_cor_ans').removeClass('input-error');
		}
		else 
		{
			$('#add_problem_cor_ans').addClass('input-error');
		}
        
        //Enable submit button if every field is valid or disable it if every field is not valid
        if (add_problem_validation())
        {
            $('#submit_add_problem').removeAttr('disabled');
        }
        else
        {
            $('#submit_add_problem').attr('disabled','disabled');
        }
	});
    
	//Confirm Submission for new problem
	$('#add_problem_form').submit(function(){
		var confirm_add_problem=confirm('You are about to create a new problem visible to all users. Are you sure you wish to proceed?');
		if (confirm_add_problem != true)
		{
			return false;
		}
	});

    //VIEW PROBLEMS//When user selects course, determine the associated topics and populate topic dropdown
    $('#PL_dropdown_course').change(function(){
        $("#PL_dropdown_topic option").remove();
        for (var i=0; i<num_all_topics; i++)
        {
            $("#PL_dropdown_topic").append(PL_elem_init[i]);
        }

        //if 'all courses' is selected
        if ($('#PL_dropdown_course').val() == '0')
        {
            //remove all topic selections then add back 'All Topics'
            $("#PL_dropdown_topic option").remove();
            $("#PL_dropdown_topic").append(PL_elem_init[0]);
            
            $('#PL_dropdown_topic option[value="0"]').prop('selected','selected');
            $('#PL_dropdown_topic').prop('disabled','disabled');
        }
        else
        {
            //store the selected topic (to fix error in FireFox)
            var selected_topic = null;
            $('#PL_dropdown_topic option').each(function() {
                if(this.selected)
                {
                    selected_topic = $(this).val();
                }
            });
            
            //remove all topic selections then add back 'All Topics'
            $("#PL_dropdown_topic option").remove();
            $("#PL_dropdown_topic").append(PL_elem_init[0]);
        
            //get topics in course
            var course_id = $('#PL_dropdown_course').val();
            var topics_in_course_string = $("#"+course_id+"").val();
            var topics_in_course = topics_in_course_string.split(",");
            var num_topics = topics_in_course.length;
                        
            //show all topics in course
            for (var i=0; i<num_topics; i++)
            {
                for (var j=0; j<num_all_topics; j++)
                {
                    if (PL_elem_init[j].value == topics_in_course[i])
                    {
                        $('#PL_dropdown_topic').append(PL_elem_init[j]);
                    }
                }
            }
            
            //select the topic which the user selected (in Firefox, it resets when you delete all topics and append the appropriate ones back)
            $('#PL_dropdown_topic option[value='+selected_topic+']').prop('selected','selected');
                
            //remove the 'disabled' attribute from the topic selector if user selects course choice other than 'all courses'
            $('#PL_dropdown_topic').removeAttr('disabled');
        }
        
            $.post("", {"PL_dropdown_course_selected":$("#PL_dropdown_course").val()});
            $(document).ajaxStop(function() {
                location.reload();
            });
        });
    
    //VIEW PROBLEMS//When user selects topic, update table
    $('#PL_dropdown_topic').change(function(){
        $.post("", {"PL_dropdown_topic_selected":$("#PL_dropdown_topic").val()});
        $(document).ajaxStop(function() {
            location.reload();
        });
    });
    
});

function add_problem_validation()
{
    var valid = true;
    if ($('#course_for_new_problem').hasClass('input-error') || $('#topic_for_new_problem').hasClass('input-error') || $('#add_problem_name').hasClass('input-error') || $('#add_problem_url').hasClass('input-error') || $('#add_problem_num_ans').hasClass('input-error') || $('#add_problem_cor_ans').hasClass('input-error'))
    {
        valid = false;
    }
    return valid;
}
