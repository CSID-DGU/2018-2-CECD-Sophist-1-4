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

        $(".jBack").click(function(){
            history.back();
        });

        $(".jGen").click(function(){
            var rType = $(".jTypeSelect").val(); // type
            var groupId = $(".jGroupID").val(); // groupID
            var title = $(".jTitle").val(); // title
            var desc = $(".jDesc").val(); // desc
            var ques = $(".jQues").val(); // ques
            var madeBy = $(".jMadeBy").val(); // madeBy
            var startDate = $("[name=jSD]").val() + " " + $("[name=jST]").val(); // startDate
            var endDate = $("[name=jED]").val() + " " + $("[name=jET]").val();; // endDate
            var isEndless = $("#chk1").prop("checked") ? 1 : 0; // isEndless
            var changeable = $("#chk2").prop("checked") ? 1 : 0; // changeable

            if(!verifyText(rType, "투표 혹은 설문을 선택하세요.")) return;
            if(!verifyText(title, "제목을 입력하세요.")) return;
            if(!verifyText($(".jSD").val(), "시작일자를 선택하세요.")) return;
            if(!verifyText($(".jST").val(), "시작시간을 선택하세요.")) return;
            if(!isEndless){
                if(!verifyText($(".jED").val(), "종료일자을 선택하세요.")) return;
                if(!verifyText($(".jET").val(), "종료시간을 선택하세요.")) return;
            }

            callJson(
                "/eVote/shared/public/route.php?F=GroupRoute.addRoom",
                {
                    id : "<?=$_REQUEST["id"] == "" ? "0" : $_REQUEST["id"]?>",
                    type : rType,
                    groupID : groupId,
                    title : title,
                    desc : desc,
                    ques : ques,
                    madeBy : madeBy,
                    startDate : startDate,
                    endDate : endDate,
                    isEndless : isEndless,
                    changeable : changeable,
                    tag : JSON.stringify(tags)
                }
                , function(data){
                    if(data.returnCode > 0){
                        alert(data.returnMessage);
                        if(data.returnCode > 1){
                        }else{
                            location.href = "roomDetail.php?id=" + data.data;
                        }
                    }else{
                        alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                    }
                }
            );
        });

        $(".pickadate").pickadate({
            formatSubmit: 'yyyy-mm-dd',
            hiddenName: true,
            min: new Date(),
            closeOnSelect: true
        });

        $(".pickatime").pickatime({
            formatSubmit: 'HH:i:00',
            hiddenName: true
        });

        $(".jEndless").change(function(){
            var status = $(this).prop("checked");
            if(status == true) $(".jDateArea").fadeOut();
            else $(".jDateArea").fadeIn();
        });

        $(".jTypeSelect").change(function(){
            var status = $(this).val();
            if(status == "V") $(".jTagArea").fadeIn();
            else $(".jTagArea").fadeOut();
        });

        $(".jRHelper").click(function(e){
            var tg = $(this).attr("target");
            $("#" + tg).prop("checked", !$("#" + tg).prop("checked"));
            if($("#chk1").prop("checked")){
                $(".jED").fadeOut();
                $(".jET").fadeOut();
            }else{
                $(".jED").fadeIn();
                $(".jET").fadeIn();
            }
        });

        $(".jEndless").change(function(){
            if($(this).prop("checked")){
                $(".jED").fadeOut();
                $(".jET").fadeOut();
            }else{
                $(".jED").fadeIn();
                $(".jET").fadeIn();
            }
        });

        var tags = [];
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

        function refreshTag(){
            var container = $(".jTagContainer");
            var html = "";
            for(var e = 0; e < tags.length; e++){
                html += "<a href='#' class='col-12 jRemove genric-btn primary-border radius mt-3' tag='" + tags[e] + "'><i class='fa fa-times'></i> " + (e+1) + ". " + tags[e] + "</a> ";
            }
            container.html(html);
        }

    });
</script>

<section class="contact-section area-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="contact-title">투표/설문 설정</h2>
            </div>
            <div class="col-lg-12">
                <form class="">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <select id="prd_" class="mt-3 form-control jGroupID">
                                    <option value="0">전체 대상</option>
                                    <? foreach ($list as $item){?>
                                    <option value="<?=$item["id"]?>"><?=$item["title"]?></option>
                                    <?}?>
                                </select>
                                <select id="prd_" class="mt-3 form-control jTypeSelect">
                                    <option value="">투표/설문 선택</option>
                                    <option value="V">투표</option>
                                    <option value="S">설문</option>
                                </select>
                                <input class="jMadeBy" type="hidden" value="<?=AuthUtil::getLoggedInfo()->id?>" />
                                <input class="mt-3 form-control placeholder hide-on-focus jTitle" type="text" placeholder="제목" />
                                <input class="mt-3 form-control placeholder hide-on-focus jDesc" type="text" placeholder="부가 설명 내용" />
                                <input class="mt-3 form-control placeholder hide-on-focus jQues" type="text" placeholder="질문 내용" />
                                <input class="mt-3 form-control placeholder hide-on-focus pickadate jSD" name="jSD" type="text" placeholder="시작일자" readonly />
                                <input class="mt-3 form-control placeholder hide-on-focus pickatime jST" name="jST" type="text" placeholder="시작시간" readonly />
                                <input class="mt-3 form-control placeholder hide-on-focus pickadate jED" name="jED" type="text" placeholder="종료일자" readonly />
                                <input class="mt-3 form-control placeholder hide-on-focus pickatime jET" name="jET" type="text" placeholder="종료시간" readonly />
                            </div>
                        </div>
                    </div>
                    <div target="chk1" class="switch-wrap d-flex justify-content-between genric-btn info-border radius p-3 jRHelper">
                        <p>무기한 설정</p>
                        <div class="confirm-switch">
                            <input type="checkbox" class="jEndless" id="chk1" />
                            <label for="chk1"></label>
                        </div>
                    </div>
                    <div target="chk2" class="switch-wrap d-flex justify-content-between genric-btn info-border radius p-3 jRHelper">
                        <p>재선택 가능 설정</p>
                        <div class="confirm-switch">
                            <input type="checkbox" class="jChangeable" id="chk2" />
                            <label for="chk2"></label>
                        </div>
                    </div>

                    <div class="btn-group w-100 jTagArea" style="display: none;">
                        <input class="form-control placeholder hide-on-focus jTag col-10" type="text" placeholder="선택지 내용을 입력 후 추가"/>
                        <button class="button jAddTag col-2" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="blog-tags sm-tag jTagContainer">
                    </div>

                    <div class="form-group mt-5">
                        <button type="button" class="genric-btn primary-border radius jGen"><i class="fa fa-plus"></i> 추가하기</button>
                        <button type="button" class="genric-btn primary-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>
