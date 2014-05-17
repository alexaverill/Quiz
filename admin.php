<?php
include('header.php');
$display = new Display;
$adminQuestions = new AdminQuestions;
if($_POST['approve']){
    $counter = 0;
    foreach($_POST['approval'] as $post){
        $adminQuestions->questions_approve($_POST['eventId'][$counter],$post);
        $counter +=1;
    }
    $display->display("admin_template.php");
}else{
    $display->display("admin_template.php");
}
?>