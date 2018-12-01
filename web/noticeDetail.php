<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/WebInfoRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new WebInfoRoute();
$router->updateNoticeHit();
$item = $router->getNotice();
?>

    <script>

        $(document).ready(function(){
            buttonLink(".jBack", "notice.php");

        });
    </script>
    <body>
    <header>
        <nav id="nav" class="navbar">
            <div class="container">
                <? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/navigator.php"; ?>

                <div class="header-wrapper sm-padding bg-grey">
                    <div class="container">
                        <h2>공지사항 상세</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item"><a href="notice.php">공지사항</a></li>
                            <li class="breadcrumb-item">공지사항 상세</li>
                        </ul>
                    </div>
                </div>

    </header>
    <!-- /Header -->

    <!-- Blog -->
    <div id="blog" class="section">
        <!-- Container -->
        <div class="container">

            <!-- Row -->
            <div class="row tiny-padding">
                <!-- Main -->
                <main id="main" class="col-md-12">
                    							<h3 class="title"><?=$item["title"]?></h3>
                    <div class="blog">
<!--                        <div class="blog-img">-->
<!--                            <img class="img-responsive" src="./img/blog-post.jpg" alt="">-->
<!--                        </div>-->
                        <div class="blog-content">
                            <ul class="blog-meta">
                                <?
                                $madeName = "관리자";
                                if($item["madeBy"] != 0) $madeName .= "(".$item["madeName"].")";
                                ?>
                                <li><i class="fa fa-user"></i><?=$madeName?></li>
                                <li><i class="fa fa-clock-o"></i><?=$item["regDate"]?></li>
                                <li><i class="fa fa-history"></i><?=$item["hit"]?></li>
                            </ul>
                            <p>
                                <?=$item["desc"]?>
                            </p>
                        </div>
                    </div>

                        <div class="text-center">
                    <button class="btn btn-default jBack"><i class="fa fa-list"></i> 목록으로</button>
                    </div>
                    <!-- blog tags -->
            </div>
            </main>
            <!-- /Main -->

        </div>
        <!-- /Row -->

    </div>
    <!-- /Container -->

    </div>
    <!-- /Blog -->

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>