<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class GroupRoute extends Routable {

    function getVoteStat($id){
        $sql = "SELECT *,
                DATE(regDate) AS rDate,
                (SELECT `orderNo` FROM tblVoteCand WHERE tblVoteCand.`id`=selected) AS orderNo,
                (SELECT `title` FROM tblVoteCand WHERE tblVoteCand.`id`=selected) AS title
                 FROM tblVoteSelection WHERE voteID = '{$id}' AND applied=1 ORDER BY regDate ASC";
        $arr = $this->getArray($sql);

        $dateArr = array();
        $final = array();
        $res = array();
        for($e = 0; $e < sizeof($arr); $e++){
            $item = $arr[$e];
            $res[$item["orderNo"]]["title"] = $item["title"];
            if($res[$item["orderNo"]]["count"] == "") $res[$item["orderNo"]]["count"] = 0;
            $res[$item["orderNo"]]["count"] = $res[$item["orderNo"]]["count"] + 1;
            if($dateArr[$item["rDate"]] == "") $dateArr[$item["rDate"]] = 0;
            $dateArr[$item["rDate"]]++;
        }

        $final["date"] = $dateArr;
        $final["percent"] = $res;
        $final["count"] = sizeof($arr);
        $final["raw"] = $arr;

        return $final;
    }

    function getKeywordStat($sentencesArray){
        $retVal = array();
        $tempSentence = array();
        foreach ($sentencesArray as $st) {
            preg_match_all("|(?<hangul>[가-힣]+)|u", $st, $tempSentence);
            foreach ($tempSentence["hangul"] as $word){
                if($retVal[$word] == "") $retVal[$word] = 0;
                $retVal[$word]++;
            }
        }

        ksort($retVal);
        arsort($retVal);

        return $retVal;
    }

    function getSurveyStat($id){
        $sql = "SELECT *,
                DATE(regDate) AS rDate
                 FROM tblSurvey WHERE voteID = '{$id}' ORDER BY regDate ASC";
        $arr = $this->getArray($sql);

        $sentences = array();
        $dateArr = array();
        $final = array();
        for($e = 0; $e < sizeof($arr); $e++){
            $item = $arr[$e];
            $sentences[$e] = $item["answer"];
            if($dateArr[$item["rDate"]] == "") $dateArr[$item["rDate"]] = 0;
            $dateArr[$item["rDate"]]++;
        }

        $final["date"] = $dateArr;
        $final["count"] = sizeof($arr);
        $final["raw"] = $arr;
        $final["words"] = $this->getKeywordStat($sentences);

        return $final;
    }

    function isJoined($groupID, $userID){
        $slt = "SELECT COUNT(*) AS rn FROM tblGroupMember WHERE groupId='{$groupID}' AND userId='{$userID}'";
        return $this->getValue($slt, "rn") > 0;
    }

    function getGroupList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "isDeleted=0 AND 1=1 AND `parentId`=0 ";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 6;
        $slt = "SELECT *, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblGroup
                WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 6";
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

    function getGroupMemberList($groupId){
        $sql = "SELECT `id`, `name`, `email` FROM tblUser WHERE `id` IN (SELECT userId FROM tblGroupMember WHERE groupId = '{$groupId}') ORDER BY `name`";
        return $this->getArray($sql);
    }

    function getTopVoteList($count = 3){
        $slt = "SELECT *, NOW() > endDate AS done, NOW() >= startDate AS st,
                (SELECT `needsAuth` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS needsAuth,
                (SELECT `title` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS groupName, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblRoom WHERE isDeleted=0
                ORDER BY `regDate` DESC LIMIT {$count}";
        return $this->getArray($slt);
    }

    function getVoteList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $type = $_REQUEST["type"] == "" ? "A" : $_REQUEST["type"];
        $query = $_REQUEST["query"];

        $whereStmt = "isDeleted=0 AND 1=1 ";
        if($type != "A") $whereStmt .= "AND `type` = '{$type}'";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 6;
        $slt = "SELECT *, NOW() > endDate AS done, NOW() >= startDate AS st,
                (SELECT `needsAuth` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS needsAuth,
                (SELECT `title` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS groupName, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblRoom WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 6";
        return $this->getArray($slt);
    }

    function getMyVoteList(){
        $id = AuthUtil::getLoggedInfo()->id;
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $type = $_REQUEST["type"] == "" ? "A" : $_REQUEST["type"];
        $query = $_REQUEST["query"];

        $whereStmt = "madeBy='{$id}' AND isDeleted=0 AND 1=1 ";
        if($type != "A") $whereStmt .= "AND `type` = '{$type}'";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 6;
        $slt = "SELECT *,
                (SELECT `needsAuth` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS needsAuth,
                (SELECT `title` FROM tblGroup WHERE `id`=`groupID` LIMIT 1) AS groupName, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblRoom WHERE {$whereStmt}
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 6";
        return $this->getArray($slt);
    }

    function getVote(){
        $id = $_REQUEST["id"];
        $slt = "SELECT *, NOW() > endDate AS done, NOW() >= startDate AS st,
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
        
        if($type == "V"){
            $this->addHistory(AuthUtil::getLoggedInfo()->id, $voteID."번 투표에 답변하였습니다.");
        }else{
            $this->addHistory(AuthUtil::getLoggedInfo()->id, $voteID."번 설문에 답변하였습니다.");
        }
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

    function getMyGroupList(){
        $id = AuthUtil::getLoggedInfo()->id;
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "isDeleted=0 AND 1=1 AND `parentId`=0 AND `id` IN 
                (SELECT groupID FROM tblGroupMember WHERE userID='{$id}') ";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 6;
        $slt = "SELECT *, 
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblGroup
                WHERE {$whereStmt}
                ORDER BY `title` ASC LIMIT {$startLimit}, 6";
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
        if(trim($endDate) == "") $endDate = $startDate;
        $madeBy = $_REQUEST["madeBy"] == "" ? "0" : $_REQUEST["madeBy"];
        $isEndless = $_REQUEST["isEndless"];
        $changeable = $_REQUEST["changeable"];

        $ins = "INSERT INTO `eVoteDGU`.`tblRoom` 
                (`id`, `groupID`, `type`, `title`, `ques`, `desc`, `madeBy`, `startDate`, `endDate`, `isEndless`, `changeable`, `regDate`)
                VALUES
                ('{$id}', '{$groupID}', '{$type}', '{$title}', '{$ques}', '{$desc}', '{$madeBy}', '{$startDate}', '{$endDate}', '{$isEndless}', '{$changeable}', NOW())
                ON DUPLICATE KEY UPDATE 
                `groupID` = '{$groupID}', 
                `type` = '{$type}', 
                `title` = '{$title}', 
                `ques` = '{$ques}', 
                `desc` = '{$desc}', 
                `madeBy` = '{$madeBy}', 
                `startDate` = '{$startDate}', 
                `endDate` = '{$endDate}', 
                `isEndless` = '{$isEndless}', 
                `changeable` = '{$changeable}', 
                `regDate` = NOW()
              ";
        $this->update($ins);
        $lastKey = $this->mysql_insert_id();

        $del = "DELETE FROM tblVoteCand WHERE voteID = '{$id}'";
        $this->update($del);

        if($id != 0) {
            $lastKey = $id;
        }

        if($type == "V") {
            $candList = json_decode($_REQUEST["tag"]);
            $candOrder = 0;
            foreach ($candList as $candItem) {
                $candIns = "INSERT INTO 
                     tblVoteCand(`voteID`, `orderNo`, `title`, `regDate`)
                     VALUES('{$lastKey}', '{$candOrder}', '{$candItem}', NOW())";
                $this->update($candIns);
                $candOrder++;
            }
        }

        if($id != 0) $this->addHistory(AuthUtil::getLoggedInfo()->id, $lastKey."번 투표/설문을 수정하였습니다.");
        else $this->addHistory(AuthUtil::getLoggedInfo()->id, $lastKey."번 투표/설문을 설정하였습니다.");
        return Routable::response(1, "투표/설문 설정이 완료되었습니다.", $lastKey);
    }

    function delRoom(){
        $id = $_REQUEST["id"];
        $upt = "UPDATE tblRoom SET isDeleted=1 WHERE `id`='{$id}'";
        $this->update($upt);
        $this->addHistory(AuthUtil::getLoggedInfo()->id, $id."번 투표/설문을 삭제하였습니다.");
        return Routable::response(1, "삭제되었습니다.");
    }

    function delGroup(){
        $id = $_REQUEST["id"];

        $slt = "SELECT COUNT(*) AS rn FROM tblRoom WHERE groupID='{$id}' AND isDeleted=0";
        $cnt = $this->getValue($slt, "rn");

        if($cnt > 0){
            return Routable::response(2, "그룹 내 투표/설문이 존재합니다.");
        }else{
            $upt = "UPDATE tblGroup SET isDeleted=1 WHERE `id`='{$id}'";
            $this->update($upt);

            $this->addHistory(AuthUtil::getLoggedInfo()->id, $id."번 그룹을 삭제하였습니다.");
            return Routable::response(1, "삭제되었습니다.");
        }
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
            return Routable::response(2, "동일한 그룹명이 존재합니다.");
        }

        $ins = "INSERT INTO tblGroup(`title`, `desc`, `authCode`, `rootId`, `parentId`, `needsAuth`, `madeBy`, `tag`, `regDate`)
                VALUES('{$title}', '{$desc}', '$authCode', '{$rootId}', '{$parentId}', '{$needsAuth}', '{$madeBy}', '{$tag}', NOW())";
        $this->update($ins);

        $lastKey = $this->mysql_insert_id();

        if($rootId == 0){
            $upt = "UPDATE tblGroup SET `rootId` = '{$lastKey}' WHERE `id` = '{$lastKey}'";
            $this->update($upt);
        }

        $ins = "INSERT INTO tblGroupMember(`groupId`, `userId`, `regDate`) VALUES('{$lastKey}', '{$madeBy}', NOW())";
        $this->update($ins);

        $this->addHistory(AuthUtil::getLoggedInfo()->id, $title." 그룹을 설정하였습니다.");

        return Routable::response(1, "그룹 설정이 완료되었습니다.", $lastKey);
    }

    function unjoinGroup(){
        $groupID = $_REQUEST["id"];
        $userID = AuthUtil::getLoggedInfo()->id;
        $del = "DELETE FROM tblGroupMember WHERE groupId='{$groupID}' AND userId='{$userID}'";
        $this->update($del);
        $this->addHistory(AuthUtil::getLoggedInfo()->id, $groupID."번 그룹을 탈퇴하였습니다.");
        return Routable::response(1, "탈퇴하였습니다.");
    }

    function kickUser(){
        $groupID = $_REQUEST["groupId"];
        $userID = $_REQUEST["userId"];
        $del = "DELETE FROM tblGroupMember WHERE groupId='{$groupID}' AND userId='{$userID}'";
        $this->update($del);
        $this->addHistory(AuthUtil::getLoggedInfo()->id, $groupID."번 그룹에서 ".$userID."번 회원을 강퇴하였습니다.");
        return Routable::response(1, "강퇴하였습니다.");
    }

    function joinGroup(){
        $auth = $_REQUEST["auth"];
        $groupID = $_REQUEST["id"];
        $userID = AuthUtil::getLoggedInfo()->id;
        $slt = "SELECT * FROM tblGroup WHERE `id`='{$groupID}'";
        $row = $this->getRow($slt);
        
        if($row["needsAuth"] == 1 && $row["authCode"] != $auth){
            return Routable::response(2, "인증 코드가 일치하지 않습니다.");
        }else{
            $ins = "INSERT INTO tblGroupMember(`groupId`, `userId`, `regDate`) VALUES('{$groupID}', '{$userID}', NOW())";
            $this->update($ins);
            $this->addHistory(AuthUtil::getLoggedInfo()->id, $groupID."번 그룹에 가입하였습니다.");
            return Routable::response(1, "가입되었습니다.");
        }
    }

}
