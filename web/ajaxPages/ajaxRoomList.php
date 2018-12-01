<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new GroupRoute();
$list = $router->getVoteList();
?>
<?foreach($list as $item){
    $madeBy = $item["madeName"];
    $typeName = "";
    switch ($item["type"]){
        case "V" : $typeName = "투표"; break;
        case "S" : $typeName = "설문"; break;
        default : $typeName = "오류"; break;
    }
    if($item["madeBy"]==0) $madeBy = "관리자";
    ?>
    <div class="media">
        <div class="media-body">
            <h4 class="media-heading">
                <?if($item["needsAuth"] == 1){?>
                    <i class="fa fa-lock"></i>&nbsp;
                <?}?>
                [<?=$typeName?>] <?=$item["title"]?>
                <span class="time">
                    <?if($item["groupID"] > 0){?>
                        <i class="fa fa-users"></i>&nbsp;<?=$item["groupName"]?>&nbsp;&nbsp;
                    <?}?>
                    <i class="fa fa-user"></i>&nbsp;<?=$madeBy?>
                </span>
                <a href="#" class="reply">자세히 <i class="fa fa-sign-in"></i></a>
            </h4>
            <p><?=$item["desc"]?></p>
            <span class="small">
                <i class="fa fa-calendar"></i>
                <?=$item["startDate"]?>
                <?if($item["isEndless"] == 0){?> ~ <?=$item["endDate"]?><?}?>
            </span>
        </div>
        <div class="blog-tags sm-tag">
            <?if($item["groupID"] == 0){?><a href="#"><i class="fa fa-users"></i>공개 투표</a><?}?>
            <?if($item["groupID"] > 0){?><a href="#"><i class="fa fa-lock"></i>그룹 투표</a><?}?>
            <?if($item["needsAuth"] == 1){?><a href="#"><i class="fa fa-users"></i>비공개 그룹</a><?}?>
            <?if($item["cascadeGroup"] == 1){?><a href="#"><i class="fa fa-chain"></i>하위 그룹 포함</a><?}?>
            <?if($item["isEndless"] == 1){?><a href="#"><i class="fa fa-clock-o"></i>무기한 투표</a><?}?>
            <?if($item["changeable"] == 0){?><a href="#"><i class="fa fa-times"></i>재선택 불가</a><?}?>
            <?if($item["changeable"] == 1){?><a href="#"><i class="fa fa-repeat"></i>재선택 가능</a><?}?>
        </div>
    </div>
<?}?>
