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
                        <h3 class="title">회원가입</h3>
                        <form>
                            <input class="input jNameTxt" type="text" placeholder="성명" />
                            <br/>
                            <input class="input jEmailTxt" type="email" placeholder="이메일" />
                            <br/>
                            <input class="input jPhoneTxt" type="text" placeholder="전화번호" />
                            <br/>
                            <input class="input jPasswordTxt" type="password" placeholder="패스워드" />
                            <br/>
                            <input class="input jPasswordCTxt" type="password" placeholder="패스워드 확인" />
                            <br/>
                            <select class="input jSex">
                                <option value="N">성별(선택)</option>
                                <option value="M">남성</option>
                                <option value="F">여성</option>
                            </select>
                            <br/><br/>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn bg-primary jJoin"><i class="fa fa-pencil"></i> 가입하기</button>
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