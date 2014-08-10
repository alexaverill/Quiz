<h2>Approve Questions:</h2>
<?php
$admin = new AdminQuestions;
$admin->pending_questions();
?>
<br/>
<h2>Open Reports:</h2><br/>
<?php
$display = new Display;
$display->pull_reports();
?>
<br/>
<h2>Reset Event Numbering</h2>
Use this to fix multiple questions showing up on a single event
<form method="POST" action="">
<select name="event">
<?php
$form = new Forms;
$form->return_event_select();
?>
</select>
<input type="submit" class="btn btn-warning" name="resetNumbering" value="Fix Event"/>
</form>