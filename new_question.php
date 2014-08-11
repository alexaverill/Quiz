<?php
include('header.php');
if($_POST['MCQuestion']){
    $Question = new Questions;
    $input = htmlspecialchars($_POST['inputquest']);
    //echo  $_FILES['userfile']['name'];
    $usID=$user->data['user_id'];
    if($_FILES['userfile']['name']>1){
        $file = new files;
        $imageLocation=$file->upload($_FILES['userfile']['name'],$_FILES['userfile']['size'],$_FILES['userfile']['tmp_name'],$_FILES['userfile']['type']);
        if($Question->add_question($_POST['event'],$input,$_POST['option1'],$_POST['option2'],$_POST['option3'],$_POST['option4'],$_POST['option5'],$_POST['correct_answer'],$imageLocation,3,NULL,$usID)){
            echo 'Question Added';
        }else{
            echo 'There was an error';
        }
    } else if( strlen($_POST['URL'])>1){
         $file = new files;
        $imageLocation=$file->pull_image($_POST['URL']);
        if($Question->add_question($_POST['event'],$input,$_POST['option1'],$_POST['option2'],$_POST['option3'],$_POST['option4'],$_POST['option5'],$_POST['correct_answer'],$imageLocation,3,NULL,$usID)){
            echo 'Question Added';
        }else{
            echo 'There was an error';
        }
        
    }else{
        if($Question->add_question($_POST['event'],$input,$_POST['option1'],$_POST['option2'],$_POST['option3'],$_POST['option4'],$_POST['option5'],$_POST['correct_answer'],NULL,1,NULL,$usID)){
        Echo 'Question Added';
        }else{
            echo 'There was an error';
        }
    }
}
if($_POST['FRQuestion']){
    $Question = new Questions;
    $input = htmlspecialchars($_POST['inputquest']);
    $usID=$user->data['user_id'];
    if($_FILES['userfile']['name']>1){
        $file = new files;
        $imageLocation=$file->upload($_FILES['userfile']['name'],$_FILES['userfile']['size'],$_FILES['userfile']['tmp_name'],$_FILES['userfile']['type']);
                // add_question($eventId,    $question,   $a,  $b,   $c, $d, $e,  $correct,$image,  $type, $keywords,   $userID)
        if($Question->add_question($_POST['event'],$input,NULL,NULL,NULL,NULL,NULL,NULL,$imageLocation,4,$_POST['keywords'],$usID)){
            echo 'Question Added';
            
        }else{
            echo 'There was an error';
        }
    } else if( strlen($_POST['URL'])>1){
        
         $file = new files;
        $imageLocation=$file->pull_image($_POST['URL']);
                 // add_question($eventId,$question,        $a,  $b,   $c, $d, $e,  $correct,$image,  $type, $keywords,   $userID)
        if($Question->add_question($_POST['event'],$input,NULL,NULL,NULL,NULL,NULL,NULL,$imageLocation,4,$_POST['keywords'],$usID)){
            echo 'Question Added';
        }else{
            echo 'There was an error';
        }
        
    }else{
        if($Question->add_question($_POST['event'],$input,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,$_POST['keywords'],$usID)){
            Echo 'Question Added';
        }else{
            echo 'There was an error';
        }
    }
}
if($loggedIn){
    $events = $question->return_all_events();
 echo $twig->render('question_entry.html',array('EventList'=>$events));
}else{
    echo '<br/><a href="../ucp.php?mode=login">Please Login to add Questions</a>';
}

?>