<?php
include('header.php');
$event = 0;
if (is_numeric($_GET['event'])){
    $event =  $_GET['event'];
}
$question = new Questions;
$name = $question->get_event($event);
$number = $question->get_number($event);
$questionTotal = $question->get_number($event);

            
$status= '3';
$incorrect='';
$number_attempts = 0;
if($_POST['check']){
    if($_POST['type']==1 || $_POST['type']==3){
        if($status = $question->check_mc($_POST['idval'],$_POST['response'],$_POST['attempts'],$event)){
            $status=1;
        }else{
            $status = 2;
            $incorrect= $question->rationalize_response($_POST['response']);
        }
    }else if($_POST['type']==2 || $_POST['type']==4){
        if($status = $question->check_short($_POST['idval'],$_POST['response'],$_POST['attempts'],$event)){
            $status=1;
        }else{
            $status = 2;
            $incorrect = $_POST['response'];
        }
    }
    $number_attempts = $_POST['attempts']+1;
    $questionArray =  $question->get_question($event,$_POST['idval']);
}else{
    $questionArray =  $question->get_question($event,null);
}
echo $twig->render('questions.html',array('QuestionNumber'=>$questionTotal,'EventName'=>$name,'TotalQuestions'=>$number,'Status'=>$status,'question'=>$questionArray,'Attempts'=>$number_attempts,'Incorrect'=>$incorrect));