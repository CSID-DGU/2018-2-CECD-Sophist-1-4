<?
/**
 * Class AuthUtil
 */
class AuthUtil {

    static function requestLogin($row){
        if ($row != null) {
            $cookieStr = json_encode($row);
            $cookieStr = bin2hex($cookieStr);
            setcookie(KEY_USER_AUTH_INFO, $cookieStr, -1, "/", "");
            return true;
        } else {
            return false;
        }
    }

    static function isLoggedIn(){
        $cookieStr = $_COOKIE[KEY_USER_AUTH_INFO];
        return ($cookieStr != "") ? true : false;
    }

    static function isLoggedInViaString(){
        return self::isLoggedIn() ? "true" : "false";
    }

    static function getLoggedInfo(){
        $cookieStr = $_COOKIE[KEY_USER_AUTH_INFO];
        if (AuthUtil::isLoggedIn() == false) {
            $map = null;
        }
        else {
            $cookieStr = pack("H*", $cookieStr);
            $map = json_decode($cookieStr);
        }
        return $map;
    }

    static function requestLogout(){
        setcookie(KEY_USER_AUTH_INFO, "", time() - 3600, "/", "");
    }
}

?>