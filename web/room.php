<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<?
if($_REQUEST["type"] == ""){
    echo "<script>location.href='room.php?type=A';</script>";
}
?>
    <script>

        $(document).ready(function(){

            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/eVote/web/ajaxPages/ajaxRoomList.php",
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

            buttonLink(".jShowA", "room.php?type=A&query=<?=$_REQUEST["query"]?>");
            buttonLink(".jShowV", "room.php?type=V&query=<?=$_REQUEST["query"]?>");
            buttonLink(".jShowS", "room.php?type=S&query=<?=$_REQUEST["query"]?>");

            loadMore(currentPage);

            $(".jLoadMore").click(function(){
                loadMore(++currentPage);
            });

            $(document).on("click", ".jDetail", function(){
                var id = $(this).attr("roomId");
                location.href = "roomDetail.php?id=" + id;
            });

            $(".jSearch").click(function(){
                var searchText = encodeURI($(".jSearchTxt").val());
                var type = "<?=$_REQUEST["type"] == "" ? "A" : $_REQUEST["type"]?>";
                location.href = "room.php?query=" + searchText + "&type=" + type;
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
                        table : "tblRoom",
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

            buttonLink(".jMyGroup", "myGroup.php");
            buttonLink(".jCGroup", "createGroup.php");

        });
    </script>
    <body>
    <header>
        <nav id="nav" class="navbar">
            <div class="container">
                <? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/navigator.php"; ?>

                <div class="header-wrapper sm-padding bg-grey">
                    <div class="container">
                        <h2>투표 및 설문</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item">투표 및 설문</li>
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
                    <button class="btn btn-default jMyGroup"><i class="fa fa-list"></i> 참여중인 투표/설문</button>
                    <button class="btn bg-primary jCGroup"><i class="fa fa-plus"></i> 투표/설문 생성</button>
                </div>
                <br/>
                <aside id="aside" class="col-md-3">
                    <div class="widget">
                        <div class="widget-search">
                            <input class="search-input jSearchTxt" type="text" value="<?=$_REQUEST["query"]?>" placeholder="투표/설문 검색">
                            <button class="search-btn jSearch" type="button"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="blog-comments recommend jRec">
                        </div>
                    </div>
                </aside>
                <!-- Main -->
                <main id="main" class="col-md-12">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn <?=$_REQUEST["type"] == "A" ? "bg-primary" : ""?> jShowA">전체</button>
                        <button type="button" class="btn <?=$_REQUEST["type"] == "V" ? "bg-primary" : ""?> jShowV">투표</button>
                        <button type="button" class="btn <?=$_REQUEST["type"] == "S" ? "bg-primary" : ""?> jShowS">설문</button>
                    </div>
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