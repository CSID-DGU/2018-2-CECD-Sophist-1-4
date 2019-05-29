<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new GroupRoute();
$list = $router->getGroupList();
?>
<?foreach($list as $item){
    $madeBy = "관리자(".$item["madeName"].")";
    if($item["madeBy"]==0) $madeBy = "관리자";
    ?>
    <div class="col-md-12 mt-2">
        <div class="single_appartment_part jDetail" groupId="<?=$item["id"]?>">
            <div class="single_appartment_content all">
                <a href="#">
                    <h5>
                        <?if($item["needsAuth"] == 1){?>
                            <i class="fa fa-lock"></i>&nbsp;
                        <?}?>&nbsp;<?=$item["title"]?></h5></a>
                <p>
                    &nbsp;<i class="fa fa-user"></i>&nbsp;<?=$madeBy?>
                </p>
                <p><?=$item["desc"]?></p>
                <p><?=$item["regDate"]?></p>
                <ul class="list-unstyled mt-0">
                    <?
                    if(strlen($item["tag"]) > 0) {
                        $tags = explode(",", $item["tag"]);
                        foreach ($tags as $tag) {
                            ?>
                            <li><a href="#"><span class="fa fa-tag"></span></a><?= $tag ?></li>
                            <?
                        }
                    }?>
                </ul>
            </div>
        </div>
    </div>
<?}?>
