<?php
include('header.php');
$questions = new Questions;
$eventsB = $questions->return_all_events_division('B');
$eventsC = $questions->return_all_events_division('C');
echo $twig->render('eventTables.html', array('eventsB'=>$eventsB,'eventsC'=>$eventsC));
?>