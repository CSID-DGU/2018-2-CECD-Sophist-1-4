<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new GroupRoute();
$list = $router->getGroupList();
?>
<?foreach($list as $item){
    $madeBy = $item["madeName"];
    if($item["madeBy"]==0) $madeBy = "관리자";
    ?>
    <div class="media">
        <div class="media-body">
            <h4 class="media-heading">
                <?if($item["needsAuth"] == 1){?>
                    <i class="fa fa-lock"></i>&nbsp;
                <?}?>
                <?=$item["title"]?>
                <span class="time">
                                        <i class="fa fa-user"></i>&nbsp;<?=$madeBy?>&nbsp;&nbsp;
                                        <i class="fa fa-calendar"></i> <?=$item["regDate"]?></span>
                <a href="#" class="reply jDetail" groupId="<?=$item["id"]?>">자세히 <i class="fa fa-sign-in"></i></a>
            </h4>
            <p><?=$item["desc"]?></p>
        </div>
        <div class="blog-tags sm-tag">
            <?
            $tags = explode(",", $item["tag"]);
            foreach ($tags as $tag){
                ?>
                <a href="#"><i class="fa fa-tag"></i><?=$tag?></a>
            <?}?>
        </div>
    </div>
<?}?>
