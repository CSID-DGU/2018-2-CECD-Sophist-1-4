<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
    if($_REQUEST["id"] == ""){
        echo "<script>alert('비정상적인 접근입니다.'); history.back();</script>";
    }

    $router = new GroupRoute();
    $item = $router->getGroup();
?>
<script>

    $(document).ready(function(){
        $(".jJoin").click(function(){
            location.href = "join.php";
        });

        $(".jBack").click(function(){
            history.back();
        });

        $(".jGJoin").click(function(){
            location.href = "groupDetail.php?id=<?=$_REQUEST["id"]?>";
        });

        $(".jLog").click(function(){
            if($(".jEmailTxt").val() == "" || $(".jPasswordTxt").val() == ""){
                alert("회원 정보를 입력하세요.");
                return;
            }
            callJson(
                "/eVote/shared/public/route.php?F=UserAuthRoute.requestLogin",
                {
                    email : $(".jEmailTxt").val(),
                    pwd : $(".jPasswordTxt").val()
                }
                , function(data){
                    if(data.returnCode > 0){
                        if(data.returnCode > 1){
                            alert(data.returnMessage);
                        }else{
                            location.href = "index.php";
                        }
                    }else{
                        alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                    }
                }
            )
        });
    });
</script>

<?
$madeBy = $item["madeName"];
if($item["madeBy"]==0) $madeBy = "관리자";
?>

    <div class="apartment_part">
        <div class="container">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold">그룹 상세 정보</h1>
                        <h5 class="non-bold">본 투표/설문을 이용하기 위해 그룹 가입이 필요합니다.</h5>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
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
            </div>
        </div>
        <div class="text-center mt-3 mb-5">
            <button type="button" class="genric-btn primary-border radius jGJoin"><i class="fa fa-link"></i> 그룹 상세정보</button>
            <button type="button" class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>