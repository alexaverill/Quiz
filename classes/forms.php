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
?>