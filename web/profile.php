<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/UserAuthRoute.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>location.href='index.php';</script>";
}

$router = new UserAuthRoute();
$userInfo = $router->getUser(AuthUtil::getLoggedInfo()->id);

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
                <div class="header-wrapper sm-padding bg-grey">
                    <div class="container">
                        <h2>내 정보</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item">내 계정</li>
                            <li class="breadcrumb-item">내 정보</li>
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

                <!-- Main -->
                <main id="main" class="">
                    <!-- reply form -->
                    <div class="text-left col-md-6 md-padding">
                        <h3 class="title">계정 정보</h3>
                        <h4><?=$userInfo["email"]?> (<?=$userInfo["name"]?>)</h4>
                        <form>
                            <input style="margin:10px 0px;" class="input jPhoneTxt" value="<?=$userInfo["phone"]?>" type="text" placeholder="전화번호" />
                            <br/>
                            <input style="margin:10px 0px;" class="input jPasswordTxt" type="password" placeholder="패스워드" />
                            <br/>
                            <input style="margin:10px 0px;" class="input jPasswordCTxt" type="password" placeholder="패스워드 확인" />
                            <br/>
                            <div class="input selectpicker control-label">
                                <select id="prd_" class="form-control jSex" name="prd_">
                                    <option value="N" <?=$userInfo["sex"]=="N" ? "SELECTED" : ""?>>성별(선택)</option>
                                    <option value="M" <?=$userInfo["sex"]=="M" ? "SELECTED" : ""?>>남성</option>
                                    <option value="F" <?=$userInfo["sex"]=="F" ? "SELECTED" : ""?>>여성</option>
                                </select>
                            </div>
                            <br/><br/>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn bg-primary jJoin"><i class="fa fa-edit"></i> 변경사항 저장</button>
                            </div>
                        </form>
                    </div>


                    <div class="text-left col-md-6 md-padding">
                        <h3 class="title">서비스</h3>
                        <form>
                            <p>총 참여 투표 : <b> 0 </b>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="#" class="btn-default btn">내 투표기록 보기</a></p>
                            <br/>
                            <p>총 참여 설문 : <b> 0 </b>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="#" class="btn-default btn">내 설문기록 보기</a></p>
                            <br/>
                            <p>총 소속 그룹 : <b> 0 </b>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="#" class="btn-default btn">내 그룹 모두 보기</a></p>
                            <br/><br/>
                            <div class="btn-group" role="group" aria-label="Basic example">
<!--                                <button type="button" class="btn bg-primary jJoin"><i class="fa fa-pencil"></i> 가입하기</button>-->
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