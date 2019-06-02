<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class WebInfoRoute extends Routable {

    function getDashboardInfo(){
        $sql = "SELECT 
                (SELECT COUNT(*) FROM tblVoteSelection WHERE `userId`=6 AND voteID IN (SELECT `id` FROM tblRoom WHERE isDeleted=0)) AS cntVote,
                (SELECT COUNT(*) FROM tblSurvey WHERE `userId`=6 AND voteID IN (SELECT `id` FROM tblRoom WHERE isDeleted=0)) AS cntSurvey,
                (SELECT COUNT(*) FROM tblRoom WHERE madeBy=6 AND isDeleted=0 AND `type`='V') AS cntMVote,
                (SELECT COUNT(*) FROM tblRoom WHERE madeBy=6 AND isDeleted=0 AND `type`='S') AS cntMSurvey
                FROM DUAL
              ";
        return $this->getRow($sql);
    }

    function deleteFaq(){
        if(AuthUtil::getLoggedInfo()->isAdmin != 1){
            return self::response(0, "Permission Denied");
        }

        $id = $_REQUEST["id"];
        $dlt = "DELETE FROM tblFaq WHERE `id` = '{$id}'";
        $this->update($dlt);

        return self::response(1, "삭제되었습니다.");
    }

    function upsertFaq(){
        if(AuthUtil::getLoggedInfo()->isAdmin != 1){
            return self::response(0, "Permission Denied");
        }

        $id = $_REQUEST["id"];
        $title = $_REQUEST["title"];
        $content = $_REQUEST["content"];
        $ins = "
                INSERT INTO tblFaq(`id`, `title`, `content`, `regDate`)
                VALUES ('{$id}', '{$title}', '{$content}', NOW())
                ON DUPLICATE KEY UPDATE `title` = '{$title}', `content` = '{$content}';
        ";
        $this->update($ins);

        return self::response(1, "저장되었습니다.");
    }

    function getFaqList(){
        return $this->getArray("SELECT * FROM tblFaq ORDER BY `title` ASC");
    }

    function deleteNotice(){
        if(AuthUtil::getLoggedInfo()->isAdmin != 1){
            return self::response(2, "비정상적인 요청입니다.");
        }
        
        $id = $_REQUEST["id"];
        $del = "DELETE FROM tblNotice WHERE `id`='{$id}'";
        $this->update($del);

        return self::response(1, "삭제되었습니다.");
    }

    function upsertNotice(){
        if(AuthUtil::getLoggedInfo()->isAdmin != 1){
            return self::response(0, "Permission Denied");
        }

        $id = $_REQUEST["id"];
        $title = $_REQUEST["title"];
        $content = $_REQUEST["desc"];
        $madeBy = $_REQUEST["madeBy"];

        $ins = "
                INSERT INTO tblNotice(`id`, `title`, `desc`, `madeBy`, `regDate`)
                VALUES ('{$id}', '{$title}', '{$content}', '{$madeBy}', NOW())
                ON DUPLICATE KEY UPDATE `title` = '{$title}', `desc` = '{$content}';
        ";
        $this->update($ins);

        return self::response(1, "저장되었습니다.");
    }
    
    function getNoticeList(){
        $page = $_REQUEST["page"] == "" ? 1 : $_REQUEST["page"];
        $query = $_REQUEST["query"];
        $whereStmt = "1=1 ";
        if($query != ""){
            $whereStmt .= " AND `title` LIKE '%{$query}%'";
        }

        $startLimit = ($page - 1) * 5;
        $slt = "SELECT `id`, `title`, `madeBy`, `filePath`, `uptDate`, `regDate`,
                (SELECT `name` FROM tblUser WHERE `id`=`madeBy` LIMIT 1) AS madeName 
                FROM tblNotice WHERE {$whereStmt}
                ORDER BY tblNotice.`regDate` DESC LIMIT {$startLimit}, 5";
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
