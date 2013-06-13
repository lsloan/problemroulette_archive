<?php include 'start.php'; ?>

<p>The links below serve randomly-chosen questions, one at a time, from banks of multiple-choice problems derived from past exams.</p>
<p>Please select an exam to begin.  For each problem, you have the option to  submit your answer or skip to the next one.</p>


	<ul class="topic-selector">
		<li><input type="checkbox"/><a href="roulette.php?exam=135m1">Physics 135 Midterm 1</a></li>
		<li><input type="checkbox"/><a href="roulette.php?exam=135m2">Physics 135 Midterm 2</a></li>
		<li><input type="checkbox"/><a href="roulette.php?exam=135m3">Physics 135 Midterm 3</a></li>
		<li><input type="checkbox"/><a href="roulette.php?exam=135f">Physics 135 Final Exam</a></li>
	</ul>

    <a href="index.php" class="btn btn-courses"><i class="icon-arrow-left"></i>Select Different Course</a>
	<a href="/" id="use-selected" class="btn btn-primary disabled">Use Selected Topics</a>


<?php include 'end.php'; ?>