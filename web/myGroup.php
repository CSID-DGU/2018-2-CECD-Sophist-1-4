<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
}
?>
    <script>

        $(document).ready(function(){

            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/eVote/web/ajaxPages/ajaxMyGroupList.php",
                    {
                        page : page,
                        query : "<?=$_REQUEST["query"]?>"
                    },
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

            $(document).on("click", ".jDetail", function(){
                var id = $(this).attr("groupId");
                location.href = "groupDetail.php?id=" + id;
            });

            buttonLink(".jCGroup", "createGroup.php");

            $(".jBack").click(function(){
                history.back();
            });

        });
    </script>

    <div class="apartment_part">
        <div class="container">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold">그룹</h1>
                    </div>
                </div>
                <div class="col-12 mb-3 text-right">
                    <button class="genric-btn info-border radius jBack"><i class="fa fa-times"></i> 이전으로</button>
                    <button class="genric-btn primary-border radius jCGroup"><i class="fa fa-plus"></i> 추가</button>
                </div>
                <div class="col-12 recommend jRec">
                </div>
            </div>
            <div class="row jContainer">
            </div>
        </div>
        <div class="text-center mt-3 mb-5">
            <button class="genric-btn info-border radius jLoadMore"><i class="fa fa-spinner"></i> 더 보기</button>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>