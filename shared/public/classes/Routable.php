<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/Databases.php";

class Routable extends Databases {

    function test(){
        return $this->getRow("SELECT * FROM tblUser LIMIT 1");
    }

}

?>
