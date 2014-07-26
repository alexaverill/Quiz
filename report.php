<?php
//report.php
//url setup = report.php?Qid=[QUESTIONID];
include('header.php');
$questionID = $_GET['Qid']
?>
<h2>Report a Question</h2>
Report a question for inaccuracies or other issues.
<form method="POST" action="">
    Issue:<textarea name="reportText" class="question_id" id="stylized"></textarea><br/>
    <input type="submit" value="Submit Report" name="report"/>
</form>