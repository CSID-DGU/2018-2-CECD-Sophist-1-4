<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class GroupRoute extends Routable {

    function getGroupList(){
        $slt = "SELECT *, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblGroup ORDER BY `regDate` DESC";
        return $this->getArray($slt);
    }

}
