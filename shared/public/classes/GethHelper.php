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

}

?>
