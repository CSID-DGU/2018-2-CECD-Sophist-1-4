<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?

$router = new GroupRoute();
$list = $router->getMyGroupList(AuthUtil::getLoggedInfo()->id);
$item = $router->getGroup();
$memList = $router->getGroupMemberList($_REQUEST["id"]);
$isJoined = $router->isJoined($_REQUEST["id"], AuthUtil::getLoggedInfo()->id);

if(!$isJoined){
    echo "<script>alert('비정상적인 접근입니다.'); history.back();</script>";
}
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

            $(".jDel").click(function(){
                if(confirm("해당 항목을 삭제하시겠습니까?")){
                    callJson(
                        "/eVote/shared/public/route.php?F=GroupRoute.delGroup",
                        {
                            id : "<?=$_REQUEST["id"] == "" ? "0" : $_REQUEST["id"]?>"
                        }
                        , function(data){
                            if(data.returnCode > 0){
                                alert(data.returnMessage);
                                if(data.returnCode > 1){
                                }else{
                                    location.href = "group.php";
                                }
                            }else{
                                swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                            }
                        }
                    );
                }
            });

            $(".jMem").click(function(){
                if(confirm("그룹에 가입하시겠습니까?")){
                    var auth = $("#authText").val();
                    joinGroup(auth);   
                }
            });

            $(".jMemD").click(function(){
                if(confirm("그룹을 탈퇴하시겠습니까?")){
                    unjoin();
                }
            });

            $(".jGen").click(function(){
                var title = $(".jTitle").val();
                var desc = $(".jDesc").val();
                var authCode = $(".jAuthCode").val();
                var rootId = $(".jHier option:selected").attr("rootID");
                var parentId = $(".jHier option:selected").val();
                var needsAuth = $(".jNeedsAuth").prop("checked") == true ? 1 : 0;
                var madeBy = $(".jMadeBy").val();
                var tag = tags.toString();

                if(title == ""){
                    swal("정보", "그룹명을 입력하세요.", "info");
                    return;
                }

                if(needsAuth == 1 && authCode == ""){
                    swal("정보", "비공개 그룹 인증코드를 입력하세요.", "info");
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
                            swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                        }
                    }
                );
            });

            function joinGroup(auth){
                callJson(
                    "/eVote/shared/public/route.php?F=GroupRoute.joinGroup",
                    {
                        id : "<?=$_REQUEST["id"]?>",
                        auth : auth
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            alert(data.returnMessage);
                            if(data.returnCode > 1){
                            }else{
                                location.reload();
                            }
                        }else{
                            swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                        }
                    }
                );
            }

            function unjoin(){
                callJson(
                    "/eVote/shared/public/route.php?F=GroupRoute.unjoinGroup",
                    {
                        id : "<?=$_REQUEST["id"]?>"
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            alert(data.returnMessage);
                            if(data.returnCode > 1){
                            }else{
                                location.reload();
                            }
                        }else{
                            swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                        }
                    }
                );
            }

            function refreshTag(){
                var container = $(".jTagContainer");
                var html = "";
                for(var e = 0; e < tags.length; e++){
                    html += "<a href='#' class='jRemove' tag='" + tags[e] + "'><i class='fa fa-times'></i>" + tags[e] + "</a> ";
                }
                container.html(html);
            }

            $(".jKick").click(function(e){
                if(confirm("해당 회원을 그룹에서 강퇴하시겠습니까?")){
                    var userId = $(this).attr("userId");
                    callJson(
                        "/eVote/shared/public/route.php?F=GroupRoute.kickUser",
                        {
                            userId : userId,
                            groupId : "<?=$_REQUEST["id"]?>"
                        }
                        , function(data){
                            if(data.returnCode > 0){
                                alert(data.returnMessage);
                                if(data.returnCode > 1){
                                }else{
                                    location.reload();
                                }
                            }else{
                                swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                            }
                        }
                    )
                }
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
                        <h5>총 <?=sizeof($memList)?>명의 그룹 멤버</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <?
                $cnt = 1;
                foreach ($memList as $memItem){
                    $nameRow  = mb_substr($memItem["name"],0,1).str_repeat('*',mb_strlen($memItem["name"]) - 2).mb_substr($memItem["name"],-1,1);
                    $emailRowAlter = mb_substr($memItem["email"],0,5).str_repeat('*',mb_strlen($memItem["email"]) - 5).mb_substr($memItem["email"],-1,0);
                    $emailRow = $memItem["email"];
                    if($item["madeBy"] != AuthUtil::getLoggedInfo()->id){
                        $emailRow = $emailRowAlter;
                    }
                    ?>
                <div class="col-md-12">
                    <div class="single_appartment_part">
                        <div class="single_appartment_content all">
                            <a href="#">
                                <p class="mb-0">
                                    <?=$cnt++?>.&nbsp;
                                    <i class="fa fa-user"></i>
                                &nbsp;<?=$nameRow?>(<?=$emailRow?>)</p></a>
                            <?if($item["madeBy"] == AuthUtil::getLoggedInfo()->id){?>
                            <div class="col-12 text-right">
                                <a href="mailto:<?=$memItem["email"]?>" class="genric-btn info-border radius"><i class="fa fa-envelope" ></i> 메일</a>
                                <?if($memItem["id"] != $item["madeBy"]){?>
                                <button class="genric-btn danger-border radius jKick" userId="<?=$memItem["id"]?>"><i class="fa fa-times" ></i> 강퇴</button>
                                <?}?>
                            </div>
                            <?}?>
                        </div>
                    </div>
                </div>
                <?}?>
            </div>
        </div>
        <div class="text-center mt-3 mb-5">
            <button class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>