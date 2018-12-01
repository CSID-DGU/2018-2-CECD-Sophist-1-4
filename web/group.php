<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
//if(AuthUtil::isLoggedIn()){
//    echo "<script>location.href='index.php';</script>";
//}
$router = new GroupRoute();
$list = $router->getGroupList();
?>
    <script>

        $(document).ready(function(){
            $(".jJoin").click(function(){
                if($(".jEmailTxt").val() == ""
                    || $(".jPhoneTxt").val() == ""
                    || $(".jNameTxt").val() == ""
                    || $(".jPasswordTxt").val() == ""
                    || $(".jSex").val() == ""){
                    alert("회원 정보를 모두 입력하세요.");
                    return;
                }
                if($(".jPasswordTxt").val() != $(".jPasswordCTxt").val()){
                    alert("패스워드 확인이 일치하지 않습니다.");
                    return;
                }

                callJson(
                    "/eVote/shared/public/route.php?F=UserAuthRoute.joinUser",
                    {
                        email : $(".jEmailTxt").val(),
                        pwd : $(".jPasswordTxt").val(),
                        phone : $(".jPhoneTxt").val(),
                        name : $(".jNameTxt").val(),
                        sex : $(".jSex").val()
                    }
                    , function(data){
                        if(data.returnCode > 0){
                            alert(data.returnMessage);
                            if(data.returnCode > 1){
                            }else{
                                location.href = "index.php";
                            }
                        }else{
                            alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                        }
                    }
                )
            });
        });
    </script>
    <body>
    <header>
        <nav id="nav" class="navbar">
            <div class="container">
                <? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/navigator.php"; ?>

                <div class="header-wrapper sm-padding bg-grey">
                    <div class="container">
                        <h2>그룹</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item">그룹</li>
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

                <div class="col-md-12 text-right">
                    <button class="btn btn-default"><i class="fa fa-list"></i> 내 그룹</button>
                    <button class="btn bg-primary"><i class="fa fa-plus"></i> 그룹 생성</button>
                </div>
                <!-- Main -->
                <main id="main" class="col-md-12">

                    <div class="blog-comments">
                        <?foreach($list as $item){?>
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading"><?=$item["title"]?><span class="time"><?=$item["madeName"]?> / <?=$item["regDate"]?></span>
                                    <a href="#" class="reply">자세히 <i class="fa fa-sign-in"></i></a>
                                </h4>
                                <p><?=$item["desc"]?></p>
                            </div>
                            <div class="blog-tags sm-tag">
                                <?
                                $tags = explode(",", $item["tag"]);
                                foreach ($tags as $tag){
                                ?>
                                <a href="#"><i class="fa fa-tag"></i><?=$tag?></a>
                                <?}?>
                            </div>
                        </div>
                        <?}?>

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