<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/utils/AuthUtil.php"; ?>

<script>
    $(document).ready(function(){
        $(".jLoginBt").click(function(){
            location.href="login.php";
        });

        $(".jLogout").click(function(){
            callJson(
                "/eVote/shared/public/route.php?F=UserAuthRoute.requestLogout",
                null, function(data){
                    if(data.returnCode == 1){
                        alert(data.returnMessage);
                        location.href = "index.php";
                    }else{
                        alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                    }
                }
            );
        });
    });
</script>

<div class="navbar-header">
    <!-- Logo -->
    <div class="navbar-brand">
        <a href="index.php">
            <img class="logo" src="img/logo.png" alt="logo">
            <img class="logo-alt" src="img/logo-alt.png" alt="logo">
        </a>
    </div>
    <!-- /Logo -->

    <!-- Collapse nav button -->
    <div class="nav-collapse">
        <span></span>
    </div>
    <!-- /Collapse nav button -->
</div>

<!--  Main navigation  -->
<ul class="main-nav nav navbar-nav navbar-right">
    <li><a href="index.php">홈</a></li>
    <li><a href="room.php">투표/설문</a></li>
    <li><a href="group.php">그룹</a></li>
    <li><a href="notice.php">공지사항</a></li>
    <li><a href="faq.php">FAQ</a></li>
    <? if(AuthUtil::isLoggedIn()){ ?>
        <li class="has-dropdown"><a href="#"><?=AuthUtil::getLoggedInfo()->name?></a>
            <ul class="dropdown">
                <li><a href="dashboard.php" class=""><i class="fa fa-dashboard"> </i> 대시보드</a></li>
                <li><a href="profile.php" class=""><i class="fa fa-user"> </i> 내 정보</a></li>
                <li><a href="#" class="jLogout"><i class="fa fa-sign-out"> </i> 로그아웃</a></li>
            </ul>
        </li>
    <?}else{?>
        <li><a href="#" class="jLoginBt">로그인</a></li>
    <?}?>
</ul>
<!-- /Main navigation -->

</div>
</nav>
<!-- /Nav -->