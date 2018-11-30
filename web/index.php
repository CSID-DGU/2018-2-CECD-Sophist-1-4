<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<script>
    $(document).ready(function(){
        if("<?=$_REQUEST["msg"] != ""?>"){
            alert("<?=$_REQUEST["msg"]?>");
            location.href="index.php";
        }

        $(".jDashboardGo").click(function(){
            location.href="dashboard.php";
        });
        $(".jJoinGo").click(function(){
            location.href="join.php";
        });
        $(".jLoginGo").click(function(){
            location.href="login.php";
        });
    });
</script>

<body>
	<!-- Header -->
	<header id="home">
		<!-- Background Image -->
		<div class="bg-img" style="background-image: url('./img/background1.jpg');">
			<div class="overlay"></div>
		</div>
		<!-- /Background Image -->

		<!-- Nav -->
		<nav id="nav" class="navbar nav-transparent">
			<div class="container">

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web//inc/navigator.php"; ?>

		<!-- home wrapper -->
		<div class="home-wrapper">
			<div class="container">
				<div class="row">

					<!-- home content -->
					<div class="col-md-10 col-md-offset-1">
						<div class="home-content">
							<h1 class="white-text"><?=$CONST_PROJECT_NAME?></h1>
							<p class="white-text">
                                아직도 불편한 설문조사 서비스를 이용하고 계신가요? 보기 힘든 결과 자료를 검토하고 계신가요?
                                <br/>깨끗하고 빠른 <?=$CONST_PROJECT_NAME?>과 함께 의견을 모아보세요!
							</p>
							<?if(AuthUtil::isLoggedIn()){?>
							<button class="main-btn jDashboardGo">대시보드 바로가기</button>
							<?}else{?>
                                <button class="white-btn jLoginGo">로그인</button>
							    <button class="main-btn jJoinGo">간편 회원가입</button>
							<?}?>
						</div>
					</div>
					<!-- /home content -->

				</div>
			</div>
		</div>
		<!-- /home wrapper -->

	</header>
	<!-- /Header -->

	<!-- About -->
	<div id="about" class="section md-padding">

		<!-- Container -->
		<div class="container">

			<!-- Row -->
			<div class="row">

				<!-- Section header -->
				<div class="section-header text-center">
					<h2 class="title"><?=$CONST_PROJECT_NAME?>을 만나보세요!</h2>
				</div>
				<!-- /Section header -->

				<!-- about -->
				<div class="col-md-4">
					<div class="about">
						<i class="fa fa-cogs"></i>
						<h3>빠르고 효율적으로!</h3>
						<p>직관적인 관리방법과 매우 효과적인 결과 조회 및 통계 서비스를 제공하여<br/>보다 빠르고 효율적으로 의견을 모아볼 수 있습니다.</p>
<!--						<a href="#">Read more</a>-->
					</div>
				</div>
				<!-- /about -->

				<!-- about -->
				<div class="col-md-4">
					<div class="about">
						<i class="fa fa-magic"></i>
						<h3>마법은 저희가 부릴게요 :)</h3>
						<p>간단한 내용만 입력해주세요! 풍부하고 유연한 기능으로 도와드릴게요.</p>
<!--						<a href="#">Read more</a>-->
					</div>
				</div>
				<!-- /about -->

				<!-- about -->
				<div class="col-md-4">
					<div class="about">
						<i class="fa fa-mobile"></i>
						<h3>언제 어디서나!</h3>
						<p>저희 <?=$CONST_PROJECT_NAME?>는 플랫폼을 가리지 않아요~! 언제 어디서든 편리하게 이용해보세요!</p>
<!--						<a href="#">Read more</a>-->
					</div>
				</div>
				<!-- /about -->

			</div>
			<!-- /Row -->

		</div>
		<!-- /Container -->

	</div>
	<!-- /About -->

	<!-- Numbers -->
	<div id="numbers" class="section sm-padding">

		<!-- Background Image -->
		<div class="bg-img" style="background-image: url('./img/background2.jpg');">
			<div class="overlay"></div>
		</div>
		<!-- /Background Image -->

		<!-- Container -->
		<div class="container">

			<!-- Row -->
			<div class="row">

				<!-- number -->
				<div class="col-sm-3 col-xs-6">
					<div class="number">
						<i class="fa fa-user"></i>
						<h3 class="white-text"><span class="counter">1000</span></h3>
						<span class="white-text">총 이용자</span>
					</div>
				</div>
				<!-- /number -->

				<!-- number -->
				<div class="col-sm-3 col-xs-6">
					<div class="number">
						<i class="fa fa-users"></i>
						<h3 class="white-text"><span class="counter">10</span></h3>
						<span class="white-text">총 그룹</span>
					</div>
				</div>
				<!-- /number -->

				<!-- number -->
				<div class="col-sm-3 col-xs-6">
					<div class="number">
						<i class="fa fa-pencil"></i>
						<h3 class="white-text"><span class="counter">2500</span></h3>
						<span class="white-text">총 응답수</span>
					</div>
				</div>
				<!-- /number -->

				<!-- number -->
				<div class="col-sm-3 col-xs-6">
					<div class="number">
						<i class="fa fa-file"></i>
						<h3 class="white-text"><span class="counter">45</span></h3>
						<span class="white-text">진행된 투표/설문</span>
					</div>
				</div>
				<!-- /number -->

			</div>
			<!-- /Row -->

		</div>
		<!-- /Container -->

	</div>
	<!-- /Numbers -->


<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web//inc/footer.php"; ?>