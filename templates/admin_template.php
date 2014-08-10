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