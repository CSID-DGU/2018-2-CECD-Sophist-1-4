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
<?
$madeName = "관리자";
if($item["madeBy"] != 0) $madeName .= "(".$item["madeName"].")";
?>
    <!--================Blog Area =================-->
    <section class="blog_area single-post-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold" style="color: black;">공지사항 상세</h1>
                    </div>
                </div>
                <div class="col-lg-8 posts-list">
                    <div class="single-post">
                        <div class="blog_details">
                            <h2><?=$item["title"]?></h2>
                            <ul class="blog-info-link mt-3 mb-4">
                                <li><a href="#"><i class="far fa-user"></i> <?=$madeName?></a></li>
                                <li><a href="#"><i class="far fa-clock"></i> <?=$item["regDate"]?></a></li>
                                <li><a href="#"><i class="far fa-eye"></i> <?=$item["hit"]?></a></li>
                            </ul>
                            <p class="excert">
                                <?=$item["desc"]?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
        </div>
    </section>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>