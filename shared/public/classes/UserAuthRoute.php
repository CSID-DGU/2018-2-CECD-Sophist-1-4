<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class UserAuthRoute extends Routable {

    function requestLogin(){
        $email = $_REQUEST["email"];
        $pwd = $_REQUEST["pwd"];

        $val = $this->getRow("SELECT * FROM tblUser WHERE email='{$email}' AND `password`='{$pwd}' LIMIT 1");
        if($val != null){
            AuthUtil::requestLogin($val);
            return Routable::response(1, "정상적으로 로그인되었습니다.");
        }else{
            return Routable::response(2, "일치하는 회원 정보를 찾을 수 없습니다.");
        }
    }

    function requestLogout(){
        AuthUtil::requestLogout();
        return Routable::response(1, "정상적으로 로그아웃되었습니다.");
    }

}
