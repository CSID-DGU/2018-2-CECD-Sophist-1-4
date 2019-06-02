<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/WebInfoRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new WebInfoRoute();
$list = $router->getUserList();
?>
<?foreach($list as $item){
    $madeBy = "관리자(".$item["madeName"].")";
    if($item["madeBy"]==0) $madeBy = "관리자";
    ?>
    <div class="col-md-12 mt-2">
        <div class="single_appartment_part jDetail" userID="<?=$item["id"]?>">
            <div class="single_appartment_content all">
                <p>
                    &nbsp;<i class="fa fa-user"></i>&nbsp;<?=$item["name"]?> (<?=$item["email"]?>) [<?=$item["id"]?>]
                </p>
                <p>전화번호 / <?=$item["phone"]?><br/>
                    가입 / <?=$item["regDate"]?><br/>
                    마지막 로그인 / <?=$item["accessDate"]?></p>
                <hr/>
                <div target="chk<?=$item["id"]?>" class="switch-wrap d-flex justify-content-between jSetAdmin" userID="<?=$item["id"]?>">
                    <p>관리자 설정</p>
                    <div class="confirm-switch">
                        <input type="checkbox" class="" id="chk<?=$item["id"]?>" <?=$item["isAdmin"]=="1"?"CHECKED":""?> />
                        <label for="chk<?=$item["id"]?>"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?}?>