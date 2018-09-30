<?php
include_once "../bases/Databases.php";

class Routable extends Databases {

    function test(){
        return $this->getRow("SELECT * FROM tblLang LIMIT 1,1");
    }

}

?>
