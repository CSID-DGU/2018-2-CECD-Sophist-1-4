<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/Routable.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new Routable();
$list = $router->getHistoryOf($_REQUEST["id"]);
?>
<?foreach($list as $item){?>
    <p><?=$item["regDate"]?></p>
    <a href="#" class="col-12 genric-btn primary-border radius mb-3" hid="<?=$item["id"]?>"><?=$item["content"]?></a>
<?}?>