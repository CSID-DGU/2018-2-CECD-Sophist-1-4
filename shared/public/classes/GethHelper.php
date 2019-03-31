<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php";

class GethHelper extends Routable {

    static function sendTransaction($from, $to, $data){
        $obj = new Routable();
        $res = $obj->postGeth(
            GETH_URL,
            "eth_sendTransaction",
            array(
                array(
                    "from" => $from,
                    "to" => $to,
                    "gasPrice" => "0x10",
                    "value" => "0x10",
                    "data" => "0x".bin2hex($data)
                )
            ),
            GETH_TXID
        );

        return $res;
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

        return $res;
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

    function accTest(){
        return GethHelper::newAccount("test");
    }

    function txTest(){
        return GethHelper::sendTransaction(
            "0xb7795b1f3648475b4749f5a659617340e99012a6",
            "0xb7795b1f3648475b4749f5a659617340e99012a6",
            "txTest"
            );
    }

    function txGetTest(){
        return GethHelper::getTransaction("0xa6daa15dd52d47ef8ef33902d620e2ead3ccb4cca467e4038184c2c83038b751");
    }

}

?>
