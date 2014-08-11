<?php
include('header.php');
$display = new Display;
$adminQuestions = new AdminQuestions;
if($user->data['group_id']==5 ||$user->data['group_id'] ==4|| $user->data['group_id']==110 ||$user->data['group_id'] ==7){
    if($_POST['changeEvent']){
        $adminQuestions->updateEvent($_POST['Changequest'],$_POST['realEvent']);
    }
    if($_POST['close']){
        $adminQuestions->fix_report($_POST['idval']);
    }
    if($_POST['update']){
        $adminQuestions->update_question($_POST['textChange'],$_POST['response1'],$_POST['response2'],$_POST['response3'],$_POST['response4'],$_POST['response5'],$_POST['correct_answer'],$_POST['idval']);
    }
    if($_POST['updateFRQ']){
        $adminQuestions->update_frq($_POST['textChange'],$_POST['keywords'],$_POST['idval']);
    }
    if($_POST['resetNumbering']){
        $adminQuestions->reset_numbering($_POST['event']);
    }
    if($_POST['approve']){
        //$counter = 0;
        //foreach($_POST['approval'] as $post){
        if($_POST['approval']){
            $adminQuestions->questions_approve($_POST['eventId'],$_POST['approval']);
           // $counter +=1;
        }
        if($_POST['reject']){
            $adminQuestions->questions_reject($_POST['reject']);
        }
    }
    $NameStatus = 0;
    $reported = $adminQuestions->pull_reports();
    $questions = $adminQuestions->query_questions();
    $eventList = $question->return_all_events();
    if($questions['eventid']>0){
        $NameStatus = 1;
    }
    echo $twig->render('adminHTML.html',array('ReportArray'=>$reported,'ApprovalArray'=>$questions,'Event'=>$NameStatus,'EventList'=>$eventList));
}

?>