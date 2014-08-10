<div>
<h2>Approve Questions:</h2>
<?php
$admin = new AdminQuestions;
$admin->pending_questions();
?>
</div>
<div>
<h2>Open Reports:</h2><br/>
<?php
$display = new Display;
$display->pull_reports();
?>
</div>
<div><h2>Reset Event Numbering</h2></div>
<div>Use this to fix multiple questions showing up on a single event</div>
<form method="POST" action="" class="inline-form">
<select name="event">
<?php
$form = new Forms;
echo $form->return_event_select();
?>
</select>
<input type="submit" class="btn btn-warning" name="resetNumbering" value="Fix Event"/>
</form>