<?php
include('header.php');
$display = new Display;
$adminQuestions = new AdminQuestions;
if($user->data['group_id']==5 ||$user->data['group_id'] ==4|| $user->data['group_id']==110 ||$user->data['group_id'] ==7){
    if($_POST['changeEvent']){
        $adminQuestions->updateEvent($_POST['Changequest'],$_POST['realEvent']);
    }
    if($_POST['approve']){
        $counter = 0;
        foreach($_POST['approval'] as $post){
            $adminQuestions->questions_approve($_POST['eventId'][$counter],$post);
            $counter +=1;
        }
        /*foreach($_POST['reject'] as $post){
            $adminQuestions->questions_reject($post);
        }*/
        $display->template("admin_template.php");
    }else{
        $display->template("admin_template.php");
    }

}
?>