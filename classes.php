<?php
class Forms{
    public function return_events(){
        global $dbh;
        $sql = "SELECT * FROM Events";
        $eventsql=$dbh->prepare($sql);
        $eventsql->execute();
        $eventReturn = $eventsql->fetchAll();
        return $eventReturn;
    }
}
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
class files{
    public function upload($file_name,$file_size,$file_tmp,$file_type){
        $errors= array(); 
        $file_ext=strtolower(end(explode('.',$file_name)));
        $extensions = array("jpeg","jpg","png"); 		
        if(in_array($file_ext,$extensions )=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
        if($file_size > 2097152){
            $errors[]='File size must be excately 2 MB';
        }
        $imageLocation="images/".md5($file_name).'.'.$file_ext;
        if(empty($errors)==true){
            move_uploaded_file($file_tmp,$imageLocation);
            return $imageLocation;
        }else{
            print_r($errors);
            
        }
    }
    public function pull_image($url){
        $ext = end(explode(".",strtolower(basename($url))));
        $name = basename($url);
        $file = file_get_contents($url); 
        $final = md5($name).$ext;
        $location = "images/".$final;
        
        //check if the files are only image / document
        if($ext == "jpg" || $ext == "png" || $ext == "gif"){
             $upload = file_put_contents($location,$file);
        if($upload){
            
        }else{
            echo "Please upload only image/document files";
        }
        return $location;
    }
    }
        
}
class Questions{
    public function check_delim($string){
        //check and see if !# is present.
        //if not append to end.
        //If more then one !# return string to break.
        $delim = '!#';
        $del_place = strpos($string,$delim);
        if(!$del_place){ //append delim if not present. 
            $string = $string .' '.$delim;
            return $string;
        }else{
            return $string;
        }
    }
    public function check_single_delim($string){
        $delim = '!#';
        $first_place = strpos($string,$delim);
        $last_place = strrpos($string,$delim);
        if($first_place && $last_place){
            //if both are equal the delim is in the same place.
            if($first_place==$last_place){
                return true;
            }
        }
        return $false;
    }
    public function add_question($eventId,$question,$a,$b,$c,$d,$e,$correct,$image,$type,$keywords,$userID){
        //$type is one for MC and 2 for fill in the blank/short responses
        //3 is for images
        //$userID=$user->data['user_id'];
        global $dbh;
        $stats = new stats;
        //Lets increase the number of max questions.
        $increase = "UPDATE Events SET maxQuestions = maxQuestions +1 WHERE id=?";
        $increasing = $dbh->prepare($increase);
        $increasing->execute(array($eventId));
        $totalMax=0;
        if($type == 1){
            $sql = "INSERT INTO Questions(eventid,eventNumber,Question,optionA,optionB,optionC,optionD,optionE,correctResponse,questionType,userID)
            Values(?,?,?,?,?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$a,$b,$c,$d,$e,$correct,$type,$userID));
        }elseif($type ==3){
            $sql = "INSERT INTO Questions(eventid,eventNumber,Question,optionA,optionB,optionC,optionD,optionE,correctResponse,questionType,imageLocation,userID) Values(?,?,?,?,?,?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$a,$b,$c,$d,$e,$correct,$type,$image,$userID));
        }elseif($type ==4){
            $question = $this->check_delim($question);
            if(!$this->check_single_delim($question)){
                //if there is more then one deliminator break.
                echo 'Please only use one deliminator.';
                return false;
            }
            $sql = "INSERT INTO Questions(eventid,eventNumber,Question,questionType,KeyWords,imageLocation,userID) Values(?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$type,$keywords,$image,$userID));
        }else{
            $question = $this->check_delim($question);
            if(!$this->check_single_delim($question)){
                //if there is more then one deliminator break.
                echo 'Please only use one deliminator.';
                return false;
            }
            $sql = "INSERT INTO Questions(eventid,eventNumber,question,questionType,KeyWords,userID)Values(?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$type,$keywords,$userID));
        }
        //increase the total each user has submitted.
        $increase = $stats->increase_submitted($userID);
        return true;
    }
    public function return_option($x){
        $option;
        switch($x){
            case 1:
                $option = "optionA";
                break;
            case 2:
                 $option = "optionB";
                break;
            case 3:
                $option = "optionC";
                 break;
            case 4:
                $option = "optionD";
                break;
            case 5:
                $option = "optionE";
                 break;
            default:
                $option = "optionE";
                break;
        }
        return $option;
    }
    public function get_event($eventID){
        global $dbh;
        $sql = "SELECT * FROM Events WHERE id=?";
        $get = $dbh->prepare($sql);
        $get->execute(array($eventID));
        $name = '';
        foreach($get->fetchAll() as $event){
            $name = $event['Event'];
        }
        return $name;
    }
    public function get_number($eventID){
        //returns total number of approved questions for given event
        global $dbh;
        $maxNum = "SELECT * FROM Questions WHERE eventid=? AND Approved=1";
        $getNum = $dbh->prepare($maxNum);
        $getNum->execute(array($eventID));
        $totalMax = $getNum->rowCount();
        return $totalMax;
    }
    public function return_all_events(){
        global $dbh;
        $sql  = "SELECT * FROM Events";
        $get = $dbh->prepare($sql);
        $get->execute(array($division));
        $get = $get->fetchAll();
        return $get;
    }
    public function return_all_events_division($division){
        global $dbh;
        $sql  = "SELECT * FROM Events WHERE division=? order by `Event` ASC";
        $get = $dbh->prepare($sql);
        $get->execute(array($division));
        $get = $get->fetchAll();
        return $get;
    }
    public function select_question($questionID){
        //why private no work for inheritance?
        //pull single question from database
        global $dbh;
        $sql = "SELECT * FROM Questions WHERE idQuestions=?";
        $get_questions = $dbh->prepare($sql);
        $get_questions->execute(array($questionID));
        $questionArray = $get_questions->fetchAll(PDO::FETCH_ASSOC);
        return $questionArray;
    }
    public function get_question($EventId,$questionID){
        global $dbh;
        if($questionID>0){
            $questionArray = $this->select_question($questionID);
            if($questionArray[0]['questionType']== 4 ||  $questionArray[0]['questionType'] ==2){
                $questionArray[0]['Question'] = $this->ProcessFRQ($questionArray[0]['Question']); //create the input box now.
            }
            return $questionArray[0];
        }else{
            $totalQuestions = $this->get_number($EventId);    
            $question = rand(1,$totalQuestions);
            $get_questions_sql = "SELECT * FROM Questions WHERE eventNumber=? AND eventid=?";
            $get_questions = $dbh->prepare($get_questions_sql);
            $get_questions->execute(array($question,$EventId));
            $questionArray = $get_questions->fetchAll(PDO::FETCH_ASSOC);
            if($questionArray[0]['questionType']==4 ||  $questionArray[0]['questionType'] ==2 ){
                $questionArray[0]['Question'] = $this->ProcessFRQ($questionArray[0]['Question']); //create the input box now.
            
            }
            return $questionArray[0];
        }
        
    }
    public function ProcessFRQ($Question) {
        $Deliminator='!#';	
        $InputHTML='<input type="text" name="response"/>';
        
        $SplitQuestion=explode($Deliminator,$Question);
        
        if(count($SplitQuestion)!=2) {
        throw new Exception("Multiple Deliminators");
        }
        
        $OutputString=implode($InputHTML,$SplitQuestion);
        
        return $OutputString;
    }
    public function check_short($questionID,$response,$attempts,$eventID){
        global $dbh;
        $sql = "SELECT * FROM Questions WHERE idQuestions=?";
        $getCorrect = $dbh->prepare($sql);
        $getCorrect->execute(array($questionID));
        $getCorrect=$getCorrect->fetchAll();
        $keywords = $getCorrect[0]['KeyWords'];
        $correct = $this->answermatch($keywords,$response);
        $stats = new stats;
        if($correct){
            if($attempts<=0){
                    
                    $stats->increase_correct($user->data['user_id'],$eventID);
                    $stats->question_increase_correct($questionID);  
                }
               
            return true;
        }else{
            $stats->question_increase_attempts($questionID);
            return false;
        }
        
    }
    function answermatch($answers,$response) {
        /*Matches a response to a comma separated list of answers
        Originally written by Tim Hendricks (TimHendricks at scioly.org)*/
        $cleanresponse = strtolower($response);
        $cleananswers = strtolower($answers); //precaution
       
        /*Double use of (presumably) O(n) operations should be of little
        concern assuming list of answers will always be small*/
        $answerkey = explode(',', $cleananswers); //answers cannot contain commas
       
        #Assumes false then checks for correct
        $answeriscorrect = false;
        foreach ($answerkey as $answer) {
                if($cleanresponse==$answer) {
                                $answeriscorrect = true;
                                break;
                }
        }
        return $answeriscorrect;
    }
    public function check_mc($questionID,$response,$attempts,$eventID){
        global $dbh;
        $sql = "SELECT correctResponse FROM Questions WHERE idQuestions=?";
        $getCorrect = $dbh->prepare($sql);
        $getCorrect->execute(array($questionID));
        foreach($getCorrect->fetchAll() as $correct){
            $stats = new stats;
            if($correct['correctResponse'] == $response){
                if($attempts<=0){
                    
                    $stats->increase_correct($user->data['user_id'],$eventID);
                    $stats->question_increase_correct($questionID);
                }
                return true;
            }else{
                $stats->question_increase_attempts($questionID);
            }
        }
        return false;
    }
    public function rationalize_response($questionID,$responseID){
        global $dbh;
        $sql= "SELECT * FROM Questions WHERE idQuestions=?";
        $rationalize = $dbh->prepare($sql);
        $rationalize->execute(array($questionID));
        $rationalArray = $rationalize->fetchAll();
        $place = $this->return_option($responseID);
        return $rationalArray[0][$place];
    }
    public function report_question($questionID,$report,$userID){
        global $dbh;
        //save report
        $insert = "INSERT INTO reports(questionID,report,userID)VALUES(?,?,?)";
        $go = $dbh->prepare($insert);
        $go->execute(array($questionID,$report,$userID));
        return true;
    }
}
class AdminQuestions extends Questions{
    public function query_questions(){
        global $dbh;
        $sql = "SELECT * FROM Questions WHERE Approved=0";
        $get_needed = $dbh->query($sql);
        return $get_needed->fetchAll();
    }
    public function updateEvent($questID,$event){
        global $dbh;
        $sql = "UPDATE Questions SET eventid=? WHERE idQuestions=?";
        $up = $dbh->prepare($sql);
        $up->execute(array($event,$questID));
    }

