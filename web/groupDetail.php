<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?

$router = new GroupRoute();
$list = $router->getMyGroupList(AuthUtil::getLoggedInfo()->id);
$item = $router->getGroup();
$isJoined = $router->isJoined($_REQUEST["id"], AuthUtil::getLoggedInfo()->id);
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
                                alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
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
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
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
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
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

            buttonLink(".jManageMember", "groupMember.php?id=<?=$_REQUEST["id"]?>");

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
                    </div>
                </div>
                <?if($item["madeBy"] == AuthUtil::getLoggedInfo()->id){?>
                <div class="col-12 text-right mb-3">
                    <button class="genric-btn info-border radius jManageMember">멤버 관리</button>
                </div>
                <?}else if($isJoined){?>
                    <div class="col-12 text-right mb-3">
                        <button class="genric-btn info-border radius jManageMember">멤버 보기</button>
                    </div>
                <?}?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="single_appartment_part jDetail" groupId="<?=$item["id"]?>">
                        <div class="single_appartment_content all">
                            <a href="#">
                                <h5>
                                    <?if($item["needsAuth"] == 1){?>
                                        <i class="fa fa-lock"></i>&nbsp;
                                    <?}?>&nbsp;<?=$item["title"]?>
                                    <?if($item["needsAuth"] == 1 && $item["madeBy"] == AuthUtil::getLoggedInfo()->id){?>(<?=$item["authCode"]?>)<?}?>
                                </h5></a>
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
                            <?if($item["madeBy"] != AuthUtil::getLoggedInfo()->id){?>
                                <?if(!$isJoined){?>
                                    <div class="col-12 mt-3">
                                    <?if($item["needsAuth"] == 1){?>
                                        <input class="form-control placeholder hide-on-focus col-12" id="authText" type="text" placeholder="가입인증코드" />
                                    <?}?>
                                        <button class="mt-3 genric-btn info-border radius jMem col-12"><i class="fa fa-edit"></i> 가입하기</button>
                                    </div>
                                <?}else{?>
                                    <button class="mt-3 genric-btn danger-border radius jMemD col-12"><i class="fa fa-edit"></i> 탈퇴하기</button>
                                <?}?>
                            <?}?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3 mb-5">
            <?if($item["madeBy"] == AuthUtil::getLoggedInfo()->id){?>
                <button class="genric-btn danger-border radius jDel"><i class="fa fa-check"></i> 삭제하기</button>
            <?}else{?>
            <?}?>
            <button class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>