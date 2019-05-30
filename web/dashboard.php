<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/UserAuthRoute.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/WebInfoRoute.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
}

$router = new UserAuthRoute();
$userInfo = $router->getUser(AuthUtil::getLoggedInfo()->id);
$web = new WebInfoRoute();
$dashInfo = $web->getDashboardInfo();

?>
    <script>
        $(document).ready(function(){
            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/eVote/web/ajaxPages/ajaxHistoryList.php",
                    {
                        id : "<?=AuthUtil::getLoggedInfo()->id?>",
                        page : page,
                        query : "<?=$_REQUEST["query"]?>"
                    },
                    ".jContainer",
                    true,
                    function(){
                        isFinal = true;
                        currentPage--;
                        $(".jLoadMore").hide();
                    }
                );
            }

            loadMore(currentPage);

            $(".jLoadMore").click(function(e){
                e.preventDefault();
                loadMore(++currentPage);
            });

        });
    </script>

    <!--::breadcrumb part end::-->
    <section class="breadcrumb blog_bg alter">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner">
                        <div class="breadcrumb_iner_item">
                            <h2 class="non-bold">대시보드</h2>
                            <p><?=$userInfo["name"]?>님의 대시보드</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--::breadcrumb part end::-->


    <!-- ================ contact section start ================= -->
    <section class="contact-section mb-5">
        <div class="apartment_part">
            <div class="container">
                <div class="row justify-content-between align-content-center">
                    <div class="col-md-8 col-lg-7 col-sm-8">
                        <div class="section_tittle">
                            <h1 class="non-bold">통계 및 관리</h1>
                        </div>
                    </div>
                </div>
                <div class="btn-group mb-3">
                    <a href="myGroup.php" class="genric-btn info-border radius"><i class="fa fa-users"></i> 내 그룹</a>&nbsp;
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <div class="single_appartment_part">
                            <div class="appartment_img">
                                <img src="img/ic_vote.png" alt="">
                                <div class="single_appartment_text">
                                    <h3 class="non-bold"></h3>
                                    <p>
                                        <!--                                        <span class="ti-info-alt"> 부가정보</span>-->
                                    </p>
                                </div>
                            </div>
                            <div class="single_appartment_content">
                                <p>참여 투표</p>
                                <a href="#">
                                    <h5><i class="fa fa-history"></i>&nbsp;<?=$dashInfo["cntVote"]?></h5></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <div class="single_appartment_part">
                            <div class="appartment_img">
                                <img src="img/ic_survey.png" alt="">
                                <div class="single_appartment_text">
                                    <h3 class="non-bold"></h3>
                                    <p>
                                        <!--                                        <span class="ti-info-alt"> 부가정보</span>-->
                                    </p>
                                </div>
                            </div>
                            <div class="single_appartment_content">
                                <p>참여 설문</p>
                                <a href="#">
                                    <h5><i class="fa fa-history"></i>&nbsp;<?=$dashInfo["cntSurvey"]?></h5></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <div class="single_appartment_part">
                            <div class="appartment_img">
                                <img src="img/ic_info.png" alt="">
                                <div class="single_appartment_text">
                                    <h3 class="non-bold"></h3>
                                    <p>
<!--                                        <span class="ti-info-alt"> 부가정보</span>-->
                                    </p>
                                </div>
                            </div>
                            <div class="single_appartment_content">
                                <p>개설 투표/설문</p>
                                <a href="#">
                                    <h5><i class="fa fa-history"></i>&nbsp;<?=$dashInfo["cntMVote"] + $dashInfo["cntMSurvey"]?></h5></a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="btn-group mt-3">
                    <a href="myRoom.php?type=V" class="genric-btn primary-border radius"><i class="fa fa-list"></i> 투표 결과 집계</a>&nbsp;
                    <a href="myRoom.php?type=S" class="genric-btn primary-border radius"><i class="fa fa-list"></i> 설문 결과 집계</a>&nbsp;
                    <a href="#" class="genric-btn primary-border radius"><i class="fa fa-eye"></i> 그룹 통계 조회</a>&nbsp;
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-7 col-sm-8">
                    <div class="section_tittle mt-5">
                        <h1 class="non-bold" style="color:black;">활동내역</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="jContainer col-12">
                </div>
                <div class="text-center col-12 mb-5">
                    <a href="#" class="genric-btn info-border radius jLoadMore"><i class="fa fa-spinner"></i> 더보기</a>
                </div>

            </div>
        </div>
    </section>
    <!-- ================ contact section end ================= -->

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>