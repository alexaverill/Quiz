<?php
include('header.php');

$stats = new stats;
$numberDisplay = 5;
$submitted = $stats->return_top_sumitters();
 $correct = $stats->return_total_correct_stats($numberDisplay);
echo $twig->render('leaders.html',array('TopSubmitters'=>$submitted,'TopCorrect'=>$correct));
?>

