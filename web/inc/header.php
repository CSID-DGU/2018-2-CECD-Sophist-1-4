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
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:title" content="<?=$CONST_PROJECT_NAME?>">
    <meta name="description" content="<?=$CONST_DESC?>">
    <meta property="og:description" content="<?=$CONST_DESC_SHORT?>">
    <meta property="og:image" content="http://picklecode.co.kr/eVote/web/img/logo.png">

    <title><?=$CONST_PROJECT_NAME.$CONST_TITLE_POSTFIX?></title>
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CVarela+Round" rel="stylesheet">
    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- Owl Carousel -->
    <link type="text/css" rel="stylesheet" href="css/owl.carousel.css" />
    <link type="text/css" rel="stylesheet" href="css/owl.theme.default.css" />
    <!-- Magnific Popup -->
    <link type="text/css" rel="stylesheet" href="css/magnific-popup.css" />
    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/AjaxUtil.js"></script>
    <script type="text/javascript" src="js/web3.js"></script>


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
        });

        jQuery.extend( jQuery.fn.pickadate.defaults, {
            monthsFull: [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
            monthsShort: [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
            weekdaysFull: [ '일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일' ],
            weekdaysShort: [ '일', '월', '화', '수', '목', '금', '토' ],
            today: '오늘',
            clear: '취소',
            firstDay: 1,
            format: 'yyyy 년 mm 월 dd 일',
            formatSubmit: 'yyyy/mm/dd'
        });

        jQuery.extend( jQuery.fn.pickatime.defaults, {
            clear: '취소'
        });

    </script>
</head>