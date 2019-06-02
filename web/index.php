<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GethHelper.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
$router = new GroupRoute();
$list = $router->getTopVoteList();
$rawList = $router->getRawTopVoteList();
for($q = 0; $q < sizeof($list); $q++){
    $tv = $rawList[$q];
    if(!GethHelper::verifyAction($tv["thash"], $tv)){
        $list[$q]["fabricated"] = true;
    }
}
?>
<script>
    $(document).ready(function(){

        if("<?=$_REQUEST["fl"]?>"==1) showSnackBar("대시보드에서 모든 결과를 확인하세요!");

        if("<?=$_REQUEST["msg"] != ""?>"){
            alert("<?=$_REQUEST["msg"]?>");
            location.href="index.php";
        }

        $(document).on("click", ".jDetail", function(){
            var id = $(this).attr("roomId");
            var st = $(this).attr("st");
            var done = $(this).attr("done");
            var endl = $(this).attr("endl");

            if(st == "0"){
                swal("정보", "시작되지 않은 항목입니다.", "info");
                return;
            }
            if(done == "1" && endl == "0"){
                swal("정보", "마감된 항목입니다.", "info");
                return;
            }
            location.href = "roomDetail.php?id=" + id;
        });

        $(".jDashboardGo").click(function(){
            location.href="dashboard.php";
        });
        $(".jJoinGo").click(function(){
            location.href="join.php";
        });
        $(".jLoginGo").click(function(){
            location.href="login.php";
        });
    });
</script>

   <!--::banner part start::-->
   <section class="banner_part">
      <div class="container">
         <div class="row align-content-center">
            <div class="col-lg-6">
               <div class="banner_text aling-items-center">
                  <div class="banner_text_iner">
                     <h5>당신의 믿음직한 투표 파트너</h5>
                     <h2 class="non-bold"><?=$CONST_PROJECT_NAME?></h2>
                     <p>가장 든든하고 빠른 투표 서비스 <?=$CONST_PROJECT_NAME?>과 함께하세요!</p>
                      <?if(AuthUtil::isLoggedIn()){?>
                          <a href="dashboard.php" class="btn_1 banner_btn">대시보드</a>
                      <?}else{?>
                          <a href="#" class="btn_1 banner_btn jLoginGo">로그인</a>
                          <a href="#" class="btn_1 banner_btn jJoinGo">간편 회원가입</a>
                      <?}?>
                     <div class="d-none d-xl-block banner_social_icon">
                        <ul class="list-inline">
                           <li class="list-inline-item"><a href="https://www.facebook.com/pullingpolling"><span class="ti-facebook"></span>facebook</a><span
                                 class="dot"><i class="fas fa-circle"></i></span></li>
                           <li class="list-inline-item"><a href="https://twitter.com/pullingpolling1"><span class="ti-twitter-alt"></span>twitter</a><span
                                 class="dot"><i class="fas fa-circle"></i></span></li>
                           <li class="list-inline-item"><a href="https://plus.google.com/u/2/100278896118356850382?hl=ko"><span class="ti-google"></span>google+</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
       <a href="room.php?type=A" class="popup-youtube video_popup"><span class="ti-arrow-right"></span></a>

   </section>
   <!--::banner part end::-->

    <!--::apartment_part start::-->
    <div class="apartment_part">
        <div class="container">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-7 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold">Latest</h1>
                    </div>
                </div>
                <div class="col-md-4 col-lg-5 col-sm-4">
                    <div class="view_more_btn float-right d-none d-md-block">
                        <a href="room.php?type=A" class="btn_2">더보기 <span class="ti-arrow-right"></span></a>
                    </div>
                </div>
            </div>
            <div class="row">

                <?foreach($list as $item){
                    $madeBy = $item["madeName"];
                    $typeName = "";
                    switch ($item["type"]){
                        case "V" : $typeName = "투표"; break;
                        case "S" : $typeName = "설문"; break;
                        default : $typeName = "오류"; break;
                    }
                    if($item["madeBy"]==0) $madeBy = "관리자";
                    ?>
                    <div class="col-md-4 col-lg-4 col-sm-6">
                        <div class="single_appartment_part jDetail" endl="<?=$item["isEndless"]?>" done="<?=$item["done"]?>" st="<?=$item["st"]?>" roomId="<?=$item["id"]?>" groupId="<?=$item["groupID"]?>">
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
                            <div class="single_appartment_content <?=$item["fabricated"] ? "red" : ""?>">
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
                <?}?>

            </div>
        </div>
    </div>
    <!--::apartment_part end::-->

    <br/><br/><br/>
   <!--::cta_part start::-->
   <div class="cta_part">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-6">
               <div class="cta_iner">
                  <h1 class="non-bold"><?=$CONST_PROJECT_NAME?>의 최근 소식이 궁금하신가요? </h1>
                  <a href="notice.php" class="cta_btn">공지사항 보기</a>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--::cta_part end::-->

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web//inc/footer.php"; ?>