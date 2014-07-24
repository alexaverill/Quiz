<h3><?php $user->data['username'];?> Stats</h3>

<?php
$user = new Users;
$usID = $user->data['user_id']; 
?>

<h4>Question Statistics</h4>
Questions Added:<?php $user->total_submitted($usID);?>
Questions Anwnsers:
Top Event:
<h4>Questions Submitted</h4>
<div class=scroll_container>
</div>