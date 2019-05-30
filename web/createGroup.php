<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
}
$router = new GroupRoute();
$list = $router->getMyGroupList(AuthUtil::getLoggedInfo()->id);
?>

    <script>

        $(document).ready(function(){

            var tags = [];

            $(".jAuthCode").hide();

            $(".jNeedsAuth").change(function(){
                if($(this).prop("checked")){
                    $(".jAuthCode").fadeIn();
                }else{
                    $(".jAuthCode").fadeOut();
                }
            });

            $(".jAddTag").click(function(){
                var tag = $(".jTag").val();
                if(tag == ""){
                    return;
                }else{
                    addTag(tag);
                    $(".jTag").val("");
                }
            });

            $(document).on("click", ".jRemove", function(e){
                e.preventDefault();
                removeTag($(this).attr("tag"));
            });

            function addTag(title){
                var flag = true;
                for(var e = 0; e < tags.length; e++){
                    if(tags[e] == title) flag = false;
                }
                if(flag) tags.push(title)
                refreshTag();
            }

            function removeTag(title){
                var temp = [];
                for(var e = 0; e < tags.length; e++){
                    if(tags[e] != title) temp.push(tags[e]);
                }
                tags = temp;
                refreshTag();
            }

            $(".jBack").click(function(){
                history.back();
            });

            $(".jGen").click(function(){
                var title = $(".jTitle").val();
                var desc = $(".jDesc").val();
                var authCode = $(".jAuthCode").val();
                var rootId = 0;
                var parentId = 0;
                var needsAuth = $(".jNeedsAuth").prop("checked") == true ? 1 : 0;
                var madeBy = $(".jMadeBy").val();
                var tag = tags.toString();

                if(title == ""){
                    alert("그룹명을 입력하세요.");
                    return;
                }

                if(needsAuth == 1 && authCode == ""){
                    alert("비공개 그룹 인증코드를 입력하세요.");
                    return;
                }

                if(needsAuth == 0){
                    authCode = "";
                }

                if(!confirm("그룹 정보는 수정할 수 없습니다.\n이대로 진행하시겠습니까?")){
                    return;
                }

                callJson(
                    "/eVote/shared/public/route.php?F=GroupRoute.addGroup",
                    {
                        title : title,
                        desc : desc,
                        authCode : authCode,
                        rootId : rootId,
                        parentId : parentId,
                        needsAuth : needsAuth,
                        madeBy : madeBy,
                        tag : tag
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            alert(data.returnMessage);
                            if(data.returnCode > 1){
                            }else{
                                location.href = "groupDetail.php?id=" + data.data;
                            }
                        }else{
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                        }
                    }
                )
            });

            function refreshTag(){
                var container = $(".jTagContainer");
                var html = "";
                for(var e = 0; e < tags.length; e++){
                    html += "<a href='#' class='jRemove genric-btn primary-border radius mt-3' tag='" + tags[e] + "'><i class='fa fa-times'></i> " + tags[e] + "</a> ";
                }
                container.html(html);
            }

            $(".jRHelper").click(function(e){
                var tg = $(this).attr("target");
                $("#" + tg).prop("checked", !$("#" + tg).prop("checked"));
                if($("#" + tg).prop("checked")){
                    $(".jAuthCode").fadeIn();
                }else{
                    $(".jAuthCode").fadeOut();
                }
            });

        });
    </script>

    <section class="contact-section area-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">그룹 설정</h2>
                </div>
                <div class="col-lg-12">
                    <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input class="jMadeBy" type="hidden" value="<?=AuthUtil::getLoggedInfo()->id?>" />
                                    <input class="mt-3 form-control placeholder hide-on-focus jTitle" type="text" placeholder="제목" />
                                    <input class="mt-3 form-control placeholder hide-on-focus jDesc" type="text" placeholder="부가 설명 내용" />
                                    <input class="mt-3 form-control placeholder hide-on-focus jAuthCode" type="text" placeholder="비공개 그룹 인증 코드" />
                                </div>
                            </div>
                        </div>
                        <div target="chk1" class="switch-wrap d-flex justify-content-between genric-btn info-border radius p-3 jRHelper">
                            <p>비공개 그룹으로 설정</p>
                            <div class="confirm-switch">
                                <input type="checkbox" class="jNeedsAuth" id="chk1" />
                                <label for="chk1"></label>
                            </div>
                        </div>

                        <div class="btn-group w-100">
                            <input class="form-control placeholder hide-on-focus jTag col-10" type="text" placeholder="태그 내용"/>
                            <button class="button jAddTag col-2" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="blog-tags sm-tag jTagContainer">
                        </div>

                        <div class="form-group mt-5">
                            <button type="button" class="genric-btn primary-border radius jGen"><i class="fa fa-plus"></i> 설정하기</button>
                            <button type="button" class="genric-btn primary-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>