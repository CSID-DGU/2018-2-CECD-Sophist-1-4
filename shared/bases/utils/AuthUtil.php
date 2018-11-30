<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/Databases.php"; ?>
<?php

class AuthUtil {

    static function doWebLogin($row){
        if ($row != null) {
            $cookieStr = json_encode($row);
            $cookieStr = bin2hex($cookieStr); // 16진수로 암호화
            setcookie("webUserMap", $cookieStr, -1, "/", "");
            return true;
        } else {
            return false;
        }
    }

    // 로그인 유무
    static function isWebLogin(){
        $cookieStr = $_COOKIE["webUserMap"];
        return ($cookieStr != "") ? true : false;
    }

    static function getWebUser(){
        $cookieStr = $_COOKIE["webUserMap"];
        if (AuthUtil::isWebLogin() == false) {
            $map = null;
        }
        else {
            $cookieStr = pack("H*", $cookieStr);
            $map = json_decode($cookieStr);
        }
        return $map;
    }

    static function doWebLogout(){
        setcookie("webUserMap", "", time() - 3600, "/", "");
    }
}

?>