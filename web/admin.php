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
            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/eVote/web/ajaxPages/ajaxUserList.php",
                    {
                        page : page,
                        query : "<?=$_REQUEST["query"]?>"
                    },
                    ".jContainer",
                    true,
                    function(){
                        isFinal = true;
                        currentPage--;
                        $(".jLoadMore").hide();
                    }
                );
            }

            loadMore(currentPage);

            $(".jLoadMore").click(function(){
                loadMore(++currentPage);
            });

            $(".jSearch").click(function(){
                var searchText = encodeURI($(".jSearchTxt").val());
                location.href = "admin.php?s=t&query=" + searchText;
            });

            $(document).on("click", ".jRecList", function(){
                $(".jSearchTxt").val($(this).html());
                $(".jRec").html("");
            });

            $(".jSearchTxt").keyup(function(){
                if($(this).val().trim() == ""){
                    $(".jRec").html("");
                    return;
                }
                callJsonIgnoreError(
                    "/eVote/web/ajaxPages/ajaxRecommendation.php",
                    {
                        key : $(this).val(),
                        table : "tblUser",
                        col : "email"
                    },
                    function(data){
                        console.log(data);
                        var html = "";
                        for(var w = 0; w < data.length; w++){
                            html += "<div class='col-12 genric-btn primary-border radius recommend jRecList mt-1 recommend jRecList'>" + data[w] + "</div>";
                        }
                        $(".jRec").html(html);
                    }
                );
            });

            $(document).on("change", ".jSetAdmin", function(e){
                e.preventDefault();
                var id = $(this).attr("userID");
                var set = $("#" + "chk" + id).prop("checked") ? 1 : 0;
                callJson(
                    "/eVote/shared/public/route.php?F=WebInfoRoute.setAdmin",
                    {
                        id : id,
                        set : set
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            if(data.returnCode > 1){
                                swal("정보", data.returnMessage, "info");
                            }else{
                                showSnackBar(data.returnMessage);
                            }
                        }else{
                            swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                        }
                    }
                );
            });

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
                            swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
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
                                swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
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

            function goTab(tabName){
                $(".jShowTab").removeClass("info-border");
                $(".jShowTab").removeClass("primary-border");
                $(".jShowTab").addClass("primary-border");
                $("[tg="+tabName+"]").addClass("info-border");
                var tg = tabName;
                $(".jTabMenu").hide();
                $("." + tg).fadeIn();
            }

            if("<?=$_REQUEST["s"] != ""?>"){
                goTab("tAdmin");
            }

        });
    </script>

    <div class="apartment_part mb-5">
        <div class="container">
            <div class="mb-5">
                <button tg="tFAQ" class="jShowTab genric-btn primary-border radius">FAQ 관리</button>
                <button tg="tAdmin" class="jShowTab genric-btn primary-border radius">관리자 설정</button>
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

            <div class="tAdmin jTabMenu">
                <div class="row justify-content-between align-content-center">
                    <div class="col-md-8 col-lg-8 col-sm-8">
                        <div class="section_tittle">
                            <h1 class="non-bold">관리자 설정</h1>
                        </div>
                    </div>
                    <div class="widget-search btn-group col-12">
                        <input class="form-control placeholder hide-on-focus jSearchTxt col-10" type="text" value="<?=$_REQUEST["query"]?>" placeholder="사용자 검색 (이메일)"/>
                        <button class="button jSearch col-2" type="button"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col-12 recommend jRec">
                    </div>
                </div>
                <div class="row jContainer mt-3">
                </div>
            </div>
            <div class="text-center mt-3 mb-5">
                <button class="genric-btn info-border radius jLoadMore"><i class="fa fa-spinner"></i> 더 보기</button>
            </div>
        </div>

    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>