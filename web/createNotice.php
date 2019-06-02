<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/UserAuthRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/WebInfoRoute.php"; ?>
<?
$webInfo = new WebInfoRoute();
$router = new UserAuthRoute();
$item = $webInfo->getNotice();
$userInfo = $router->getUser(AuthUtil::getLoggedInfo()->id);
if($userInfo["isAdmin"] != 1){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}

?>

    <script>
        $(document).ready(function(){
            buttonLink(".jBack", "notice.php");

            $(".jWrite").click(function(){
                callJson(
                    "/eVote/shared/public/route.php?F=WebInfoRoute.upsertNotice",
                    {
                        id : "<?=$_REQUEST["id"] == "" ? 0 : $_REQUEST["id"]?>",
                        madeBy : $(".jMadeBy").val(),
                        title : $(".jTitle").val(),
                        desc : $(".jDesc").val()
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            if(data.returnCode > 1){
                                swal("정보", data.returnMessage, "info");
                            }else{
                                alert(data.returnMessage);
                                location.href = "notice.php";
                            }
                        }else{
                            swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                        }
                    }
                );
            });
        });
    </script>
<?
$madeName = "관리자";
if($item["madeBy"] != 0) $madeName .= "(".$item["madeName"].")";
?>
    <!--================Blog Area =================-->
    <section class="blog_area single-post-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold" style="color: black;">공지사항 설정</h1>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input class="jMadeBy" type="hidden" value="<?=AuthUtil::getLoggedInfo()->id?>" />
                        <p>제목</p>
                        <input class="mb-3 form-control placeholder hide-on-focus jTitle" type="text" placeholder="제목" value="<?=$item["title"]?>" />
                        <p>내용</p>
                        <input class="mb-3 form-control placeholder hide-on-focus jDesc" type="text" placeholder="내용" value="<?=$item["desc"]?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <button class="genric-btn primary-border radius jWrite"><i class="fa fa-edit"></i> 설정하기</button>
            <button class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
        </div>
    </section>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>