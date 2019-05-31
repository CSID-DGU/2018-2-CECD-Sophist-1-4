<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
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

if($item["type"] == "V") {
    $statRes = $router->getVoteStat($_REQUEST["id"]);
    $stat = $statRes["percent"];
}else{
    $statRes = $router->getSurveyStat($_REQUEST["id"]);
}

if($item["madeBy"] != AuthUtil::getLoggedInfo()->id){
    echo "<script>alert('비정상적인 접근입니다.'); history.back();</script>";
}

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

        var cache_width = $('#renderPDF').width(); //Criado um cache do CSS
        var a4 = [595.28, 841.89]; // Widht e Height de uma folha a4

        $(document).ready(function(){
            <?if($item["type"] == "V"){?>
            var animals = [
                <?
                    foreach ($stat as $sItem){
                ?>
                    "<?=$sItem["title"]?>",
                <?
                }
                ?>
            ];

            var data = {
                series: [
                    <?
                    foreach ($stat as $sItem){
                    ?>
                    <?=$sItem["count"]?>,
                    <?
                    }
                    ?>
                ]
            };

            var sum = function(a, b) { return a + b };

            new Chartist.Pie('.ct-chart', data, {
                labelInterpolationFnc: function(value, idx) {
                    var percentage = Math.round(value / data.series.reduce(sum) * 100) + '%';
                    return animals[idx] + ' (' + percentage + ')';
                }
            });

            <?}?>

            new Chartist.Line('.ct-chart-line', {
                labels: [
                    <?
                    foreach ($statRes["date"] as $dItem => $dValue){
                    ?>
                    "<?=$dItem?>",
                    <?
                    }
                    ?>
                ],
                series: [
                    [
                        <?
                        foreach ($statRes["date"] as $dItem => $dValue){
                        ?>
                        <?=$dValue?>,
                        <?
                        }
                        ?>
                    ]
                ]
            }, {
                fullWidth: true,
                chartPadding: {
                    right: 40
                }
            });

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

            // Making PDF
//            $("#renderPDF").width((a4[0] * 1.33333) - 80).css('max-width', 'none');
//
//            html2canvas($('#renderPDF'), {
//                onrendered: function (canvas) {
//                    var img = canvas.toDataURL("image/jpeg", 1.0);
//                    //var doc = new jsPDF({ unit: 'px', format: 'a4' });//this line error
//                    var doc = new jsPDF('portrait'); // portrait / landscape
//                    doc.addImage(img, 'JPEG', 0, 0);
//                    doc.save('<?//=$item["title"]?>//_통계문서.pdf');
//                    //Retorna ao CSS normal
//                    $('#renderPDF').width(cache_width);
//                }
//            });
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

    <div class="apartment_part">
        <div class="container" id="renderPDF" style="background-color: white;">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold"><?=$typeName?> 결과 집계</h1>
                    </div>
                </div>
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
                        <div class="single_appartment_content">
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
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6">
                            <h3 class="">
                                <?=$typeName?> 참여 정보<br/>
                            </h3>
                            <p><?=$item["ques"]?></p>
                            <br/>
                    <?if($item["type"] == "V"){?>
                    <div class="ct-chart h-75"></div>
                    <?}else{?>
                        <h5 class="non-bold"><i class="fa fa-list"></i> 빈출 음절 순위</h5>
                        <?
                        $limitLoop = sizeof($statRes["words"]) > 5 ? 5 : sizeof($statRes["words"]);
                        foreach($statRes["words"] as $wKey => $wValue){
                            if($limitLoop <= 0) break;
                        ?>
                            <span class="col-12 genric-btn radius success-border mt-2"><i class="fa fa-users"></i> <?=$wValue?>회 / <?=$wKey?></span>
                    <?
                            $limitLoop--;
                        }
                    }?>
                </div>
                <div class="col-12 mt-5">
                    <span class="col-12 genric-btn radius success-border"><i class="fa fa-users"></i> 총 참여 : <?=$statRes["count"]?></span>
                </div>
                <?if($item["type"] == "V"){?>
                    <div class="col-12 mt-5 h-100">
                        <h4>일자별 참여</h4>
                        <div class="ct-chart-line h-75"></div>
                    </div>
                    <div class="col-12 mt-5 h-100">
                        <h4>선택 항목별 참여</h4>
                        <?
                        $max = 0;
                        foreach ($stat as $sItem){
                            if($max <= $sItem["count"]) $max = $sItem["count"];
                            ?>
                            <span class="mt-3 col-12 genric-btn info-border radius"><?=$sItem["count"]?> : <?=$sItem["title"]?></span>
                            <?
                        }
                        ?>
                    </div>
                    <div class="col-12 mt-5 h-100">
                        <h4>최대 득표 항목</h4>
                        <?if($max == 0){?>
                            <span class="mt-3 col-12 genric-btn danger-border radius"><i class="fa fa-times"></i> 참여 인원 없음</span>
                        <?}else{?>
                            <?
                            foreach ($stat as $sItem){
                                if($max == $sItem["count"]) {
                                    ?>
                                    <span class="mt-3 col-12 genric-btn danger-border radius"><?= $sItem["count"] ?>회
                                        &nbsp;: <?= $sItem["title"] ?></span>
                                    <?
                                }
                            }
                            ?>
                        <?}?>
                    </div>
                <?}else{?>
                    <div class="col-12 mt-5 h-100">
                        <h4>일자별 참여</h4>
                        <div class="ct-chart-line h-75"></div>
                    </div>
                <?}?>
                <div class="col-12 mt-5 h-100">
                    <h4>참여 내역</h4>
                    <?
                    foreach ($statRes["raw"] as $rItem){
                        ?>
                    <?if($item["type"] == "V"){?>
                            <span class="mt-3 col-12 genric-btn info-border radius"><?=$rItem["regDate"]?> / <?=$rItem["orderNo"] + 1?>번 선택됨</span>
                        <?}else{?>
                            <span class="mt-3 col-12 genric-btn info-border radius"><?=$rItem["regDate"]?><br/><hr style="margin:0;" /><?=$rItem["answer"]?></span>
                        <?}?>
                        <?
                    }
                    if(sizeof($statRes["raw"]) == 0){
                    ?>
                        <span class="mt-3 col-12 genric-btn danger-border radius"><i class="fa fa-times"></i> 참여 인원 없음</span>
                    <?}?>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-5 mb-5">
        <button class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>