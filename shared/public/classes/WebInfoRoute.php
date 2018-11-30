<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class WebInfoRoute extends Routable {

    function getFaqList(){
        return $this->getArray("SELECT * FROM tblFaq ORDER BY `title` ASC");
    }

}
