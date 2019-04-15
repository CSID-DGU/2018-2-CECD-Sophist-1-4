<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new GroupRoute();
$item = $router->getVote();
$attendedList = $router->getAttendedInfo($_REQUEST["id"], $item["type"]);
$typeName = "";
$selectionList = "";
$selected = -1;

if($item["type"] == "V"){
    $selectionList = $router->getCandidates($_REQUEST["id"]);
    $selected = $attendedList[0]["selected"];
}

switch ($item["type"]){
    case "V": $typeName = "투표"; break;
    case "S": $typeName = "설문"; break;
    default: $typeName = "투표/설문"; break;
}

if($item["groupID"] != 0){
    if($item["cascadeGroup"] == 1 ){
        // TODO lower group check
    }
    if(!$router->belongsToGroup(AuthUtil::getLoggedInfo()->id, $item["groupID"])){
        echo "<script>location.href='groupAccess.php?id={$item["groupID"]}';</script>";
    }
}
?>

    <script>

        $(document).ready(function(){

            $(".jRevote").click(function(){
                callJson(
                    "/eVote/shared/public/route.php?F=GroupRoute.attendSurvey",
                    {
                        voteID : "<?=$_REQUEST["id"]?>",
                        answer : $("input[name='selects']:checked").val(),
                        type : "<?=$item["type"]?>"
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            alert(data.returnMessage);
                            location.reload();
                        }else{
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                        }
                    }
                );
            });

            $(".jReselect").click(function(){
                callJson(
                    "/eVote/shared/public/route.php?F=GroupRoute.attendSurvey",
                    {
                        voteID : "<?=$_REQUEST["id"]?>",
                        answer : $("#surveyAnswer").val(),
                        type : "<?=$item["type"]?>"
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            alert(data.returnMessage);
                            location.reload();
                        }else{
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                        }
                    }
                );
            });

            $(".jBack").click(function(){
                history.back();
            });

        });
    </script>
    <body>
    <header>
        <nav id="nav" class="navbar">
            <div class="container">
                <? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/navigator.php"; ?>

                <div class="header-wrapper sm-padding bg-grey">
                    <div class="container">
                        <h2><?=$typeName?> 상세 정보</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item"><a href="room.php?type=A">투표 및 설문</a></li>
                            <li class="breadcrumb-item"><?=$typeName?> 상세 정보</li>
                        </ul>
                    </div>
                </div>

    </header>

    <!-- Blog -->
    <div id="blog" class="section">

        <!-- Container -->
        <div class="container">

            <!-- Row -->
            <div class="row">
                <div class="reply-form text-center">
                    <?
                    $madeBy = $item["madeName"];
                    $typeName = "";
                    switch ($item["type"]){
                        case "V" : $typeName = "투표"; break;
                        case "S" : $typeName = "설문"; break;
                        default : $typeName = "오류"; break;
                    }
                    if($item["madeBy"]==0) $madeBy = "관리자";
                    ?>
                    <div class="blog-comments jContainer text-left">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <?if($item["needsAuth"] == 1){?>
                                        <i class="fa fa-lock"></i>&nbsp;
                                    <?}?>
                                    [<?=$typeName?>] <?=$item["title"]?>
                                    <span class="time">
                    <?if($item["groupID"] > 0){?>
                        <i class="fa fa-users"></i>&nbsp;<?=$item["groupName"]?>&nbsp;&nbsp;
                    <?}?>
                                        <i class="fa fa-user"></i>&nbsp;<?=$madeBy?>
                </span>
                                    <a href="#" class="reply jDetail" roomId="<?=$item["id"]?>" groupId="<?=$item["groupID"]?>">자세히 <i class="fa fa-sign-in"></i></a>
                                </h4>
                                <p><?=$item["desc"]?></p>
                                <span class="small">
                <i class="fa fa-calendar"></i>
                                    <?=$item["startDate"]?>
                                    <?if($item["isEndless"] == 0){?> ~ <?=$item["endDate"]?><?}?>
            </span>
                            </div>
                            <div class="blog-tags sm-tag">
                                <?if($item["groupID"] == 0){?><a href="#"><i class="fa fa-users"></i>공개 투표</a><?}?>
                                <?if($item["groupID"] > 0){?><a href="#"><i class="fa fa-lock"></i>그룹 투표</a><?}?>
                                <?if($item["needsAuth"] == 1){?><a href="#"><i class="fa fa-users"></i>비공개 그룹</a><?}?>
                                <?if($item["cascadeGroup"] == 1){?><a href="#"><i class="fa fa-chain"></i>하위 그룹 포함</a><?}?>
                                <?if($item["isEndless"] == 1){?><a href="#"><i class="fa fa-clock-o"></i>무기한 투표</a><?}?>
                                <?if($item["changeable"] == 0){?><a href="#"><i class="fa fa-times"></i>재선택 불가</a><?}?>
                                <?if($item["changeable"] == 1){?><a href="#"><i class="fa fa-repeat"></i>재선택 가능</a><?}?>
                            </div>
                        </div>
                    </div>

                    <div class="reply-form text-center" style="margin-top: 0px;">
                        <div class="blog-comments jContainer text-left" >
                            <div class="media">
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <?=$typeName?> 참여<br/>
                                    </h4>
                                        <?if(sizeof($attendedList) <= 0){?>
                                            <?if($item["type"] == "V"){?>
                                                <? for($e = 0; $e < sizeof($selectionList); $e++){ ?>
                                                    <input type="radio" name="selects" <?=$e == 0 ? "CHECKED" : ""?> value="<?=$selectionList[$e]["id"]?>" />
                                                    <?=$selectionList[$e]["title"]?> (<?=$selectionList[$e]["desc"]?>)<br/>
                                                <?}?>
                                                <br/>
                                                <button type="button" class="btn btn-default jRevote"><i class="fa fa-edit"></i> 답변 저장</button>
                                            <?}else{?>
                                                <textarea id="surveyAnswer"></textarea>
                                                <button type="button" class="btn btn-default jReselect"><i class="fa fa-edit"></i> 답변 저장</button>
                                            <?}?>
                                            <span></span>
                                        <?}else{?>
                                        <?if($item["type"] == "V"){?>
                                                <? for($e = 0; $e < sizeof($selectionList); $e++){ ?>
                                                    <input type="radio" name="selects" <?=$item["changeable"] == 1 ? "" : "DISABLED"?> <?=$selected == $selectionList[$e]["id"] ? "CHECKED" : ""?> value="<?=$selectionList[$e]["id"]?>" />
                                                    <?=$selectionList[$e]["title"]?> (<?=$selectionList[$e]["desc"]?>)<br/>
                                                <?}?>
                                                <?if($item["changeable"] == 1){?>
                                                    <br/>
                                                    <button type="button" class="btn btn-default jRevote"><i class="fa fa-refresh"></i> 답변 수정</button>
                                                <?}?>
                                        <?}else{?>
                                                <textarea id="surveyAnswer" <?=$item["changeable"] == 1?"":"disabled"?>><?=$attendedList[0]["answer"]?></textarea>
                                                <?if($item["changeable"] == 1){?>
                                                    <button type="button" class="btn btn-default jReselect"><i class="fa fa-refresh"></i> 답변 수정</button>
                                                <?}?>
                                            <?}?>
                                        <?}?>

                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Main -->
                <main id="main" class="col-md-9">
                    <!-- reply form -->


                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-default jBack"><i class="fa fa-times"></i> 이전으로</button>
                        </div>
                    </div>
                    <!-- /reply form -->
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