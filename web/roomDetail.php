<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GethHelper.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
}
?>
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
$verify = GethHelper::verifyAction($item["thash"], $router->getRawVote());

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

            if("<?=$item["st"]?>" == "0"){
                alert("시작되지 않은 항목입니다.");
                location.href = "room.php?type=A";
                return;
            }
            if("<?=$item["done"]?>" == "1" && "<?=$item["isEndless"]?>" == "0"){
                alert("마감된 항목입니다.");
                location.href = "room.php?type=A";
                return;
            }

            if("<?=$verify?>" != "1"){
                showSnackBar("본 투표/설문의 데이터가 위변조되었을 수 있습니다.");
            }

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
                            swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
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
                            swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                        }
                    }
                );
            });

            $(".jBack").click(function(){
                history.back();
            });

            $(".jRHelper").click(function(e){
                var tg = $(this).attr("target");
                $("#" + tg).prop("checked", true);
            });

            buttonLink(".jModify", "createRoom.php?id=<?=$_REQUEST["id"]?>");

        });
    </script>

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
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v3.3&appId=2224111927873126&autoLogAppEvents=1"></script>

    <div class="apartment_part">
        <div class="container">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold"><?=$typeName?> 상세 정보</h1>
                    </div>
                </div>
<!--                <div class="fb-share-button col-4 text-right" data-href="http://picklecode.co.kr/eVote/web/roomDetail.php?id=10" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fpicklecode.co.kr%2FeVote%2Fweb%2FroomDetail.php%3Fid%3D--><?//=$_REQUEST["id"]?><!--&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">공유하기</a></div>-->
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-6">
                    <div class="single_appartment_part jDetail" roomId="<?=$item["id"]?>" groupId="<?=$item["groupID"]?>">
                        <div class="appartment_img">
                            <? if($item["type"]=="V"){ ?>
                                <img src="img/ic_vote.png" alt="">
                            <?}else{?><img src="img/ic_survey.png" alt=""><?}?>
                            <div class="single_appartment_text">
                                <h3 class="non-bold"><?if($item["needsAuth"] == 1){?>
                                        <i class="fa fa-lock"></i>&nbsp;
                                    <?}?>
                                    <?=$typeName?></h3>
                                <p><span class="ti-calendar"></span>
                                    <br/><?=$item["startDate"]?>
                                    <?if($item["isEndless"] == 0){?><br/><?=$item["endDate"]?><?}?>
                                </p>
                            </div>
                        </div>
                        <div class="single_appartment_content <?=$verify ? "" : "red"?>">
                            <p>
                                <?if($item["groupID"] > 0){?><i class="fa fa-users"></i> <?=$item["groupName"]?><?}?>
                                &nbsp;<i class="fa fa-user"></i>&nbsp;<?=$madeBy?>
                            </p>
                            <p><?=$item["desc"]?></p>
                            <a href="#">
                                <h5><?if($item["needsAuth"] == 1){?>
                                        <i class="fa fa-lock"></i>&nbsp;
                                    <?}?> <?=$item["title"]?></h5></a>
                            <ul class="list-unstyled">
                                <?if($item["groupID"] == 0){?><li><a href="#"><span class="fa fa-users"></span></a>공개</li><?}?>
                                <?if($item["groupID"] > 0){?><li><a href="#"><span class="fa fa-lock"></span></a>그룹</li><?}?>
                                <?if($item["needsAuth"] == 1){?><li><a href="#"><span class="fa fa-users"></span></a>비공개</li><?}?>
                                <?if($item["isEndless"] == 1){?><li><a href="#"><span class="fa fa-clock"></span></a>무기한</li><?}?>
                                <?if($item["changeable"] == 0){?><li><a href="#"><span class="fa fa-check"></span></a>재선택불가</li><?}?>
                                <?if($item["changeable"] == 1){?><li><a href="#"><span class="fa fa-check"></span></a>재선택가능</li><?}?>
                            </ul>
                            <?if($verify == 0){?>
                            <p>※ 데이터 위변조 가능성 있음</p>
                            <?}?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6">
                            <h3 class="">
                                <?=$typeName?> 참여 정보<br/>
                            </h3>
                            <p><?=$item["ques"]?></p>
                            <br/>
                            <?if(sizeof($attendedList) <= 0){?>
                                <?if($item["type"] == "V"){?>
                                    <? for($e = 0; $e < sizeof($selectionList); $e++){ ?>
                                        <div target="voting-radio-<?=$e?>" class="jRHelper switch-wrap d-flex justify-content-between genric-btn info-border radius p-3">
                                            <p><?=$selectionList[$e]["title"]?></p>
                                            <div class="primary-radio">
                                                <input type="radio" name="selects" id="voting-radio-<?=$e?>" <?=$e == 0 ? "CHECKED" : ""?> value="<?=$selectionList[$e]["id"]?>" />
                                                <label for="voting-radio-<?=$e?>"></label>
                                            </div>
                                        </div>
                                    <?}?>
                                    <br/>
                                    <button type="button" class="col-12 genric-btn primary-border radius jRevote"><i class="fa fa-edit"></i> 답변 저장</button>
                                <?}else{?>
                                    <textarea class="mb-2 form-control placeholder hide-on-focus h-50" id="surveyAnswer" placeholder="답변 내용"></textarea>
                                    <button type="button" class="col-12 genric-btn primary-border radius jReselect"><i class="fa fa-edit"></i> 답변 저장</button>
                                <?}?>
                                <span></span>
                            <?}else{?>
                                <?if($item["type"] == "V"){?>
                                    <? for($e = 0; $e < sizeof($selectionList); $e++){ ?>
                                        <div target="voting-radio-<?=$e?>" class="jRHelper switch-wrap d-flex justify-content-between genric-btn info-border radius p-3">
                                            <p><?=$selectionList[$e]["title"]?></p>
                                            <div class="primary-radio">
                                                <input type="radio" name="selects" id="voting-radio-<?=$e?>" <?=$item["changeable"] == 1 ? "" : "DISABLED"?> <?=$selected == $selectionList[$e]["id"] ? "CHECKED" : ""?> value="<?=$selectionList[$e]["id"]?>" />
                                                <label for="voting-radio-<?=$e?>"></label>
                                            </div>
                                        </div>
                                    <?}?>
                                    <?if($item["changeable"] == 1){?>
                                        <br/>
                                        <button type="button" class="col-12 genric-btn primary-border radius jRevote"><i class="fa fa-refresh"></i> 답변 수정</button>
                                    <?}?>
                                <?}else{?>
                                    <textarea class="mb-2 form-control placeholder hide-on-focus col-12 h-50" id="surveyAnswer" placeholder="답변 내용" <?=$item["changeable"] == 1?"":"disabled"?>><?=$attendedList[0]["answer"]?></textarea>
                                    <?if($item["changeable"] == 1){?>
                                        <button type="button" class="col-12 genric-btn primary-border radius jReselect"><i class="fa fa-refresh"></i> 답변 수정</button>
                                    <?}?>
                                <?}?>
                            <?}?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-5 mb-5">
        <?if($item["madeBy"] == AuthUtil::getLoggedInfo()->id){?><button class="genric-btn primary-border radius jModify"><i class="fa fa-edit"></i> 조회 및 관리</button><?}?>
        <button class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>