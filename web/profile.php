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

    <section class="contact-section area-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">내 정보</h2>
                    <h4>반갑습니다, <?=$userInfo["name"]?>님!</h4>
                </div>
                <div class="col-12 text-right">
                    <a href="dashboard.php" class="genric-btn info-border radius"><i class="fa fa-list"></i> 대시보드</a>
                </div>
                <div class="col-12">
                    <p class="mt-3"><i class="fa fa-mail-bulk"></i> 이메일</p>
                    <span class="col-12 genric-btn primary-border radius"><?=$userInfo["email"]?></span>
                    <p class="mt-3"><i class="fa fa-user"></i> 성명</p>
                    <span class="col-12 genric-btn primary-border radius"><?=$userInfo["name"]?></span>
                    <p class="mt-3"><i class="fa fa-phone"></i> 전화번호</p>
                    <span class="col-12 genric-btn primary-border radius"><?=$userInfo["phone"]?></span>
                    <p class="mt-3"><i class="fa fa-info-circle"></i> 성별</p>
                    <?
                    switch ($userInfo["sex"]){
                        case "N": $sexInfo = "미응답"; break;
                        case "M": $sexInfo = "남성"; break;
                        case "F": $sexInfo = "여성"; break;
                        default: $sexInfo = "오류"; break;
                    }
                    ?>
                    <span class="col-12 genric-btn primary-border radius"><?=$sexInfo?></span>
                    <p class="mt-3"><i class="fa fa-clock"></i> 마지막 로그인</p>
                    <span class="col-12 genric-btn primary-border radius"><?=$userInfo["accessDate"]?></span>
                </div>
            </div>
        </div>
    </section>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>