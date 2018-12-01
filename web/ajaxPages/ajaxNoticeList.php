<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/WebInfoRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new WebInfoRoute();
$list = $router->getNoticeList();
?>
<?foreach($list as $item){
    $madeBy = "관리자(".$item["madeName"].")";
    if($item["madeBy"]==0) $madeBy = "관리자";
    ?>
    <div class="media">
        <div class="media-body">
            <h4 class="media-heading jDetail" noticeID="<?=$item["id"]?>">
                <?=$item["title"]?>
                <span class="time">
                    <i class="fa fa-user"></i>&nbsp;<?=$madeBy?>&nbsp;&nbsp;
                    <i class="fa fa-calendar"></i> <?=$item["regDate"]?></span>
                <a href="#" class="reply jDetail" noticeID="<?=$item["id"]?>">자세히 <i class="fa fa-sign-in"></i></a>
            </h4>
            <p><?=$item["desc"]?></p>
        </div>
    </div>
<?}?>
