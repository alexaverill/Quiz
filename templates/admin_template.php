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

<?php
$admin->reset_numbering('12');
?>