<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/UserAuthRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/WebInfoRoute.php"; ?>

<?
$webInfo = new WebInfoRoute();
$list = $webInfo->getFaqList();

$router = new UserAuthRoute();
$userInfo = $router->getUser(AuthUtil::getLoggedInfo()->id);
if($userInfo["isAdmin"] != 1){
    echo "<script>alert('비정상적인 접근입니다.'); location.href='index.php';</script>";
}

?>
    <script>
        $(document).ready(function(){
            $(".jSave").click(function(e){
                e.stopPropagation();
                var id = $(this).attr("fid");
                var title = $("#tit" + id).val();
                var content = $("#con" + id).val();
                saveFaq(id, title, content);
            });

            function saveFaq(id, title, content){
                callJson(
                    "/eVote/shared/public/route.php?F=WebInfoRoute.upsertFaq",
                    {
                        id : id,
                        title : title,
                        content : content
                    }, function(data){
                        if(data.returnCode == 1){
                            alert(data.returnMessage);
                            location.reload();
                        }else{
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                        }
                    }
                );
            }

            $(".jDelete").click(function(e){
                e.stopPropagation();
                if(confirm("삭제하시겠습니까?")){
                    var id = $(this).attr("fid");
                    callJson(
                        "/eVote/shared/public/route.php?F=WebInfoRoute.deleteFaq",
                        {
                            id : id
                        }, function(data){
                            if(data.returnCode == 1){
                                alert(data.returnMessage);
                                location.reload();
                            }else{
                                alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                            }
                        }
                    );
                }
            });

            $(".jTabMenu").hide();
            $(".jTabMenu").eq(0).show();
            $(".jShowTab").eq(0).removeClass("primary-border");
            $(".jShowTab").eq(0).addClass("info-border");

            $(".jShowTab").click(function(){
                $(".jShowTab").removeClass("info-border");
                $(".jShowTab").removeClass("primary-border");
                $(".jShowTab").addClass("primary-border");
                $(this).addClass("info-border");
                var tg = $(this).attr("tg");
                $(".jTabMenu").hide();
                $("." + tg).fadeIn();
            });

        });
    </script>

    <div class="apartment_part mb-5">
        <div class="container">
            <div class="mb-5">
                <button tg="tFAQ" class="jShowTab genric-btn primary-border radius">FAQ 관리</button>
            </div>

            <div class="tFAQ jTabMenu">
                <div class="row justify-content-between align-content-center">
                    <div class="col-md-8 col-lg-8 col-sm-8">
                        <div class="section_tittle">
                            <h1 class="non-bold">FAQ 관리</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 tab-full">
                        <div class="collapsible">
                                <span style="font-size: 14px;">
                                    <input type="text" class="jTitle form-control placeholder hide-on-focus" id="tit0" style="margin: 0;" value="" placeholder="제목" />
                                    <div class="text-right">
                                        <a href="#" fid="0" class="mt-2 jSave genric-btn primary-border radius" style="margin: 0;" ><i class="fa fa-plus"></i> 추가</a>
                                    </div>
                                </span>
                        </div>
                        <div class="collapsible_content">
                            <textarea id="con0" class="faq-answer form-control placeholder hide-on-focus col-12 h-50" placeholder="답변"><?=$item["content"]?></textarea>
                        </div>

                        <? foreach ($list as $item){ ?>
                            <div class="collapsible">
                                <span style="font-size: 14px;">
                                    <input type="text" class="jTitle form-control placeholder hide-on-focus" id="tit<?=$item["id"]?>" style="margin: 0;" placeholder="제목" value="<?=$item["title"]?>" />
                                    <div class="text-right mt-2">
                                        <a href="#" fid="<?=$item["id"]?>" class="jSave genric-btn primary-border radius">저장</a>
                                        <a href="#" fid="<?=$item["id"]?>" class="jDelete genric-btn danger-border radius">삭제</a>
                                    </div>
                                </span>
                            </div>
                            <div class="collapsible_content">
                                <textarea id="con<?=$item["id"]?>" class="faq-answer form-control placeholder hide-on-focus col-12 h-50"><?=$item["content"]?></textarea>
                            </div>
                        <?}?>
                    </div>
                </div>
            </div>
        </div>

    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>