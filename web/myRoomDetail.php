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

        var cache_width = $('#renderPDF').width(); //Criado um cache do CSS
        var a4 = [595.28, 841.89]; // Widht e Height de uma folha a4

        $(document).ready(function(){
            var animals = ['Dog','Cat','Cow','Snake'];

            var data = {
                series: [5, 3, 4, 1]
            };

            var sum = function(a, b) { return a + b };

            new Chartist.Pie('.ct-chart', data, {
                labelInterpolationFnc: function(value, idx) {
                    var percentage = Math.round(value / data.series.reduce(sum) * 100) + '%';
                    return animals[idx] + ' (' + percentage + ')';
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
                    <div class="ct-chart h-75"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-5 mb-5">
        <?if($item["madeBy"] == AuthUtil::getLoggedInfo()->id){?><button class="genric-btn primary-border radius jModify"><i class="fa fa-edit"></i> 재설정</button><?}?>
        <button class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>