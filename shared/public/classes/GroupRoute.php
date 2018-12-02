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

    function getMyGroupList($id){
        $slt = "SELECT * 
                FROM tblGroup 
                WHERE `id` IN 
                (SELECT groupID FROM tblGroupMember WHERE userID='{$id}') ORDER BY `title` DESC";
        return $this->getArray($slt);
    }

    function addGroup(){
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

        return Routable::response(1, "그룹 생성이 완료되었습니다.", $lastKey);
    }

}
