<?php
/**
 * Created by PhpStorm.
 * User: sayho
 * Date: 2019-04-15
 * Time: 18:38
 */
?>

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
            alert($(".startDate").val());


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

        $(".pickadate").pickadate({
            formatSubmit: 'yyyy-mm-dd',
            hiddenName: true
        });
        $(".pickatime").pickatime({
            formatSubmit: 'HH:i',
            hiddenName: true,
            name: "test"
        });

        $(".jEndless").change(function(){
            var status = $(this).prop("checked");
            if(status == true) $(".jDateArea").fadeOut();
            else $(".jDateArea").fadeIn();
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
<header>
    <nav id="nav" class="navbar">
        <div class="container">
            <? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/navigator.php"; ?>

            <div class="header-wrapper sm-padding bg-grey">
                <div class="container">
                    <h2>투표/설문 생성</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                        <li class="breadcrumb-item"><a href="room.php?type=A">투표/설문</a></li>
                        <li class="breadcrumb-item">투표/설문 생성</li>
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
                    <h3 class="title">투표/설문 생성 정보</h3>
                    <form>

                        <br/>
                        <input class="input jTitle" type="text" placeholder="제목"/>
                        <br/>
                        <div class="input selectpicker control-label">
                            <select class="form-control">
                                <option>투표/설문 선택</option>
                                <option>투표</option>
                                <option>설문</option>
                            </select>
                        </div>
                        <br/>
                        <textarea class="input jDesc" placeholder="투표/설문 설명"></textarea>
                        <br/>

                        <div class="input jDateArea">
                            <input class="pickadate" type="text" name="startDate" placeholder="시작일 선택" readonly/>
                            <input class="pickatime" type="text" name="startTime" placeholder="시작시간 선택" readonly/>

                            ~

                            <input class="pickadate" type="text" name="endtDate" placeholder="종료일 선택" readonly/>
                            <input class="pickatime" type="text" name="endTime" placeholder="종료시간 선택" readonly/>
                        </div>
                        <br/>

                        <div class="input form-check text-left" style="margin-bottom: 0;">
                            <input type="checkbox" class="jEndless form-check-input" id="chk1">
                            <label class="form-check-label" for="chk1">무기한 설정</label>
                        </div>
                        <br/>

                        <div class="input form-check text-left" style="margin-bottom: 0;">
                            <input type="checkbox" class="jChangeable form-check-input" id="chk2">
                            <label class="form-check-label" for="chk2">재선택 가능</label>
                        </div>


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
                            <button type="button" class="btn bg-primary jGen"><i class="fa fa-pencil"></i> 생성하기</button>
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
