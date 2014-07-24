<?php
include('header.php');
if($loggedIn){

?>
<h3><?php $user->data['username'];?> Stats</h3>

<h4>Question Statistics</h4>
Questions Added:<?php     $usID =  $user->data['user_id'];
    $user = new Users; $user->total_submitted($usID);?>
Questions Anwnsers:
Top Event:
<h4>Questions Submitted</h4>
<div class=scroll_container>
</div>
<?php
}else{
    echo '<br/><a href="../ucp.php?mode=login">Please Login to view your stats.</a>';
}
?>