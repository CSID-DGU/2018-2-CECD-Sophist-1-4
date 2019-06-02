<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GethHelper.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new GroupRoute();
$list = $router->getMyVoteList();
$rawList = $router->getMyRawVoteList();
for($q = 0; $q < sizeof($list); $q++){
    $tv = $rawList[$q];
    if(!GethHelper::verifyAction($tv["thash"], $tv)){
        $list[$q]["fabricated"] = true;
    }
}
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
    <div class="col-md-4 col-lg-4 col-sm-6 mt-3">
        <div class="single_appartment_part jDetail" roomId="<?=$item["id"]?>" groupId="<?=$item["groupID"]?>">
            <div class="appartment_img">
                <? if($item["type"]=="V"){ ?>
                    <img src="img/ic_vote.png" alt="">
                <?}else{?><img src="img/ic_survey.png" alt=""><?}?>
                <div class="single_appartment_text">
                    <h3 class="non-bold"><?if($item["needsAuth"] == 1){?>
                            <i class="fa fa-lock"></i>&nbsp;
                        <?}?>
                        <?=$typeName?></h3>
                    <p><span class="ti-calendar"></span>
                        <br/><?=$item["startDate"]?>
                        <?if($item["isEndless"] == 0){?><br/><?=$item["endDate"]?><?}?>
                    </p>
                </div>
            </div>
            <div class="single_appartment_content <?=$item["fabricated"] ? "red" : ""?>">
                <p>
                    <?if($item["groupID"] > 0){?><i class="fa fa-users"></i> <?=$item["groupName"]?><?}?>
                    &nbsp;<i class="fa fa-user"></i>&nbsp;<?=$madeBy?>
                </p>
                <p><?=$item["desc"]?></p>
                <a href="#">
                    <h5><?if($item["needsAuth"] == 1){?>
                            <i class="fa fa-lock"></i>&nbsp;
                        <?}?> <?=$item["title"]?></h5></a>
                <ul class="list-unstyled">
                    <?if($item["groupID"] == 0){?><li><a href="#"><span class="fa fa-users"></span></a>공개</li><?}?>
                    <?if($item["groupID"] > 0){?><li><a href="#"><span class="fa fa-lock"></span></a>그룹</li><?}?>
                    <?if($item["needsAuth"] == 1){?><li><a href="#"><span class="fa fa-users"></span></a>비공개</li><?}?>
                    <?if($item["isEndless"] == 1){?><li><a href="#"><span class="fa fa-clock"></span></a>무기한</li><?}?>
                    <?if($item["changeable"] == 0){?><li><a href="#"><span class="fa fa-check"></span></a>재선택불가</li><?}?>
                    <?if($item["changeable"] == 1){?><li><a href="#"><span class="fa fa-check"></span></a>재선택가능</li><?}?>
                </ul>
            </div>
        </div>
    </div>
<?}?>