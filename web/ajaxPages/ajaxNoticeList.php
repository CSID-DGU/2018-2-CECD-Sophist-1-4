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
    <div class="col-md-12 mt-2">
        <?if(AuthUtil::getLoggedInfo()->isAdmin == 1){?>
            <button noticeID="<?=$item["id"]?>" class="genric-btn radius info-border jModifyNotice mb-1"><i class="fa fa-edit"></i> 수정</button>
            <button noticeID="<?=$item["id"]?>" class="genric-btn radius danger-border jDeleteNotice mb-1"><i class="fa fa-times"></i> 삭제</button>
        <?}?>
        <div class="single_appartment_part jDetail" noticeID="<?=$item["id"]?>">
            <div class="single_appartment_content all">
                <p>
                    &nbsp;<i class="fa fa-user"></i>&nbsp;<?=$madeBy?>
                </p>
                <p><?=$item["regDate"]?></p>
                <a href="#">
                    <h5><?=$item["title"]?></h5></a>
            </div>
        </div>
    </div>
<?}?>