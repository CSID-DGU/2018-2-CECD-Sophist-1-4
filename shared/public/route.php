<?php
foreach (glob($_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/*.php") as $filename) {
    include_once $filename;
}

$raw = $_REQUEST[ROUTE_PARAMETER_UPPER] == "" ? $_REQUEST[ROUTE_PARAMETER] : $_REQUEST[ROUTE_PARAMETER_UPPER];

$requested = explode(".", $raw);
if(sizeof($requested) != 2) {
    echo MSG_INVALID_COMMAND;
    exit;
}

$ROUTABLE_CLS = $requested[0];
$ROUTABLE_MTD = $requested[1];

if(class_exists($ROUTABLE_CLS)){
    $tempObject = new $ROUTABLE_CLS();
}else{
    echo MSG_CLASS_NOT_EXISTS;
    exit;
}

if(method_exists($tempObject, $ROUTABLE_MTD)){
    $val = $tempObject->$ROUTABLE_MTD();
    if(is_array($val) && $val["redirect"] == true){
        echo "<script>location.href='".$val["url"]."';</script>";
        exit;
    }else{
        echo json_encode($val);
        exit;
    }
}else{
    echo MSG_METHOD_NOT_EXISTS;
    exit;
}
?>
