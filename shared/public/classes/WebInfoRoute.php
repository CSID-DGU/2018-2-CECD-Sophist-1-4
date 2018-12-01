<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class WebInfoRoute extends Routable {

    function getFaqList(){
        return $this->getArray("SELECT * FROM tblFaq ORDER BY `title` ASC");
    }

    function getNoticeList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $startLimit = ($page - 1) * 5;
        $slt = "SELECT `id`, `title`, `madeBy`, `filePath`, `uptDate`, `regDate`,
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblNotice
                ORDER BY `regDate` DESC LIMIT {$startLimit}, 5";
        return $this->getArray($slt);
    }

    function getNotice(){
        $slt = "SELECT *,
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName
                FROM tblNotice WHERE `id`='{$_REQUEST["id"]}'";
        return $this->getRow($slt);
    }

    function updateNoticeHit(){
        $id = $_REQUEST["id"];
        $slt = "SELECT `hit` FROM tblNotice WHERE `id` = '{$id}'";
        $hitVal = $this->getValue($slt, "hit") + 1;
        $upt = "UPDATE tblNotice SET `hit` = '{$hitVal}' WHERE `id` = '{$id}'";
        $this->update($upt);
    }

}
