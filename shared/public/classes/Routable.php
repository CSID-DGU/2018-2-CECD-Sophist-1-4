<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/Databases.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/utils/AuthUtil.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/modules/email/EmailSender.php";

class Routable extends Databases {

    static function response($returnCode, $returnMessage = "", $data = ""){
        $retVal = array("returnCode" => $returnCode, "returnMessage" => $returnMessage, "data" => $data);
        return json_encode($retVal);
    }

    function test(){
        return $this->decryptAES256("r8e5JO53po+YjINxOCQWpg==");
    }

    function getRecommendation($key, $table, $col, $count = 10){
        $slt = "SELECT `{$col}` FROM `{$table}` WHERE `{$col}` LIKE '%{$key}%' ORDER BY `{$col}` DESC LIMIT {$count}";
        $arr = $this->getArray($slt);

        if(sizeof($arr) == 0) return array();

        $retVal = array();
        $cursor = 0;
        foreach ($arr as $unit){
            $retVal[$cursor++] = $unit[$col];
        }
        return $retVal;
    }

    function getData($actionUrl, $request=array()){
        $url = $actionUrl . "?" . http_build_query($request, '', '&');
        $curl_obj = curl_init();
        curl_setopt($curl_obj, CURLOPT_URL, $url);
        curl_setopt($curl_obj, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, true);
        return  (curl_exec($curl_obj));
    }

    function postData($actionUrl, $postData){
        $curl_obj = curl_init();
        curl_setopt($curl_obj, CURLOPT_URL, $actionUrl);
        curl_setopt($curl_obj, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl_obj, CURLOPT_POST, true);
        curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_obj, CURLOPT_POSTFIELDS, $postData);
        return  (curl_exec($curl_obj));
    }

    function encryptAES256($str){
        $res = openssl_encrypt($str, "AES-256-CBC", AES_KEY_256, 0, AES_KEY_256);
        return $res;
    }

    function decryptAES256($str){
        $res = openssl_decrypt($str, "AES-256-CBC", AES_KEY_256, 0, AES_KEY_256);
        return $res;
    }

    function makeFileName(){
        srand((double)microtime()*1000000);
        $Rnd = rand(1000000,2000000);
        $Temp = date("Ymdhis");
        return $Temp.$Rnd;
    }


    //TODO file upload sample source
    function upsertDoc(){
        $check = file_exists($_FILES['docFile']['tmp_name']);

        $id = $_REQUEST["id"];
        $adminId = $this->admUser->id;
        $title = $_REQUEST["title"];
        $content = $_REQUEST["content"];
        if($id == "") $id = 0;

        $fileName = $_REQUEST["fileName"];
        $filePath = $_REQUEST["filePath"];

        if($check !== false){
            $fName = $this->makeFileName() . "." . pathinfo(basename($_FILES["docFile"]["name"]),PATHINFO_EXTENSION);
            $targetDir = $this->filePath . $fName;
            $fileName = $_FILES["docFile"]["name"];
            if(move_uploaded_file($_FILES["docFile"]["tmp_name"], $targetDir)) $filePath = $fName;
            else return $this->makeResultJson(-1, "fail");
        }

        $sql = "INSERT INTO tblDocument(`id`, `adminId`, `title`, `fileName`, `filePath`, `content`, `regDate`)
                    VALUES(
                      '{$id}', 
                      '{$adminId}', 
                      '{$title}', 
                      '{$fileName}',
                      '{$filePath}',
                      '{$content}',
                      NOW()
                    )
                    ON DUPLICATE KEY UPDATE 
                      `title` = '{$title}', 
                      `adminId`='{$adminId}', 
                      `content` = '{$content}',
                      `fileName` = '{$fileName}',
                      `filePath` = '{$filePath}'
                  ";

        $this->update($sql);
        return $this->makeResultJson(1, "");
    }

}

?>