    public function update_question($question,$a,$b,$c,$d,$e,$correct,$qid){
        global $dbh;
        $sql = "UPDATE Questions SET Question=?,optionA=?,optionB=? ,optionC=? ,optionD=? ,optionE=?,correctResponse=?  WHERE idQuestions=?";
        try{
            $update = $dbh->prepare($sql);
            $update->execute(array($question,$a,$b,$c,$d,$e,$correct,$qid));
            $this->fix_report($qid);
            return true;
        }catch(PDOException $Exception ) {
            echo $Exception;
            return false;
        }
    }
    public function update_frq($question,$keywords,$qid){
        global $dbh;
        $sql = "UPDATE Questions SET Question=?,KeyWords=? WHERE idQuestions=?";
        try{
            $update = $dbh->prepare($sql);
            $update->execute(array($question,$keywords,$qid));
            $this->fix_report($qid);
            return true;
        }catch(PDOException $Exception ) {
            echo $Exception;
            return false;
        }
    }
    public function fix_report($QID){
        //just set fixed to 1 in reports table
        global $dbh;
        $fix = "UPDATE reports SET fixed=1 WHERE questionID=?";
        try{
            $update = $dbh->prepare($fix);
            $update->execute(array($QID));
            return true;
        }catch(PDOException $Exception ) {
            echo $Exception;
            return false;
        }
    }
    public function questions_approve($eventId,$questionId){
        global $dbh;
        //Lets increase the number of max questions.
        $increase = "UPDATE Events SET totalApproved = totalApproved +1 WHERE id=?";
        $increasing = $dbh->prepare($increase);
        $increasing->execute(array($eventId));
        $totalMax = $this->get_number($eventId);
        $totalMax+=1;
        $setApproved = "UPDATE Questions SET Approved = 1, eventNumber=? WHERE idQuestions = ?";
        $approve = $dbh->prepare($setApproved);
        $approve->execute(array($totalMax,$questionId));
        return true;
    }
    public function questions_reject($questionId){
        global $dbh;
        //Lets increase the number of max questions.
        $setApproved = "UPDATE Questions SET Approved = -1 WHERE idQuestions = ?";
        $approve = $dbh->prepare($setApproved);
        $approve->execute(array($questionId));
        return true;
    }
    public function reset_numbering($eventID){
        global $dbh;
        $sql = "SELECT * FROM Questions WHERE eventID=?";
        $totalRows = $dbh->prepare($sql);
        $totalRows->execute(array($eventID));
        $questionNumber = 1;
        $UPDATE = "UPDATE Questions SET eventNumber=? WHERE idQuestions=?";
        $updating = $dbh->prepare($UPDATE);
        foreach($totalRows->fetchAll() as $questionArray){
            $updating->execute(array($questionNumber,$questionArray['idQuestions']));
            $questionNumber++;
        }
        return true;
    }
    public function pull_reports(){
        //lets pull all the reports first.
        global $dbh;
        $user = new Users;
        $report = "SELECT * FROM reports WHERE fixed=0";
        $reportArray = $dbh->query($report);
        $reportArray = $reportArray->fetchAll();
        $returnArray = array();
        
        foreach($reportArray as $data){
            $question = $this->select_question($data['questionID']);
            $questionFinal = $question[0];
            $returnArray[0]['Report'] = $data['report'];
            $returnArray[0]['user'] = $user->rationalize_userID($data['userID']);
           array_merge($returnArray[0],$question);
        }
        return $returnArray;
    }
}

?>