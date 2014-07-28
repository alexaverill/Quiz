<?php
include('header.php');
if($loggedIn){
$usID =  $user->data['user_id'];
$user = new Users;
?>
<h3><?php $user->data['username'];?> Stats</h3>

<h4>Question Statistics</h4>
Questions Added:<?php echo $user->total_submitted($usID);?><br/>
Questions Answered Correctly:<?php echo $user->total_correct($usID);?><br/>
<!--Top Event:<br/>-->
<h4>Questions Submitted</h4>
<div class=scroll_container>
    <?php
       $user->get_questions($usID);
        
    ?>
</div>
<?php
}else{
    echo '<br/><a href="../ucp.php?mode=login">Please Login to view your stats.</a>';
}
?>