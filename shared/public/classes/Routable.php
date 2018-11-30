<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/Databases.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/utils/AuthUtil.php";

class Routable extends Databases {

    static function response($returnCode, $returnMessage = "", $data = ""){
        $retVal = array("returnCode" => $returnCode, "returnMessage" => $returnMessage, "data" => $data);
        return json_encode($retVal);
    }

    function test(){
        return $this->getRow("SELECT * FROM tblUser LIMIT 1");
    }

}

?>
