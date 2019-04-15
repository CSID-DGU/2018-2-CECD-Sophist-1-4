<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?

$router = new GroupRoute();
$list = $router->getMyGroupList(AuthUtil::getLoggedInfo()->id);
$item = $router->getGroup();
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
                        <h2>그룹 상세 정보</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item"><a href="group.php">그룹</a></li>
                            <li class="breadcrumb-item">그룹 상세 정보</li>
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
            <div class="row">
                <div class="reply-form text-center">
                    <?
                    $madeBy = $item["madeName"];
                    if($item["madeBy"]==0) $madeBy = "관리자";
                    ?>
                    <div class="blog-comments jContainer text-left">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <?if($item["needsAuth"] == 1){?>
                                        <i class="fa fa-lock"></i>&nbsp;
                                    <?}?>
                                    <?=$item["title"]?>
                                    <span class="time">
                                        <i class="fa fa-user"></i>&nbsp;<?=$madeBy?>&nbsp;&nbsp;
                                        <i class="fa fa-calendar"></i> <?=$item["regDate"]?></span>
                                </h4>
                                <p><?=$item["desc"]?></p>
                            </div>
                            <div class="blog-tags sm-tag">
                                <?
                                $tags = explode(",", $item["tag"]);
                                foreach ($tags as $tag){
                                    ?>
                                    <a href="#"><i class="fa fa-tag"></i><?=$tag?></a>
                                <?}?>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-default jBack"><i class="fa fa-times"></i> 이전으로</button>
                    </div>
                </div>
                <!-- /reply form -->
            </div>
            <!-- /Main -->

        </div>
        <!-- /Row -->

    </div>
    <!-- /Container -->

    </div>
    <!-- /Blog -->

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>