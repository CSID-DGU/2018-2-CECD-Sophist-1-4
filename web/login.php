<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<?
    if(AuthUtil::isLoggedIn()){
        echo "<script>location.href='index.php';</script>";
    }
?>
<script>
    $(document).ready(function(){
        $(".jJoin").click(function(){
            location.href = "join.php";
        });

        $(".jLog").click(function(){
            if($(".jEmailTxt").val() == "" || $(".jPasswordTxt").val() == ""){
                swal("정보", "로그인 정보를 입력하세요.", "info");
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
                            location.href = "index.php?fl=" + data.data;
                        }
                    }else{
                        swal("정보", "오류가 발생하였습니다.\n관리자에게 문의하세요.", "warning");
                    }
                }
            )
        });
    });
</script>

    <section class="contact-section area-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">Member Login</h2>
                </div>
                <div class="col-lg-6">
                    <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input class="jEmailTxt form-control placeholder hide-on-focus" type="email" placeholder="이메일">
                                    <input class="jPasswordTxt mt-3 form-control placeholder hide-on-focus" type="password" placeholder="패스워드">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" class="button button-contactForm jLog"><i class="fa fa-sign-in"></i> 로그인</button>
                            <button type="button" class="button button-contactForm jJoin"><i class="fa fa-pencil"></i> 회원가입</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>