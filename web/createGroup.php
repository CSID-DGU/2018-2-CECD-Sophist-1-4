<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
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
                var rootId = $(".jHier option:selected").attr("rootID");
                var parentId = $(".jHier option:selected").val();
                var needsAuth = $(".jNeedsAuth").prop("checked") == true ? 1 : 0;
                var madeBy = $(".jMadeBy").val();
                var tag = tags;

                if(needsAuth == 1 && authCode == ""){
                    alert("비공개 그룹 인증코드를 입력하세요.");
                    return;
                }

                if(needsAuth == 0){
                    authCode = "";
                }

                if(title == ""
                    || $(".jNameTxt").val() == ""
                    || $(".jPasswordTxt").val() == ""
                    || $(".jSex").val() == ""){
                    alert("회원 정보를 모두 입력하세요.");
                    return;
                }

                callJson(
                    "/eVote/shared/public/route.php?F=UserAuthRoute.joinUser",
                    {
                        email : $(".jEmailTxt").val(),
                        pwd : $(".jPasswordTxt").val(),
                        phone : $(".jPhoneTxt").val(),
                        name : $(".jNameTxt").val(),
                        sex : $(".jSex").val()
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            alert(data.returnMessage);
                            if(data.returnCode > 1){
                            }else{
                                location.href = "index.php";
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
                    html += "<a href='#' class='jRemove' tag='" + tags[e] + "'><i class='fa fa-times'></i>" + tags[e] + "</a> ";
                }
                container.html(html);
            }

        });
    </script>
    <body>
    <header>
        <nav id="nav" class="navbar">
            <div class="container">
                <? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/navigator.php"; ?>

                <div class="header-wrapper sm-padding bg-grey">
                    <div class="container">
                        <h2>그룹 설정</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item"><a href="group.php">그룹</a></li>
                            <li class="breadcrumb-item">그룹 설정</li>
                        </ul>
                    </div>
                </div>

    </header>
    <!-- /Header -->

    <!-- Blog -->
    <div id="blog" class="section">
        <!-- Container -->
        <div class="container">

            <!-- Row -->
            <div class="row tiny-padding">
                <br/>
                <!-- Main -->
                <main id="main" class="col-md-12">

                    <div class="reply-form text-center">
                        <h3 class="title">그룹 생성 정보</h3>
                        <!--                        <h3 class="title">회원가입</h3>-->
                        <form>
<!--                            <label for="prd_" class="control-label">PRODUCT</label><br>-->
                            <div class="input selectpicker control-label">
                                <select id="prd_" class="form-control jHier jParentID" name="prd_">
                                    <option rootID="0" value="0">최상위 그룹으로 설정</option>
                                <?foreach($list as $item){?>
                                    <option rootID="<?=$item["rootId"]?>" value="<?=$item["id"]?>">'<?=$item["title"]?>'의 하위 그룹으로 설정</option>
                                <?}?>
                                </select>
                            </div>
                            <br/>
                            <input class="input jTitle" type="text" placeholder="그룹명" />
                            <br/>
                            <textarea class="input jDesc" placeholder="그룹 설명"></textarea>
                            <br/>
                            <div class="input form-check text-left" style="margin-bottom: 0;">
                                <input type="checkbox" class="jNeedsAuth form-check-input" id="chk1">
                                <label class="form-check-label" for="chk1">비공개 그룹으로 설정</label>
                            </div>
                            <br/>
                            <input class="input jAuthCode" type="text" placeholder="비공개 그룹 인증 코드" />
                            <input class="input jMadeBy" type="hidden" value="<?=AuthUtil::getLoggedInfo()->id?>" />
                            <br/>
                            <aside id="aside" class="input">
                                <div class="widget" style="margin-bottom: 20px;">
                                    <div class="widget-search">
                                        <input class="search-input jTag" type="text" placeholder="태그" />
                                        <button class="search-btn jAddTag" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="blog-tags sm-tag jTagContainer">
                                </div>
                            </aside>
                            <br/>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn bg-primary jGen"><i class="fa fa-pencil"></i> 그룹 생성하기</button>
                                <button type="button" class="btn btn-default jBack"><i class="fa fa-list"></i> 목록으로</button>
                            </div>
                        </form>
                    </div>
            </div>
            </main>
            <!-- /Main -->

        </div>
        <!-- /Row -->

    </div>
    <!-- /Container -->

    </div>
    <!-- /Blog -->

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>