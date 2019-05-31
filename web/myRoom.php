<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<?
if(!AuthUtil::isLoggedIn()){
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
}
?>
<?
if($_REQUEST["type"] == ""){
    echo "<script>location.href='myRoom.php?type=A';</script>";
}
?>
    <script>

        $(document).ready(function(){

            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/eVote/web/ajaxPages/ajaxMyRoomList.php",
                    {
                        page : page,
                        type : "<?=$_REQUEST["type"]?>",
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

            buttonLink(".jShowA", "myRoom.php?type=A&query=<?=$_REQUEST["query"]?>");
            buttonLink(".jShowV", "myRoom.php?type=V&query=<?=$_REQUEST["query"]?>");
            buttonLink(".jShowS", "myRoom.php?type=S&query=<?=$_REQUEST["query"]?>");

            loadMore(currentPage);

            $(".jLoadMore").click(function(){
                loadMore(++currentPage);
            });

            $(document).on("click", ".jDetail", function(){
                var id = $(this).attr("roomId");
                location.href = "myRoomDetail.php?id=" + id;
            });

            buttonLink(".jMyGroup", "myGroup.php");
            buttonLink(".jCRoom", "createRoom.php");

        });
    </script>

    <div class="apartment_part">
        <div class="container">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold">투표/설문 집계</h1>
                    </div>
                </div>
                <div class="btn-group col-12 mb-3 mt-3" role="group" aria-label="Basic example">
                    <button type="button" class="button <?=$_REQUEST["type"] == "A" ? "bg-primary" : ""?> jShowA">전체</button>
                    <button type="button" class="button <?=$_REQUEST["type"] == "V" ? "bg-primary" : ""?> jShowV">투표</button>
                    <button type="button" class="button <?=$_REQUEST["type"] == "S" ? "bg-primary" : ""?> jShowS">설문</button>
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