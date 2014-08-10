<?php
//report.php
//url setup = report.php?Qid=[QUESTIONID];
include('header.php');
$questionID = $_GET['Qid'];
if($_POST['report']){
    $quests = new Questions;
    $quests->report_question($questionID,$_POST['reportText'],$user->data['user_id']);
    echo '<a href="index.php>Report Fixed, return to the home page</a>';
    die();
}
?>
<h2>Report a Question</h2><br/>
Report a question for inaccuracies or other issues.
<form method="POST" action="">
    <textarea name="reportText" class="question_id" id="stylized"></textarea><br/>
    <input type="submit" value="Submit Report" name="report"/>
</form>
