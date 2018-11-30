<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class UserAuthRoute extends Routable {

    function requestLogin(){
        $email = $_REQUEST["email"];
        $pwd = $_REQUEST["pwd"];
    }

    function requestLogout(){
        AuthUtil::requestLogout();
        return Routable::response(1, "정상적으로 로그아웃되었습니다.");
    }

}
