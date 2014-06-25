$(document).ready(function() 
{
	$('#edit_problem_name_button').click(function(){
        $('#edit_problem_name_form').show();
    });
	$('#remove_edit_problem_name_button').click(function(){
		$("#edit_problem_name_form").hide();
	});
    
	$('#edit_problem_url_button').click(function(){
        $('#edit_problem_url_form').show();
    });
	$('#remove_edit_problem_url_button').click(function(){
		$("#edit_problem_url_form").hide();
	});
    
	$('#edit_problem_sol_url_button').click(function(){
        $('#edit_problem_sol_url_form').show();
    });
	$('#remove_edit_problem_sol_url_button').click(function(){
		$("#edit_problem_sol_url_form").hide();
	});

	$('#edit_problem_num_ans_button').click(function(){
        $('#edit_problem_num_ans_form').show();
    });
	$('#remove_edit_problem_num_ans_button').click(function(){
		$("#edit_problem_num_ans_form").hide();
	});

	$('#edit_problem_cor_ans_button').click(function(){
        $('#edit_problem_cor_ans_form').show();
    });
	$('#remove_edit_problem_cor_ans_button').click(function(){
		$("#edit_problem_cor_ans_form").hide();
	});
    
	//CHANGE NAME//Set Submit button to disabled by default
	$('#edit_problem_name_submit').attr("disabled","disabled");
	$('#edit_problem_name').addClass('input-error');
	//Enable Submit button if input is valid (alphanumeric + spaces)
	$('#edit_problem_name').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#edit_problem_name').val()).length == 0) 
		{
			validation = false;
			$('#edit_problem_name').addClass('input-error');
		}
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test($('#edit_problem_name').val().replace(/\s/g, ''))) 
		{
			validation = false;
			$('#edit_problem_name').addClass('input-error');
		}
		if(validation) 
		{
			$("#edit_problem_name_submit").removeAttr("disabled");
			$('#edit_problem_name').removeClass('input-error');
		}
		else 
		{
			$('#edit_problem_name_submit').attr("disabled","disabled");
			$('#edit_problem_name').addClass('input-error');
		}
	});
    
	//CHANGE URL//Set Submit button to disabled by default
	$('#edit_problem_url_submit').attr("disabled","disabled");
	$('#edit_problem_url').addClass('input-error');
	//Enable Submit button if input is valid
	$('#edit_problem_url').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#edit_problem_url').val()).length == 0) 
		{
			validation = false;
			$('#edit_problem_url').addClass('input-error');
		}
		if(validation) 
		{
			$("#edit_problem_url_submit").removeAttr("disabled");
			$('#edit_problem_url').removeClass('input-error');
		}
		else 
		{
			$('#edit_problem_url_submit').attr("disabled","disabled");
			$('#edit_problem_url').addClass('input-error');
		}
	});
    
	//CHANGE SOLUTION URL//Set Submit button to disabled by default
	$('#edit_problem_sol_url_submit').attr("disabled","disabled");
	$('#edit_problem_sol_url').addClass('input-error');
	//Enable Submit button if input is valid
	$('#edit_problem_sol_url').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#edit_problem_sol_url').val()).length == 0) 
		{
			validation = false;
			$('#edit_problem_sol_url').addClass('input-error');
		}
		if(validation) 
		{
			$("#edit_problem_sol_url_submit").removeAttr("disabled");
			$('#edit_problem_sol_url').removeClass('input-error');
		}
		else 
		{
			$('#edit_problem_sol_url_submit').attr("disabled","disabled");
			$('#edit_problem_sol_url').addClass('input-error');
		}
	});
    
	//CHANGE NUMBER OF ANSWERS//Set Submit button to disabled by default
	$('#edit_problem_num_ans_submit').attr("disabled","disabled");
	$('#edit_problem_num_ans').addClass('input-error');
	//Enable Submit button if input is valid
	$('#edit_problem_num_ans').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#edit_problem_num_ans').val()).length == 0) 
		{
			validation = false;
			$('#edit_problem_num_ans').addClass('input-error');
		}
        //Check that number of answers is a number and an integer
        if(!$.isNumeric(jQuery.trim($('#edit_problem_num_ans').val())) || jQuery.trim($('#edit_problem_num_ans').val()).indexOf('.') >= 0 || jQuery.trim($('#edit_problem_num_ans').val()) < 1)
        {
            validation = false;
            $('#edit_problem_num_ans').addClass('input-error');
        }
        //Check that correct answer index is not greater than number of answers
        if(parseInt(jQuery.trim($('#current_problem_info_cor_ans').html()),10) > parseInt(jQuery.trim($('#edit_problem_num_ans').val()),10))
        {
            validation = false;
            $('#edit_problem_num_ans').addClass('input-error');
        }
        else
        {
            if(jQuery.trim($('#edit_problem_num_ans').val()).length == 0) 
            {
                $('#edit_problem_num_ans').addClass('input-error');
            }
            else if(!$.isNumeric(jQuery.trim($('#edit_problem_num_ans').val())))
            {
                $('#edit_problem_num_ans').addClass('input-error');
            }
            else
            {
                $('#edit_problem_num_ans').removeClass('input-error');
            }
        }
		if(validation) 
		{
			$("#edit_problem_num_ans_submit").removeAttr("disabled");
			$('#edit_problem_num_ans').removeClass('input-error');
		}
		else 
		{
			$('#edit_problem_num_ans_submit').attr("disabled","disabled");
			$('#edit_problem_num_ans').addClass('input-error');
		}
	});
    
	//CHANGE CORRECT ANSWER//Set Submit button to disabled by default
	$('#edit_problem_cor_ans_submit').attr("disabled","disabled");
	$('#edit_problem_cor_ans').addClass('input-error');
	//Enable Submit button if input is valid
	$('#edit_problem_cor_ans').keyup(function(){
		var validation = true;
		if(jQuery.trim($('#edit_problem_cor_ans').val()).length == 0) 
		{
			validation = false;
			$('#edit_problem_cor_ans').addClass('input-error');
		}
        //Check that number of answers is a number and an integer
        if(!$.isNumeric(jQuery.trim($('#edit_problem_cor_ans').val())) || jQuery.trim($('#edit_problem_cor_ans').val()).indexOf('.') >= 0 || jQuery.trim($('#edit_problem_cor_ans').val()) < 1)
        {
            validation = false;
            $('#edit_problem_cor_ans').addClass('input-error');
        }
        //Check that correct answer index is not greater than number of answers
        if(parseInt(jQuery.trim($('#edit_problem_cor_ans').val()),10) > parseInt(jQuery.trim($('#current_problem_info_num_ans').html()),10))
        {
            validation = false;
            $('#edit_problem_cor_ans').addClass('input-error');
        }
        else
        {
            if(jQuery.trim($('#edit_problem_cor_ans').val()).length == 0) 
            {
                $('#edit_problem_cor_ans').addClass('input-error');
            }
            else if(!$.isNumeric(jQuery.trim($('#edit_problem_cor_ans').val())))
            {
                $('#edit_problem_cor_ans').addClass('input-error');
            }
            else
            {
                $('#edit_problem_cor_ans').removeClass('input-error');
            }
        }
		if(validation) 
		{
			$("#edit_problem_cor_ans_submit").removeAttr("disabled");
			$('#edit_problem_cor_ans').removeClass('input-error');
		}
		else 
		{
			$('#edit_problem_cor_ans_submit').attr("disabled","disabled");
			$('#edit_problem_cor_ans').addClass('input-error');
		}
	});
    
});