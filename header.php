<?php
//error_reporting(0);
include('templates/head.php');
include('database.php');
require_once './Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader, array(
    
));
try{
    $dbh= new PDO('mysql:host='.$data_host.';dbname='.$name_database,$data_username,$data_password);
}catch(PDOException $e){
    echo $e->getMessage();
}
include('classes.php');
 	//bridge to phpBB
    define('IN_PHPBB', true);
    $phpbb_root_path = '../';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
$request->enable_super_globals();
$user->session_begin();
$request->enable_super_globals();
$auth->acl($user->data);
$user->setup();    
$request->enable_super_globals(); 
$loggedIn=false;
//check login
if ($user->data['user_id'] == ANONYMOUS)
{
   echo '<a href="../ucp.php?mode=login">Please login to track your progress!</a>';
}
else
{
   $loggedIn = true;
}
?>
