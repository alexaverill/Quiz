<div id="centered">
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
        if($question->check_mc($_POST['idval'],$_POST['response'])){
            echo '<h2>Correct!</h2><br/>';
        }else{
            echo '<h2>Incorrect</h2><br/>';
        }
    }else if($_POST['type']==2){
        if($question->check_short($_POST['idval'],$_POST['response'])){
            echo '<h2>Correct!</h2><br/>';
        }else{
            echo '<h2>Incorrect</h2><br/>';
        }        
    }else{
        echo "There was an error grading<br/>";
    }
    $name = $question->get_event($event);
    echo "<h2>$name</h2>";
    $question->get_question($event,$_POST['idval']);
}else{
    $question->get_question($event,null);
}
?>
<form method="POST" action="">
    <input type="submit" value="Next Question" name="newQuestion"/>
</form></div>