<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/GroupRoute.php"; ?>
<?
    if($_REQUEST["id"] == ""){
        echo "<script>alert('비정상적인 접근입니다.'); history.back();</script>";
    }

    $router = new GroupRoute();
    $item = $router->getGroup();
?>
<script>

    $(document).ready(function(){
        $(".jJoin").click(function(){
            location.href = "join.php";
        });

        $(".jBack").click(function(){
            history.back();
        });

        $(".jGJoin").click(function(){
            location.href = "groupDetail.php?id=<?=$_REQUEST["id"]?>";
        });

        $(".jLog").click(function(){
            if($(".jEmailTxt").val() == "" || $(".jPasswordTxt").val() == ""){
                alert("회원 정보를 입력하세요.");
                return;
            }
            callJson(
                "/eVote/shared/public/route.php?F=UserAuthRoute.requestLogin",
                {
                    email : $(".jEmailTxt").val(),
                    pwd : $(".jPasswordTxt").val()
                }
                , function(data){
                    if(data.returnCode > 0){
                        if(data.returnCode > 1){
                            alert(data.returnMessage);
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
                        <h2>안내</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item">안내</li>
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
			<div class="row">
                <div class="reply-form text-center">
                    <h4 class="title">본 투표/설문 참여를 위해서는 그룹 가입이 필요합니다.</h4>
                    <p class="tiny-padding">해당 그룹의 상세 페이지에서 가입을 완료한 이후 이용바랍니다.</p>
                    <?
                    $madeBy = $item["madeName"];
                    if($item["madeBy"]==0) $madeBy = "관리자";
                    ?>
                    <div class="blog-comments jContainer text-left">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <?if($item["needsAuth"] == 1){?>
                                        <i class="fa fa-lock"></i>&nbsp;
                                    <?}?>
                                    <?=$item["title"]?>
                                    <span class="time">
                                        <i class="fa fa-user"></i>&nbsp;<?=$madeBy?>&nbsp;&nbsp;
                                        <i class="fa fa-calendar"></i> <?=$item["regDate"]?></span>
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
                    </div>

                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-default jGJoin"><i class="fa fa-sign-in"></i> 그룹 상세로 이동</button>
                        <button type="button" class="btn bg-primary jBack"><i class="fa fa-times"></i> 취소</button>
                    </div>
                </div>
                <!-- /reply form -->
				<!-- Main -->
				<main id="main" class="col-md-9">
						<!-- reply form -->

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