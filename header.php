<?php
//error_reporting(0);
include('templates/head.php');
include('database.php');
try{
    $dbh= new PDO('mysql:host='.$data_host.';dbname='.$name_database,$data_username,$data_password);
}catch(PDOException $e){
    echo $e->getMessage();
}
include('classes.php');

if(getcwd() != '/var/www/Quiz'){
    define('IN_PHPBB', true);
    $phpbb_root_path = '../';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
    }
$loggedIn=false;
//check login
if ($user->data['user_id'] == ANONYMOUS)
{
   echo '<a href="../ucp.php?mode=login">Please login to track your progress!</a>';
}
else
{
   //echo 'Welcome '.$user->data['username_clean'];
   $loggedIn = true;
}
?>