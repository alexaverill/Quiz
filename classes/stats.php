<?php

class stats{
    private function check_user_row($userID,$type){
        //returns true if the row exists, false if it doesn't
        //determines if the user has a preexisting row
        global $dbh;
        if($type==1){
            $check = "SELECT * FROM userOverall WHERE userId=?";
        }else{
            $check = "SELECT * FROM userData WHERE userid=?";
        }
        $runCheck = $dbh->prepare($check);
        $runCheck->execute(array($userID));
        $num = $runCheck->rowCount();
        if($num==0){
            return false; 
        }
        return true;
    }
    public function rationalize_userID($userID){
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
    public function increase_correct($userID,$eventID){
       global $dbh;
         global $user;
       $userID= $user->data['user_id'];

        //increase correct based on event and User.
        //increase the overall numbers, as well as the individual event numbers.
        if($this->check_user_row($userID,1)){
            //IF row exists just add to the total submitted;
           $increase = "UPDATE userOverall SET correct= correct +1 WHERE userId=?";
           $increasing = $dbh->prepare($increase);
           $increasing->execute(array($userID));
        }else{
            //create and set submittted to 1;
            $create = "INSERT INTO userOverall(userId,correct,submitted) VALUES(?,?,?)";
            $go = $dbh->prepare($create);
            $go->execute(array($userID,1,0));
        }
        //indivual events table increase.
                if($this->check_user_row($userID,2)){
            //IF row exists just add to the total submitted;
           $increase = "UPDATE userData SET numberCorrect= numberCorrect +1 WHERE userid=? AND eventid=?";
           $increasing = $dbh->prepare($increase);
           $increasing->execute(array($userID,$eventID));
        }else{
            //create and set submittted to 1;
            $create = "INSERT INTO userData(userid,eventid,numberCorrect) VALUES(?,?,?)";
            $go = $dbh->prepare($create);
            $go->execute(array($userID,$eventID,1));
        }
    }
    public function increase_submitted($userID){
        global $dbh;
        //add 1 to the userOverall submitted column.
        //check if user exists in stat tables
        //echo $user->data['user']
        if($this->check_user_row($userID,1)){
            //IF row exists just add to the total submitted;
           $increase = "UPDATE userOverall SET submitted = submitted +1 WHERE userId=?";
           $increasing = $dbh->prepare($increase);
           $increasing->execute(array($userID));
        }else{
            //create and set submittted to 1;
            $create = "INSERT INTO userOverall(userId,correct,submitted) VALUES(?,?,?)";
            $go = $dbh->prepare($create);
            $go->execute(array($userID,0,1));
        }
    }
    private function check_question_table($qid){
        global $dbh;
        $check = "SELECT * FROM questionStats WHERE questionID=?";
        $runCheck = $dbh->prepare($check);
        $runCheck->execute(array($qid));
        $num = $runCheck->rowCount();
        if($num==0){
            return false; 
        }
        return true;
    }
    public function question_increase_correct($qid){
        global $dbh;
        if($this->check_question_table($qid)){
            $sql = "UPDATE questionStats SET correct = correct+1,total = total+1 WHERE questionID=?";
            try{
                $update = $dbh->prepare($sql);
                $update->execute(array($qid));
            }catch(PDOException $Exception ) {
                echo $Exception;
                return false;
            }
        }else{
            $sql = "INSERT INTO questionStats(questionID,correct,attempts) VALUES(?,?,?,?)";
            try{
                $update = $dbh->prepare($sql);
                $update->execute(array($qid,1,0,1));
            }catch(PDOException $Exception ) {
                echo $Exception;
                return false;
            }
        }
    }
    public function question_increase_attempts($qid){
        global $dbh;
        if($this->check_question_table($qid)){
            $sql = "UPDATE questionStats SET attempts = attempts+1, total = total+1 WHERE questionID=?";
            try{
                $update = $dbh->prepare($sql);
                $update->execute(array($qid));
            }catch(PDOException $Exception ) {
                echo $Exception;
                return false;
            }
        }else{
            $sql = "INSERT INTO questionStats(questionID,correct,attempts,total) VALUES(?,?,?,?)";
            try{
                $update = $dbh->prepare($sql);
                $update->execute(array($qid,0,1,1));
            }catch(PDOException $Exception ) {
                echo $Exception;
                return false;
            }
        }
    }
    private function pull_data($userID){
        global $dbh;
        $get = "SELECT * FROM userOverall WHERE userId=?";
        $numbers = $dbh->prepare($get);
        $numbers->execute(array($userID));
        $returning = $numbers->fetchAll();
        return $returning;
    }
    public function return_submitted($userID){
        $data = $this->pull_data($userID);
        return $data[0]['submitted'];
    }
    public function return_responded($userID){
        $data = $this->pull_data($userID);
        return $data[0]['correct'];
    }
    public function top_event($userID){
       $data = $this->pull_data($userID);
    }
    public function return_submitted_stats($number){
        global $dbh;
        $select = 'SELECT * FROM userOverall ORDER BY submitted DESC LIMIT '.$number;
        $go = $dbh->query($select);
        return $go;
    }
    public function return_total_correct_stats($number){
        global $dbh;
        $select = 'SELECT * FROM userOverall ORDER BY correct DESC LIMIT '.$number;
        $go = $dbh->query($select);
        return $go;
    }
    public function return_top_correct(){
        
        $user = new Users;
        $numberDisplay = 5;
        $top = $this->return_total_correct_stats($numberDisplay);
        $returnArray = array();
        $number=0;
        foreach($top as $info){
                $returnArray[$number]['name']=$user->rationalize_userID($info['userId']);
                $returnArray[$number]['number']= $info['correct'];
                $number++;
            
        }
        return $returnArray;
    }
    public function return_top_sumitters(){
        
        $user = new Users;
        $numberDisplay = 5;
        $top = $this->return_submitted_stats($numberDisplay);
        $returnArray = array();
        $number=0;
        foreach($top as $info){
                $returnArray[$number]['name']=$user->rationalize_userID($info['userId']);
                $returnArray[$number]['number']= $info['submitted'];
                $number++;
            
        }
        return $returnArray;
    }
}

?>