
<?php
$event = 0;
if (is_numeric($_GET['event'])){
    $event =  $_GET['event'];
}
$question = new Questions;
$name = $question->get_event($event);
echo '<h1>'.$name.'</h1>';
if($_POST['check']){
    if($_POST['type']==1 || $_POST['type']==3){
        if($question->check_mc($_POST['idval'],$_POST['response'],$_POST['attempts'],$event)){
            echo '<h2>Correct!</h2><br/>';
        }else{
            echo '<h2>Incorrect</h2><h3>Your Response was: '.$_POST['response'].'</h3><br/>';
        }
    }else if($_POST['type']==2 || $_POST['type']==4){
        
        if($question->check_short($_POST['idval'],$_POST['response'],$_POST['attempts'],$event)){
            echo '<h2>Correct!</h2><br/>';
        }else{
            echo '<h2>Incorrect</h2><h3>Your Response was: '.$_POST['response'].'</h3><br/>';
        }        
    }else{
        echo "There was an error grading<br/>";
    }
    $num = $_POST['attempts']+1;
    $question->get_question($event,$_POST['idval'],$num);
}else{
    $question->get_question($event,null,0);
}
?>
<form method="POST" action="">
    <input type="submit" value="Next Question" name="newQuestion"/>
</form></div>