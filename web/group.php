<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
    <script>

        $(document).ready(function(){

            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/eVote/web/ajaxPages/ajaxGroupList.php",
                    {page : page},
                    ".jContainer",
                    true,
                    function(){
                        isFinal = true;
                        currentPage--;
                        $(".jLoadMore").hide();
                    }
                );
            }

            loadMore(currentPage);

            $(".jLoadMore").click(function(){
                loadMore(++currentPage);
            });

            $(document).on("click", ".jRecList", function(){
                $(".jSearchTxt").val($(this).html());
                $(".jRec").html("");
            });

            $(".jSearchTxt").keyup(function(){
                if($(this).val().trim() == ""){
                    $(".jRec").html("");
                    return;
                }
                callJsonIgnoreError(
                    "/eVote/web/ajaxPages/ajaxRecommendation.php",
                    {
                        key : $(this).val(),
                        table : "tblGroup",
                        col : "title"
                    },
                    function(data){
                        console.log(data);
                        var html = "";
                        for(var w = 0; w < data.length; w++){
                            html += "<div class='media recommend jRecList'>" + data[w] + "</div>";
                        }
                        $(".jRec").html(html);
                    }
                );
            });

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
                <br/>
                <aside id="aside" class="col-md-3">
                    <div class="widget">
                        <div class="widget-search">
                            <input class="search-input jSearchTxt" type="text" placeholder="그룹 검색">
                            <button class="search-btn jSearch" type="button"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="blog-comments recommend jRec">
                        </div>
                    </div>
                </aside>
                <!-- Main -->
                <main id="main" class="col-md-12">
                    <div class="blog-comments jContainer">
                    </div>

                    <div class="text-center">
                    <button class="btn btn-default jLoadMore"><i class="fa fa-spinner"></i> 더 보기</button>
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