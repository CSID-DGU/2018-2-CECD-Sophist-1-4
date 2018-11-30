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
    <li><a href="#">About</a></li>
    <li><a href="#">Portfolio</a></li>
    <li><a href="#">Services</a></li>
    <li><a href="#">Prices</a></li>
    <li><a href="#">Team</a></li>
    <li><a href="faq.php">FAQ</a></li>
    <? if(AuthUtil::isLoggedIn()){ ?>
        <li class="has-dropdown"><a href="#blog">마이페이지</a>
            <ul class="dropdown">
                <li><a href="profile.php" class="">로그아웃</a></li>
                <li><a href="#" class="jLogout">로그아웃</a></li>
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