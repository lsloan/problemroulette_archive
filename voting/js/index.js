var course = 4;
var currentProblem = null;
var problems;
var allTopics;


function loadProblems(course_id) {
    PRApi.getProblems(course_id).then(function(data) {
        problems = data.problems;
        showNext();
    });
}

function showNext() {
    PRApi.getVotes(course).then(function(data) {
        var answered = $.map(data.votes, function(vote) {
            return vote.problem_id;
        });

        var nextproblem = null;
        for (j = 0; j < problems.length; j++) {
            var problem = problems[j];
            if ($.inArray(problems[j].id, answered) == -1) {
                nextproblem = problem;
                break;
            }
        }

        $('input:checkbox').prop('checked',false); // Resets checkboxes after submission		      	

        if (nextproblem) {
            currentProblem = nextproblem;
            $("iframe").prop("src", problem.url);
        } else {
            $("iframe").prop("src", "");
            alert('No unanswered problems for this course.');
        }

    });
}

function loadTopics(course_id) {
    PRApi.getTopics(course_id).then(function(data) {
        var topics = data.topics;
        allTopics = topics;
        $('.rightFrame').empty();
        $.each(topics, function(idx, t) {
            $('<div class="checkbox"><label><input type="checkbox" value="' + t.value +
            '">' + t.label + '</label></div>').appendTo('.rightFrame');
        });
    });
}

// Logs selected topics
function logVotes(data) {
    $.each(data.votes, function(idx, vote) {
  	  console.log(idx + " -- " + vote.id + ": " + vote.topics + " (" + vote.created_at + ", " + vote.updated_at + ")");
	});
}

function selectCourse(course_id) {
    $('#select-course').remove();
    $('.hidden').removeClass('hidden');
    course = course_id;
    loadTopics(course_id);
    loadProblems(course_id);
}


$(document).ready(function() {

	// Changes topics for different courses
	$('.changeTopics').click(function(evt) {
        evt.preventDefault();
		var course_id = $(this).data('course');
        $li = $(this).parent('li');
        $('li', $li.parent('ul')).removeClass('active');
        $li.addClass('active');
        selectCourse(course_id);
	});
	
	// On click function for Next button
	$("#btnNext").on('click', function(e) {
	
        var selectedTopics = {};

        // Lists all topics selected
        var topics = $('input:checkbox:checked').map(function() {
        	return $(this).val();
        }).toArray();
        
        console.log(topics);
        
        // Saves selected topics
        PRApi.saveVote(currentProblem.id, topics).then(showNext);
	});
	
	//On click function for Previous button
    /*
	$("#btnPrev").on('click', function(e) {
		
        //TODO: consider current problem index
		i--;
		
		//Loads previous problem
		PRApi.getProblems(course).then(function(data) {
			var problem = data.problems[i];
			$("iframe").prop("src", problem.url);
		});
	});
    */
});

