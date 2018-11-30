<? include_once "./inc/header.php"; ?>
<script>
    $(document).ready(function(){
        callJson(
            "/eVote/shared/public/route.php?F=UserAuthRoute.rr",
            null, function(data){
                if(data.returnCode == 1){
                    alert(data.returnMessage);
                }else{
                    alert("오류가 발생하였습니다.\n관리자에게 문의하세요.");
                }
            }
        )
    });
</script>
<body>
	<header>
		<nav id="nav" class="navbar">
			<div class="container">
<? include_once "./inc/navigator.php"; ?>

	</header>
	<!-- /Header -->

	<!-- Blog -->
	<div id="blog" class="section md-padding">

		<!-- Container -->
		<div class="container">

			<!-- Row -->
			<div class="row">

				<!-- Main -->
				<main id="main" class="col-md-9">
						<!-- reply form -->
						<div class="reply-form text-center">
							<h3 class="title">회원 로그인</h3>
							<form>
                                <input class="input" type="email" placeholder="이메일">
                                <br/>
								<input class="input" type="password" placeholder="패스워드">
                                <br/>
								<button type="submit" class="main-btn">로그인</button>
							</form>
						</div>
						<!-- /reply form -->
					</div>
				</main>
				<!-- /Main -->

			</div>
			<!-- /Row -->

		</div>
		<!-- /Container -->

	</div>
	<!-- /Blog -->

<? include_once "./inc/footer.php"; ?>