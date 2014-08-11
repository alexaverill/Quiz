<?php
include('header.php');
$display = new Display;
$stats = new stats;
$sumitted = $stats->return_top_sumitters();
$correct = $stats->return_top_correct();
echo $twig->render('leaders.html',array('TopSubmitters'=>$sumitted,'TopCorrect'=>$correct));
?>

