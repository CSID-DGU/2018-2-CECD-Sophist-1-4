<?php

include_once "Routable.php";

class TestRoute extends Routable {
    function aa(){
        return $this->getRow("SELECT * FROM tblLang LIMIT 1");
    }
}