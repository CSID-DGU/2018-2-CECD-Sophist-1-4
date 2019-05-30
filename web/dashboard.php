<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/UserAuthRoute.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
}

$router = new UserAuthRoute();
$userInfo = $router->getUser(AuthUtil::getLoggedInfo()->id);

?>
    <script>
        $(document).ready(function(){

        });
    </script>

    <section class="contact-section mb-5">
        <div class="apartment_part">
            <div class="container">
                <div class="row justify-content-between align-content-center">
                    <div class="col-12">
                        <div class="section_tittle">
                            <h1 class="non-bold">대시보드</h1>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <a href="dashboard.php" class="genric-btn info-border radius"><i class="fa fa-list"></i> 대시보드</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">

                <div class="col-12">
                    <p class="mt-3"><i class="fa fa-mail-bulk"></i> 이메일</p>
                    <span class="col-12 genric-btn primary-border radius"><?=$userInfo["email"]?></span>

                    <p class="mt-3"><i class="fa fa-clock"></i> 마지막 로그인</p>
                    <span class="col-12 genric-btn primary-border radius"><?=$userInfo["accessDate"]?></span>
                </div>
            </div>
        </div>
    </section>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>