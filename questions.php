<?php
include('header.php');
$display = new Display;
if(!isset($_GET['event'])){
    $display->listEvents('B');
    $display->listEvents('C');
}else if($_POST['newQuestion']){
    include('templates/questions_template.php');
}else{
    include('templates/questions_template.php');
}
?>