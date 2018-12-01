<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class GroupRoute extends Routable {

    function getGroupList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $startLimit = ($page - 1) * 5;
        $slt = "SELECT *, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblGroup
                WHERE `parentId`=0 
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

    function getVoteList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $startLimit = ($page - 1) * 5;
        $slt = "SELECT *,
                (SELECT `needsAuth` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS needsAuth,
                (SELECT `title` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS groupName, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblRoom 
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

}
