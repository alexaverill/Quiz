<?php
include('header.php');
$display = new Display;
$stats = new stats;
?>
<h2>Most Questions Submitted</h2>
<?php $display->top_sumitters();?>
<br/>
<h2>Most Correct</h2>