<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class GroupRoute extends Routable {

    function getGroupList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "1=1 AND `parentId`=0 ";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 5;
        $slt = "SELECT *, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblGroup
                WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

    function belongsToGroup($user, $group){
        $slt = "SELECT COUNT(*) AS rn FROM tblGroupMember WHERE groupId='{$group}' AND userId='{$user}'";
        $rn = $this->getValue($slt, "rn");
        return ($rn > 0);
    }

    function getGroup(){
        $slt = "SELECT *,
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName
                FROM tblGroup WHERE `id` = '{$_REQUEST["id"]}'";
        return $this->getRow($slt);
    }

    function getTopVoteList($count = 3){
        $slt = "SELECT *,
                (SELECT `needsAuth` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS needsAuth,
                (SELECT `title` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS groupName, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblRoom
                ORDER BY `regDate` DESC LIMIT {$count}";
        return $this->getArray($slt);
    }

    function getVoteList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $type = $_REQUEST["type"] == "" ? "A" : $_REQUEST["type"];
        $query = $_REQUEST["query"];

        $whereStmt = "1=1 ";
        if($type != "A") $whereStmt .= "AND `type` = '{$type}'";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 5;
        $slt = "SELECT *,
                (SELECT `needsAuth` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS needsAuth,
                (SELECT `title` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS groupName, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblRoom WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

    function getVote(){
        $id = $_REQUEST["id"];
        $slt = "SELECT *,
                (SELECT `needsAuth` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS needsAuth,
                (SELECT `title` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS groupName, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblRoom WHERE `id`='{$id}'";
        return $this->getRow($slt);
    }

    function attendSurvey(){
        $voteID = $_REQUEST["voteID"];
        $userID = AuthUtil::getLoggedInfo()->id;
        $answer = $_REQUEST["answer"];
        $type = $_REQUEST["type"];

        if($type == "V"){
            $del = "DELETE FROM tblVoteSelection WHERE voteID='{$voteID}' AND userID='{$userID}'";
            $sql = "INSERT INTO tblVoteSelection(voteID, userID, selected, regDate) VALUES ('{$voteID}', '{$userID}', '{$answer}', NOW())";
        }else{
            $del = "DELETE FROM tblSurvey WHERE voteID='{$voteID}' AND userID='{$userID}'";
            $sql = "INSERT INTO tblSurvey(voteID, userID, answer, regDate) VALUES ('{$voteID}', '{$userID}', '{$answer}', NOW());";
        }

        $this->update($del);
        $this->update($sql);
        return Routable::response(1, "저장되었습니다.");
    }

    function getCandidates($voteID){
        $sql = "SELECT * FROM tblVoteCand WHERE `voteID`='{$voteID}' ORDER BY orderNo";
        return $this->getArray($sql);
    }

    function getAttendedInfo($roomId, $type){
        $user = AuthUtil::getLoggedInfo()->id;
        $sql = "";
        if($type == 'V'){
            $sql = "SELECT * FROM tblVoteSelection WHERE `voteId`='{$roomId}' AND `userId`='{$user}'";
        }else{
            $sql = "SELECT * FROM tblSurvey WHERE `voteId`='{$roomId}' AND `userId`='{$user}'";
        }
        return $this->getArray($sql);
    }

    function getMyGroupList($id){
        $slt = "SELECT * 
                FROM tblGroup 
                WHERE `id` IN 
                (SELECT groupID FROM tblGroupMember WHERE userID='{$id}') ORDER BY `title` DESC";
        return $this->getArray($slt);
    }

    function addRoom(){
        $id = $_REQUEST["id"];
        $title = $_REQUEST["title"];
        $desc = $_REQUEST["desc"];
        $ques = $_REQUEST["ques"];
        $type = $_REQUEST["type"];
        $groupID = $_REQUEST["groupID"];
        $startDate = $_REQUEST["startDate"];
        $endDate = $_REQUEST["endDate"];
        $madeBy = $_REQUEST["madeBy"] == "" ? "0" : $_REQUEST["madeBy"];
        $isEndless = $_REQUEST["isEndless"];
        $changeable = $_REQUEST["changeable"];

        $ins = "INSERT INTO `eVoteDGU`.`tblRoom` 
                (`groupID`, `type`, `title`, `ques`, `desc`, `madeBy`, `startDate`, `endDate`, `isEndless`, `changeable`, `regDate`)
                VALUES
                ('{$groupID}', '{$type}', '{$title}', '{$ques}', '{$desc}', '{$madeBy}', '{$startDate}', '{$endDate}', '{$isEndless}', '{$changeable}', NOW());
";
        $this->update($ins);

        $del = "DELETE FROM tblVoteCand WHERE voteID = '{$id}'";
        $this->update($del);

        $lastKey = $this->mysql_insert_id();
        if($id != 0) $lastKey = $id;

        if($type == "V") {
            $candList = json_decode($_REQUEST["tag"]);
            $candOrder = 0;
            foreach ($candList as $candItem) {
                $candIns = "INSERT INTO 
                     tblVoteCand(`voteID`, `orderNo`, `title`, `regDate`)
                     VALUES('{$lastKey}', '{$candOrder}', '{$candItem}', NOW())";
                $this->update($candIns);
            }
        }

        return Routable::response(1, "투표/설문 설정이 완료되었습니다.", $lastKey);
    }
    
    function addGroup(){
        $id = $_REQUEST["id"];
        $title = $_REQUEST["title"];
        $desc = $_REQUEST["desc"];
        $authCode = $_REQUEST["authCode"];
        $rootId = $_REQUEST["rootId"];
        $parentId = $_REQUEST["parentId"];
        $needsAuth = $_REQUEST["needsAuth"];
        $madeBy = $_REQUEST["madeBy"] == "" ? "0" : $_REQUEST["madeBy"];
        $tag = $_REQUEST["tag"];

        $slt = "SELECT COUNT(*) AS rn FROM tblGroup WHERE `title` = '{$title}' AND parentId='{$parentId}'";
        $sameTitle = $this->getValue($slt, "rn");
        if($sameTitle > 0){
            return Routable::response(2, "동일한 그룹명이 동일 계층에 존재합니다.");
        }

        $ins = "INSERT INTO tblGroup(`title`, `desc`, `authCode`, `rootId`, `parentId`, `needsAuth`, `madeBy`, `tag`, `regDate`)
                VALUES('{$title}', '{$desc}', '$authCode', '{$rootId}', '{$parentId}', '{$needsAuth}', '{$madeBy}', '{$tag}', NOW())";
        $this->update($ins);

        $lastKey = $this->mysql_insert_id();

        if($rootId == 0){
            $upt = "UPDATE tblGroup SET `rootId` = '{$lastKey}' WHERE `id` = '{$lastKey}'";
            $this->update($upt);
        }

        return Routable::response(1, "그룹 설정이 완료되었습니다.", $lastKey);
    }

}
