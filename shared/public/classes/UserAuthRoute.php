<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class UserAuthRoute extends Routable {

    function requestLogin(){
        $email = $_REQUEST["email"];
        $pwd = $this->encryptAES256($_REQUEST["pwd"]);

        $val = $this->getRow("SELECT * FROM tblUser WHERE email='{$email}' AND `password`='{$pwd}' LIMIT 1");
        if($val != null){
            if($val["status"] == "2"){
                return Routable::response(3, "인증 대기중인 계정입니다.\n인증 후 이용해주세요.");
            }else{
                $firstFlag = $val["accessDate"] == "" ? 1 : 0;
                AuthUtil::requestLogin($val);
                $upt = "UPDATE tblUser SET accessDate=NOW() WHERE `id`='{$val["id"]}'";
                $this->update($upt);
                $this->addHistory(AuthUtil::getLoggedInfo()->id, "로그인하였습니다.");
                return Routable::response(1, "정상적으로 로그인되었습니다.", $firstFlag);
            }
        }else{
            return Routable::response(2, "일치하는 회원 정보를 찾을 수 없습니다.");
        }
    }

    function authMail(){
        $email = $this->decryptAES256($_REQUEST["authCode"]);
        $val = $this->getRow("SELECT * FROM tblUser WHERE email='{$email}' LIMIT 1");
        if($val != null){
            $upt = "UPDATE tblUser SET `status`=1 WHERE `id`='{$val["id"]}'";
            $this->update($upt);
            $this->addHistory($val["id"], "이메일 인증이 완료되었습니다.");
            $retVal = array(
                "redirect" => true,
                "url" => "http://".$_SERVER["HTTP_HOST"]."/eVote/web/index.php?msg=인증이%20완료되었습니다."
            );
        }else{
            $retVal = array(
                "redirect" => true,
                "url" => "http://".$_SERVER["HTTP_HOST"]."/eVote/web/index.php?msg=유효하지%20않은%20요청입니다."
            );
        }

        return $retVal;
    }

    function getUserByReq(){
        return $this->getUser($_REQUEST["id"]);
    }

    function getUser($no){
        $slt = "SELECT * FROM tblUser WHERE `id`='{$no}'";
        return $this->getRow($slt);
    }

    function joinUser(){
        $name = $_REQUEST["name"];
        $email = $_REQUEST["email"];
        $phone = $_REQUEST["phone"];
        $pwd = $this->encryptAES256($_REQUEST["pwd"]);
        $sex = $_REQUEST["sex"] == "" ? "N" : $_REQUEST["sex"];

        $val = $this->getRow("SELECT * FROM tblUser WHERE email='{$email}' LIMIT 1");
        if($val != null){
            return Routable::response(2, "이미 존재하는 이메일 계정입니다.");
        }else{
            $ins = "INSERT INTO tblUser(email, `password`, `name`, `phone`, `sex`, regDate)
                    VALUES ('{$email}', '{$pwd}', '{$name}', '{$phone}', '{$sex}', NOW())";
            $this->update($ins);
            $lsKey = $this->mysql_insert_id();
            $link = "http://".$_SERVER["HTTP_HOST"]."/eVote/shared/public/route.php?F=UserAuthRoute.authMail&authCode=".urlencode($this->encryptAES256($email));
            $sender = new EmailSender();
            $sender->sendMailTo(
                "풀링폴링 인증 메일입니다.",
                "아래 링크를 클릭하여 인증을 완료해주세요.<br/><a href='$link'>인증 링크</a><br/>본 서비스를 신청하지 않으셨다면 즉시 본 이메일로 회신바랍니다.",
                $email, $name
                );

            $this->addHistory($lsKey, $email." 이메일로 회원 가입하였습니다.");
            return Routable::response(1, "가입 처리가 완료되었습니다.\n입력하신 이메일로 인증 링크가 발송되었습니다.");
        }
    }

    function authUser(){

    }

    function requestLogout(){
        AuthUtil::requestLogout();
        return Routable::response(1, "정상적으로 로그아웃되었습니다.");
    }

}
