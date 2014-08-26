<?php
class AdminQuestions extends Questions{
    public function queryQuestions(){
        global $dbh;
        $sql = "SELECT * FROM Questions WHERE Approved=0";
        $get_needed = $dbh->query($sql);
        return $get_needed->fetchAll();
    }
    public function updateEvent($questID,$event){
        //Updates events on a question, takes in questionID
        global $dbh;
        $sql = "UPDATE Questions SET eventid=? WHERE idQuestions=?";
        $up = $dbh->prepare($sql);
        $up->execute(array($event,$questID));
    }

    public function updateMCQuestion($question,$a,$b,$c,$d,$e,$correct,$qid){
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
    public function updateFRQ($question,$keywords,$qid){
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
    public function fixReport($QID){
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
    public function questionsApprove($eventId,$questionId){
        global $dbh;
        $totalMax = $this->get_number($eventId);
        $totalMax+=1; 
        $setApproved = "UPDATE Questions SET Approved = 1, eventNumber=? WHERE idQuestions = ?";
        $approve = $dbh->prepare($setApproved);
        $approve->execute(array($totalMax,$questionId));
        return true;
    }
    public function questionsReject($questionId){
        //reject question by setting approved to -1
        global $dbh;
        $setApproved = "UPDATE Questions SET Approved = -1 WHERE idQuestions = ?";
        $approve = $dbh->prepare($setApproved);
        $approve->execute(array($questionId));
        return true;
    }
    public function resetRumbering($eventID){
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
    public function pullReports(){
        //function pulls active reports, and returns an array that can be processed by Twig
        global $dbh;
        $user = new Users;
        $report = "SELECT * FROM reports WHERE fixed=0";
        $reportArray = $dbh->query($report);
        $reportArray = $reportArray->fetchAll();
        $returnArray = array();
        foreach($reportArray as $data){
            $question = $this->select_question($data['questionID']);
            $question[0]['Report'] = $data['report'];
            $question[0]['user'] = $user->rationalize_userID($data['userID']);
            $returnArray =array_merge($returnArray,$question);
        }
        return $returnArray;


    }
    public function deleteQuestion($questionid){
        global $dbh;
        $this->fix_report($questionid);
        $sql = "DELETE FROM Questions WHERE idQuestions=?";
        $delete = $dbh->prepare($sql);
        $delete->execute(array($questionid));
    }
}


?>