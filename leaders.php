<?php
include('header.php');

$stats = new stats;
$numberDisplay = 5;
$sumitted = $stats->return_submitted_stats($numberDisplay);
$correct = $stats->return_total_correct_stats($numberDisplay);
echo $twig->render('leaders.html',array('TopSubmitters'=>$sumitted,'TopCorrect'=>$correct));
?>

