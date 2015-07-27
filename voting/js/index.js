var course = 4;
var currentProblem = null;
var problems;
var allTopics;


function loadProblems(course_id, oldtopics) {
    PRApi.getProblems(course_id, oldtopics).then(function(data) {
        problems = data.problems;
        // alert('Problem count: ' + problems.length);
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

        $('.leftFrame').empty();
        if (nextproblem) {
            currentProblem = nextproblem;
            $('<iframe width="100%" height="540px" src="' + problem.url + '"></iframe>').appendTo('.leftFrame');
        } else {
            $('.voting-ui').addClass('hidden');
            $('#no-problems').removeClass('hidden');
        }

    });
}

function loadTopics(course_id) {
    PRApi.getTopics(course_id).then(function(data) {
        var topics = data.topics;
        allTopics = topics;
        $('.rightFrame').empty();

        var mid = Math.floor(topics.length / 2);
        if (topics.length % 2 == 1) {
            mid += 1;
        }
        var leftcol = topics.slice(0, mid)
        var rightcol = topics.slice(mid, topics.length);

        var markup = '<div class="row">' +
        '<div class="col-lg-6">' +
        makeTopicChoices(leftcol) +
        '</div>' +
        '<div class="col-lg-6">' +
        makeTopicChoices(rightcol) +
        '</div>' +
        '</div>';

        $(markup).appendTo('.rightFrame');
    });
}

function makeTopicChoices(topics) {
    var markup = ''
    $.each(topics, function(idx, t) {
        markup += '<div class="checkbox"><label><input type="checkbox" value="' + t.value + '">' + t.label + '</label></div>';
    });
    return markup;
}

// Logs selected topics
function logVotes(data) {
    $.each(data.votes, function(idx, vote) {
  	  console.log(idx + " -- " + vote.id + ": " + vote.topics + " (" + vote.created_at + ", " + vote.updated_at + ")");
	});
}

function selectCourse(course_id, oldtopics) {
    $('#select-course').addClass('hidden');
    $('#no-problems').addClass('hidden');
    $('.voting-ui').removeClass('hidden');
    course = course_id;
    loadTopics(course_id);
    loadProblems(course_id, oldtopics);
}


$(document).ready(function() {

	// Changes topics for different courses
	$('.changeTopics').click(function(evt) {
        evt.preventDefault();
		var course_id = $(this).data('course');
        var oldtopics = $(this).data('oldtopics');
        $li = $(this).parent('li');
        $('li', $li.parent('ul')).removeClass('active');
        $li.addClass('active');
        selectCourse(course_id, oldtopics);
	});
	
	// On click function for Next button
	$("#btnNext").on('click', function(e) {
        e.preventDefault();
	
        var selectedTopics = {};

        // Lists all topics selected
        var topics = $('input:checkbox:checked').map(function() {
        	return $(this).val();
        }).toArray();
        
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

