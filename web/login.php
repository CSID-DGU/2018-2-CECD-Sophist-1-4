<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<?
    if(AuthUtil::isLoggedIn()){
        echo "<script>location.href='index.php';</script>";
    }
?>
<script>

    $(document).ready(function(){
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
                        if(data.returnCode == 2){
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
                                <input class="input jEmailTxt" type="email" placeholder="이메일" />
                                <br/>
								<input class="input jPasswordTxt" type="password" placeholder="패스워드" />
                                <br/>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-default jLog"><i class="fa fa-sign-in"></i> 로그인</button>
                                    <button type="button" class="btn bg-primary jJoin"><i class="fa fa-pencil"></i> 회원가입</button>
                                </div>
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

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>