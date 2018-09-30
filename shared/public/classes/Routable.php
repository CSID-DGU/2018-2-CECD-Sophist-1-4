<?php
include_once "../bases/Databases.php";

class Routable extends Databases {

    function test(){
        return $this->getRow("SELECT * FROM tblUser LIMIT 1");
    }

}

?>
