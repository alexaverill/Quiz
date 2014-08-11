<?php
include('header.php');
//if($loggedIn){
$usID =  $user->data['user_id'];
$usID=10604;
$user = new Users;
$stats = new stats;
$questionsAdded = $stats->return_submitted($usID);
$questionsCorrect = $stats->return_responded($usID);
$userQuestions = $user->get_questions($usID);
//var_dump($userQuestions);
echo $twig->render('userStats.html',array('Username'=>$user->data['username_clean'],'QuestionsAdded'=>$questionsAdded,'QuestionsCorrect'=>$questionsCorrect,'UserQuestions'=>$userQuestions));
