<?php
include('header.php');
$questions = new Questions;
$eventsB = $questions->return_all_events('B');
$eventsC = $questions->return_all_events('C');
echo $twig->render('eventTables.html', array('eventsB'=>$eventsB,'eventsC'=>$eventsC));
?>