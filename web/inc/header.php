<?
include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/bases/utils/AuthUtil.php";
$CONST_PROJECT_NAME = "풀링폴링";
$CONST_TITLE_POSTFIX = " :: 깨끗하고 빠른 의견수렴 서비스";
$CONST_DESC_SHORT = "깨끗하고 빠른 의견수렴 서비스";
$CONST_DESC = "아직도 불편한 설문조사 서비스를 이용하고 계신가요? 지금 바로 풀링폴링과 함께 하세요!";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:title" content="<?=$CONST_PROJECT_NAME?>">
    <meta name="description" content="<?=$CONST_DESC?>">
    <meta property="og:description" content="<?=$CONST_DESC_SHORT?>">
    <meta property="og:image" content="http://picklecode.co.kr/eVote/web/img/logo.png">

    <title><?=$CONST_PROJECT_NAME.$CONST_TITLE_POSTFIX?></title>

    <link rel="icon" href="img/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- themify CSS -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="css/flaticon.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="js/snackbar.min.css" />
    <script src="js/snackbar.min.js"></script>
    <script src="js/sweetalert.min.js"></script>

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/AjaxUtil.js"></script>
    <script type="text/javascript" src="js/web3.js"></script>

    <!-- jquery plugins here-->
    <!-- jquery -->
    <!-- popper js -->
    <script src="js/popper.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- easing js -->
    <script src="js/jquery.magnific-popup.js"></script>
    <!-- particles js -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- easing js -->
    <script src="js/jquery.easing.min.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>
</head>

<!--    pickadate.js-->
<script type="text/javascript" src="js/picker.js"></script>
<script type="text/javascript" src="js/legacy.js"></script>
<script type="text/javascript" src="js/picker.date.js"></script>
<script type="text/javascript" src="js/picker.time.js"></script>
<link type="text/css" rel="stylesheet" href="css/default.css"/>
<link type="text/css" rel="stylesheet" href="css/default.date.css"/>
<link type="text/css" rel="stylesheet" href="css/default.time.css"/>

<script>
    $(document).ready(function(){
        var coll = $(".collapsible");
        for (var i = 0; i < coll.length; i++){
            coll[i].addEventListener("click", function(){
                this.classList.toggle("active");
                var content = this.nextElementSibling;

                if (content.style.maxHeight)
                    content.style.maxHeight = null;
                else
                    content.style.maxHeight = content.scrollHeight + "px";
            });
        }

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

        jQuery.extend( jQuery.fn.pickadate.defaults, {
            monthsFull: [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
            monthsShort: [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
            weekdaysFull: [ '일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일' ],
            weekdaysShort: [ '일', '월', '화', '수', '목', '금', '토' ],
            today: '오늘',
            clear: '지우기',
            close: '닫기',
            firstDay: 1,
            format: 'yyyy년 mm월 dd일',
            formatSubmit: 'yyyy/mm/dd'
        });

        jQuery.extend( jQuery.fn.pickatime.defaults, {
            clear: '지우기'
        });
    });

    function showSnackBar(text){
        Snackbar.show({pos: 'bottom-left', duration:30000, text: text, actionText:'닫기', actionTextColor:'#fff'});
    }

    function verifyText(content, msg){
        if(content == ""){
            alert(msg);
            return false;
        }
        return true;
    }

</script>
</head>

<body>
<!--::menu part start::-->
<header class="main_menu">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="index.php"> <img src="img/logo.png" alt="logo"> </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse main-menu-item" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="room.php?type=A">투표/설문</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="group.php">그룹</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link page-scroll" href="notice.php">공지사항</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link page-scroll" href="faq.php">FAQ</a>
                            </li>
                            <? if(AuthUtil::isLoggedIn()){ ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?=AuthUtil::getLoggedInfo()->name?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="dashboard.php">대시보드</a>
                                        <a class="dropdown-item" href="profile.php">내 정보</a>
                                        <a class="dropdown-item jLogout" href="#">로그아웃</a>
                                        <?if(AuthUtil::getLoggedInfo()->isAdmin == 1){?>
                                        <a class="dropdown-item" href="admin.php">관리자 모드</a>
                                        <?}?>
                                    </div>
                                </li>
                            <?}else{?>
                                <li class="nav-item">
                                    <a class="nav-link page-scroll jLoginBt" href="#">로그인</a>
                                </li>
                            <?}?>
                        </ul>
                    </div>
<!--                    <div class="btn_1 d-none d-lg-block">-->
<!--                        <a href="#" class="float-right">Submit property</a>-->
<!--                    </div>-->
                </nav>
            </div>
        </div>
    </div>
</header>
<!--::menu part end::-->