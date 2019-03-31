<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class GethHelper extends Routable {

    static function sendTransaction($from, $passPhrase, $to, $data){
        self::unlock($from, $passPhrase);

        $obj = new Routable();
        $res = $obj->postGeth(
            GETH_URL,
            "eth_sendTransaction",
            array(
                array(
                    "from" => $from,
                    "to" => $to,
                    "data" => "0x".bin2hex($data)
                )
            ),
            GETH_TXID
        );

        return json_decode($res, true);
    }

    static function getTransaction($hash){
        $obj = new Routable();
        $res = $obj->postGeth(
            GETH_URL,
            "eth_getTransactionByHash",
            array(
                $hash
            ),
            GETH_TXID
        );

        return json_decode($res, true);
    }

    static function newAccount($passPhrase){
        $obj = new Routable();
        $res = $obj->postGeth(
            GETH_URL,
            "personal_newAccount",
            array(
                $passPhrase
            ),
            GETH_TXID
        );

        $arr = json_decode($res, true);

        return $arr["result"];
    }

    static function unlock($address, $passPhrase){
        $obj = new Routable();
        $res = $obj->postGeth(
            GETH_URL,
            "personal_unlockAccount",
            array(
                $address,
                $passPhrase
            ),
            GETH_TXID
        );

        $arr = json_decode($res, true);

        return $arr["result"];
    }

    static function getTransactions($hashes){
        $arr = array();
        for($e = 0; $e < sizeof($hashes); $e++){
            $arr[$e] = GethHelper::getTransaction($hashes[$e]);
        }
        return $arr;
    }

    function writeOnBase($data){
        return GethHelper::sendTransaction(
            GETH_ETHERBASE,
            GETH_ETHERBASE_PASS,
            GETH_ETHERBASE,
            $data
        );
    }

    static function formatHistoryData($action, $id, $by, $what, $extra){
        return "HISTORY-".$action."-".$id."-".$by."-".$what."-".$extra;
    }

    static function writeByApp($action, $id, $by, $what, $extra){
        return GethHelper::writeOnBase(GethHelper::formatHistoryData($action, $id, $by, $what, $extra));
    }

    static function verifyAction($hash, $action, $id, $by, $what, $extra){
        $res = GethHelper::getTransaction($hash);
        $comp = GethHelper::formatHistoryData($action, $id, $by, $what, $extra);
        return hex2bin(substr($res["result"]["input"], 2)) == $comp;
    }

    function ver(){
        return self::verifyAction(
            "0x832ea3b2069f04d964cb18931c56400f2a97ccd6b327969ade34df4886b82077",
            "create", 5, "new", "room", "aa"
        );
    }

    function txTT(){
        return GethHelper::writeByApp("create", 5, "new", "room", "aa");
    }

}

?>
