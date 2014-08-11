<?php
include('header.php');

$stats = new stats;
$sumitted = $stats->return_top_sumitters();
$correct = $stats->return_top_correct();
echo $twig->render('leaders.html',array('TopSubmitters'=>$sumitted,'TopCorrect'=>$correct));
?>

