<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<?
if(AuthUtil::isLoggedIn()){
    echo "<script>location.href='index.php';</script>";
}
?>
    <script>

        $(document).ready(function(){
            $(".jJoin").click(function(){
                if($(".jEmailTxt").val() == ""
                    || $(".jPhoneTxt").val() == ""
                    || $(".jNameTxt").val() == ""
                    || $(".jPasswordTxt").val() == ""
                    || $(".jSex").val() == ""){
                    swal("정보", "회원 정보를 모두 입력하시기 바랍니다.", "info");
                    return;
                }
                if($(".jPasswordTxt").val() != $(".jPasswordCTxt").val()){
                    swal("정보", "패스워드 확인이 일치하지 않습니다.", "info");
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
                    <h2 class="contact-title">Member Sign Up</h2>
                </div>
                <div class="col-lg-6">
                    <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input class="form-control placeholder hide-on-focus jNameTxt" type="text" placeholder="성명" />
                                    <input class="mt-3 form-control placeholder hide-on-focus jEmailTxt" type="email" placeholder="이메일" />
                                    <input class="mt-3 form-control placeholder hide-on-focus jPhoneTxt" type="text" placeholder="전화번호" />
                                    <input class="mt-3 form-control placeholder hide-on-focus jPasswordTxt" type="password" placeholder="패스워드" />
                                    <input class="mt-3 form-control placeholder hide-on-focus jPasswordCTxt" type="password" placeholder="패스워드 확인" />
                                    <select id="prd_" class="mt-3 form-control jSex" name="prd_">
                                        <option value="N">성별(선택)</option>
                                        <option value="M">남성</option>
                                        <option value="F">여성</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" class="button button-contactForm jJoin"><i class="fa fa-sign-in"></i> 가입하기</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>