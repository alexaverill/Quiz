<?php
class Users{
    public function total_submitted($userID){
        $stats = new stats;
        $total = $stats->return_submitted($userID);
        return $total;
    }
    public function total_correct($userID){
        $stats = new stats;
        $total = $stats->return_responded($userID);
        return $total;
    }
    public function top_event($userID){
        
    }
    public function get_questions($userID){
        global $dbh;
        $select = "SELECT * FROM Questions WHERE userID=?";
        $get = $dbh->prepare($select);
        $get->execute(array($userID));
        $userQuestions = $get->fetchAll();
        return $userQuestions;
       
    }
    public function rationalize_userID($userID){
		//connect to phpBB3 database and pull username
        include('../config.php');
        $data_host=$dbhost;
        $name_database=$dbname;
        $data_username=$dbuser;
        $data_password=$dbpasswd;
        try{
            $dbh_forums= new PDO('mysql:host='.$data_host.';dbname='.$name_database,$data_username,$data_password);
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        //convert userID to username.
        $get= "SELECT * FROM phpbb_users WHERE user_id=?";
        $user = $dbh_forums->prepare($get);
        $user->execute(array($userID));
        $user = $user->fetchAll();
        $username=$user[0]['username'];
        return $username;
    }
}

?>
