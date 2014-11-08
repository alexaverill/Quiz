<?php
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
        //defualt time is eastern for this area of the application
        date_default_timezone_set('America/New_York');
        $date = date("Y-m-d H:i:s", time());
        $totalMax=0;
        if($type == 1){
            $sql = "INSERT INTO Questions(eventid,eventNumber,Question,optionA,optionB,optionC,optionD,optionE,correctResponse,questionType,time,userID)
            Values(?,?,?,?,?,?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$a,$b,$c,$d,$e,$correct,$type,$date,$userID));
        }elseif($type ==3){
            $sql = "INSERT INTO Questions(eventid,eventNumber,Question,optionA,optionB,optionC,optionD,optionE,correctResponse,questionType,imageLocation,time,userID) Values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$a,$b,$c,$d,$e,$correct,$type,$image,$date,$userID));
        }elseif($type ==4){
            $question = $this->check_delim($question);
            if(!$this->check_single_delim($question)){
                //if there is more then one deliminator break.
                echo 'Please only use one deliminator.';
                return false;
            }
            $sql = "INSERT INTO Questions(eventid,eventNumber,Question,questionType,KeyWords,imageLocation,time,userID) Values(?,?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$type,$keywords,$image,$date,$userID));
        }else{
            $question = $this->check_delim($question);
            if(!$this->check_single_delim($question)){
                //if there is more then one deliminator break.
                echo 'Please only use one deliminator.';
                return false;
            }
            $sql = "INSERT INTO Questions(eventid,eventNumber,question,questionType,KeyWords,time,userID)Values(?,?,?,?,?,?,?)";
            $add = $dbh->prepare($sql);
            $add->execute(array($eventId,$totalMax,$question,$type,$keywords,$date,$userID));
        }
        //increase the total each user has submitted.
        $increase = $stats->increase_submitted($userID);
        return true;
    }
    public function return_option($x){
		//This function converts the numerical value in the question form, to the form needed for the database.
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
		//select all the events from the database
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
            $question = mt_rand(1,$totalQuestions);
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
		//converts the FRQ deliminator to a input box.
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
    private function removeLeadSpace($string){
        if($string[0]==' '){
            $string = substr($string,1);
            return $string;
        }else{
           return $string; 
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
            //echo $answer;
            if(is_numeric($cleanresponse)){
                //attempt to clean correct response.
                //need to make sure there are no leading spaces
                $answer = $this->removeLeadSpace($answer);
                preg_match("/\D/is", $answer, $match_list, PREG_OFFSET_CAPTURE);
                $char_location = $match_list[0][1];
                $check = substr($answer,0,$char_location);
                if($check == $response){
                    return true;
                    break;
                }

            }
                if($cleanresponse==$answer) {
                                $answeriscorrect = true;
                                break;
                }elseif ($answer== 't' || $answer == 'true'){
                    if($cleanresponse == 'true' || $cleanresponse == 't'){
                        $answeriscorrect = true;
                        break;
                    }
                }elseif ($answer== 'f' || $answer == 'false'){
                    if($cleanresponse == 'false' || $cleanresponse == 'f'){
                        $answeriscorrect = true;
                        break;
                    }
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
		//convert an option value to to the response the option was.
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


?>
