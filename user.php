<?php
include('header.php');
if($loggedIn){
$Display = new Display;
$Display->template('users.php');
}else{
    echo '<br/><a href="../ucp.php?mode=login">Please Login to view your stats.</a>';
}
?>