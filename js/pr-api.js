var PR_API_URL = PR_API_URL || null;
var PRApi = (function($, endpoint) {
    var that = {};

    var sampleApi = (function() {
        var COURSES = {
            courses: [{
                id: "4",
                name: "Physics 140"
            }, {
                id: "5",
                name: "Physics 240"
            }]
        };

        var PROBLEMS = {
            course_id: "4",
            problems: [
                {
                    id: "288",
                    name: "UM Physics 140 Midterm 1 Fall 09 Problem 01",
                    url: "https://docs.google.com/document/pub?id=1XxvbK1lqlbeTluYC7wwtvkaTZTRZDphbnAMomQ-iGUM"
                }, {
                    id: "289",
                    name: "UM Physics 140 Midterm 1 Fall 09 Problem 02",
                    url: "https://docs.google.com/document/pub?id=1IQaFLnbi_JpDiId7LuEtlAWUQmFNJE5lvK4BwV0_aZA"
                }, {
                    id: "290",
                    name: "UM Physics 140 Midterm 1 Fall 09 Problem 03",
                    url: "https://docs.google.com/document/pub?id=13p0LLEVbufdhCgAXoEpWXRz31YZjO3h6T3zvkbk5rcc"
                }, {
                    id: "291",
                    name: "UM Physics 140 Midterm 1 Fall 09 Problem 04",
                    url: "https://docs.google.com/document/pub?id=1vqmyDXPcZIgdb2OuEev1iQVZAjAqAMHmAzH48hXfkXM"
                }
            ]
        };

        var VOTES = {
            course_id: "5",
            votes: [{
                id: "35",
                problem_id: "289",
                user_id: "11",
                topics: ["Kinetic and Potential Energy","Friction"],
                created_at: "2015-06-01T19:29:25Z",
                updated_at: "2015-06-02T01:10:09Z"
            }, {
                id: "36",
                problem_id: "290",
                user_id: "11",
                topics: ["Energy Conservation","Kinetic and Potential Energy"],
                created_at: "2015-06-01T19:29:25Z",
                updated_at: "2015-06-02T01:10:09Z"
            }]
        };

        var vote_id = 36;

        return {
            getCourses: function() {
                return new Promise(function(fulfill, reject) {
                    fulfill(COURSES);
                });
            },

            getProblems: function(course_id) {
                return new Promise(function(fulfill, reject) {
                    if (course_id) {
                        PROBLEMS.course_id = course_id.toString();
                        fulfill(PROBLEMS);
                    } else {
                        reject(new Error("The course_id parameter is required."));
                    }
                });
            },

            getVotes: function(course_id) {
                return new Promise(function(fulfill, reject) {
                    if (course_id) {
                        VOTES.course_id = course_id.toString();
                        fulfill(VOTES);
                    } else {
                        reject(new Error("The course_id parameter is required."));
                    }
                });
            },

            saveVote: function(problem_id, topics) {
                return new Promise(function(fulfill, reject) {
                    if (!Array.isArray(topics)) {
                        topics = [topics];
                    }

                    var now = (new Date()).toJSON();

                    var create = true;
                    for (var i = 0; i < VOTES.votes.length; i++) {
                        if (VOTES.votes[i].problem_id == problem_id) {
                            VOTES.votes[i].topics = topics;
                            VOTES.votes[i].updated_at = now;
                            create = false;
                            break;
                        }
                    }

                    if (create) {
                        VOTES.votes.push({
                            id: (vote_id += 1).toString(),
                            problem_id: problem_id.toString(),
                            user_id: "11",
                            topics: topics,
                            created_at: now,
                            updated_at: now
                        });
                    }

                    fulfill({success: true});
                });
            }
        };
    }());

    var HttpApi = function(endpoint) {
        return {
            getCourses: function() {
                return Promise.resolve($.get(endpoint + "/courses.php"));
            },

            getProblems: function(course_id) {
                return Promise.resolve($.get(endpoint + "/problems.php", { course_id: course_id }));
            },

            getVotes: function(course_id) {
                return Promise.resolve($.get(endpoint + "/votes.php", { course_id: course_id }));
            },

            saveVote: function(problem_id, topics) {
                return Promise.resolve($.post(endpoint + "/votes.php", { problem_id: problem_id, topics: topics }));
            }
        };
    };

    function init(endpoint) {
        var api = sampleApi;
        if (endpoint) {
            api = HttpApi(endpoint);
            console.debug("[PR API] Using HTTP API at: " + endpoint);
        } else {
            console.debug("[PR API] Using Sample API");
        }

        for (prop in api) {
            that[prop] = api[prop];
        }
    }

    init(endpoint);
    return that;

}(jQuery, PR_API_URL));

