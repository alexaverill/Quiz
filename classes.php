<?php
class Forms{
    public function return_event_select(){
        global $dbh;
        $sql = "SELECT * FROM Events";
        $eventsql=$dbh->prepare($sql);
        $eventsql->execute();
        $html = '<option></option>';
        foreach($eventsql->fetchAll() as $event){
            $id = $event['id'];
            $eventName = $event['Event'];            
            $html .= "<option value=".$id.">$eventName</option>";
        }
        return $html;
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
    private function rationalize_userID($userID){
        //convert userID to username.
        return $username;
    }
    public function increase_correct($userID,$eventID){
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
        //add 1 to the userOverall submitted column.
        //check if user exists in stat tables
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
    public function top_correct($number,$eventID){
        //return top $number of a category
        //
    }
    public function top_submitter($number,$eventID){
        
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
}
class Questions{
    public function add_question($eventId,$question,$a,$b,$c,$d,$e,$correct,$image,$type,$keywords,$username,$userID){
        //$type is one for MC and 2 for fill in the blank/short responses
        //3 is for images
        global $dbh;
        $stats = new stats;
        //Lets increase the number of max questions.
        $increase = "UPDATE Events SET maxQuestions = maxQuestions +1 WHERE id=?";
        $increasing = $dbh->prepare($increase);
        $increasing->execute(array($eventId));
        $maxNum = "SELECT maxQuestions FROM Events WHERE id=?";
        $getNum = $dbh->prepare($maxNum);
        $getNum->execute(array($eventId));
        $totalMax = 1;
        foreach($getNum->fetchAll() as $row){
            $totalMax = $row['maxQuestions'];
        }
        if($type == 1){
            $sql = "INSERT INTO Questions(eventid,eventNumber,Question,optionA,optionB,optionC,optionD,optionE,correctResponse,questionType,username) Values(?,?,?,?,?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$a,$b,$c,$d,$e,$correct,$type,$username));
        }elseif($type ==3){
            $sql = "INSERT INTO Questions(eventid,eventNumber,Question,optionA,optionB,optionC,optionD,optionE,correctResponse,questionType,imageLocation,username) Values(?,?,?,?,?,?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$a,$b,$c,$d,$e,$correct,$type,$image,$username));
        }else{
            $sql = "INSERT INTO Questions(eventid,eventNumber,question,questionType,KeyWords,username)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$type,$keywords,$username));
        }
        //increase the total each user has submitted.
        $stats->increase_submitted($userID);
        
    }
    protected function return_option($x){
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
    public function return_all_events($division){
        global $dbh;
        $sql  = "SELECT * FROM Events WHERE division=?";
        $get = $dbh->prepare($sql);
        $get->execute(array($division));
        $get = $get->fetchAll();
        return $get;
    }
    public function get_question($EventId,$questionID,$attempts){
        global $dbh;
        if($questionID>0){
             $sql = "SELECT * FROM Questions WHERE idQuestions=?";
             $get_questions = $dbh->prepare($sql);
             $get_questions->execute(array($questionID));
             $questionArray = $get_questions->fetchAll();
                $id = $questionArray[0][idQuestions];
                if($questionArray[0]['questionType'] == 3){
                    echo '<img src="'.$questionArray[0]['imageLocation'].'" max-width=300 max-height=300/><br/>';
                }
                echo $questionArray[0][Question];
                echo '<form method="POST" action="">';
                echo '<input type=hidden name=idval value="'.$id.'"/>';
                 echo '<input type=hidden name=at value="'.$attempts.'"/>';
                for($x = 1; $x<=5; $x++){
                    $option = $this->return_option($x);
                    echo '<label><input type="checkbox" value="'.$x.'" name="response"/>'.$questionArray[0][$option].'</label><br/>';
                }
                echo '<input type="Submit" value="Check Question" name="check">';
        }else{
            $sql = "SELECT * FROM Events WHERE id=?";
            $get_num = $dbh->prepare($sql);
            $get_num->execute(array($EventId));
            $totalQuestions = 0;
            foreach($get_num->fetchAll() as $row){
                $totalQuestions = $row['totalApproved'];
            }
            if($totalQuestions == 0 ){
                echo '<h1>This event has no questions, why not <a href="new_question.php">add some?</a></h1>';
                return;
            }else if($totalQuestions <= 50 ){
                 echo '<h1>This event only has a few questions, why not <a href="new_question.php">add some?</a></h1>';
            }
            $question = rand(1,$totalQuestions);
            $get_questions_sql = "SELECT * FROM Questions WHERE eventNumber=? AND eventid=?";
            $get_questions = $dbh->prepare($get_questions_sql);
            $get_questions->execute(array($question,$EventId));
            foreach($get_questions->fetchAll() as $questionArray){
                //should figure out how to template this correctly
                if($questionArray['questionType'] == 3){
                    echo '<img src="'.$questionArray['imageLocation'].'" max-width=300 max-height=300/><br/>';
                }
                echo $questionArray['Question'];
                $id = $questionArray['idQuestions'];
                    echo '<form method="POST" action="">';
                    echo '<input type=hidden name=type value="'.$questionArray['questionType'].'"/>';
                    echo '<input type=hidden name=idval value="'.$id.'"/>';
                    echo '<input type=hidden name=at value="'.$attempts.'"/>';
                    for($x = 1; $x<=5; $x++){
                        $option = $this->return_option($x);
                        echo '<label><input type="checkbox" value="'.$x.'" name="response"/>'.$questionArray[$option].'</label><br/>';
                    }
                    echo '<input type="Submit" value="Check Question" name="check">';
                }
        }
    }
    public function check_short($questionID,$response){
        global $dbh;
        $sql = "SELECT * FROM Questions WHERE idQuestions=?";
        $getCorrect = $dbh->prepare($sql);
        $getCorrect->execute(array($questionID));
        $getCorrect=$getCorrect->fetchAll();
        $keywords = $getCorrect[0]['keywords'];
        $correct = answermatch($keywords,$response);
        return $correct;
        
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
            if($correct['correctResponse'] == $response){
                if($attempts<=0){
                    $stats = new stats;
                    $stats->increase_correct($user->data['user_id'],$event);
                }
                return true;
            }
        }
        return false;
    }
}
class AdminQuestions extends Questions{
    private function query_questions(){
        global $dbh;
        $sql = "SELECT * FROM Questions WHERE Approved=0";
        $get_needed = $dbh->query($sql);
        return $get_needed->fetchAll();
    }
    public function pending_questions(){
        $input = $this->query_questions();
       
        echo '<form method="post" action="">';
        foreach($input as $pending){
            $event = $this->get_event($pending['eventid']);
            echo '<h3>Question:</h3>';
            echo 'Event: '.$event.'<br/>';
            
            if($pending['questionType']==3){
                echo '<img src="'.$pending['imageLocation'].'" width=200 height=300/><br/>';
            }
            echo '<input type="hidden" value='.$pending['eventid'].' name="eventId[]"/>';
            echo 'Question: '.$pending['Question'].'<br/>';
            echo '1.'.$pending['optionA'].'<br/>';
            echo '2.'.$pending['optionB'].'<br/>';
            echo '3.'.$pending['optionC'].'<br/>';
            echo '4.'.$pending['optionD'].'<br/>';
            echo '5.'.$pending['optionE'].'<br/>';
            echo 'Correct: '.$pending['optionA'].'<br/>';
            echo '<label>Approve <input type="checkbox" value="'.$pending['idQuestions'].'" name="approval[]"/></label><br/>';
            
            
       }
       echo '<br/><input type="Submit" value="Approve" name="approve"/>';
    }
    public function questions_approve($eventId,$questionId){
        global $dbh;
        //Lets increase the number of max questions.
        $increase = "UPDATE Events SET totalApproved = totalApproved +1 WHERE id=?";
        $increasing = $dbh->prepare($increase);
        $increasing->execute(array($eventId));
        $setApproved = "UPDATE Questions SET Approved = 1 WHERE idQuestions = ?";
        $approve = $dbh->prepare($setApproved);
        $approve->execute(array($questionId));
        return true;
    }
}
class Display{
    public function template($file_name){
	/*
	 *general purpose templating function. Can be either the general file name such as admin_mail, the full template name
	 *such as admin_mail_template,or admin_mail_template.php
	 *General purpose to make my life easier. Or harder. Best practice is full name but wanted to test the modularity.
	 **/
	//First if it has a php tag, we are going to assume that is the best way to go.
	//if(strpos($file_name, '.php') !== false){
	    $full_name = 'templates/'.$file_name;
            include($full_name);
            return;
	/*}else if(strpos($file_name, 'template') !== false){
	    //lets just append a .php to see if that is a template
	    $full_name = 'templates/'.$file_name.'.php';
	    include($full_name);
	}else{
	    //last chance to get it to appear.
	    $full_name = 'templates/'.$file_name.'_template.php';
	     include($full_name);
	}*/
	
    }
    public function listEvents($division){
        $questions = new Questions;
        $events = $questions->return_all_events($division);
         echo '<table class="table table-striped table-bordered table-condensed table-hover"
        style="float:left; width:400px; margin-left: 30px; margin-top:20px"><tr><th>'.$division.' Division Events</th></tr>';
        
        foreach ($events as $single){
            //echo '<a href=?event='.$single['id'].'>'.$single['Event'].'</a><br/>';
             echo '<tr><td><a href=?event='.$single['id'].'>'.$single['Event'].'</a></td></tr>';
        }
         echo '</table>';
    }
}

?>