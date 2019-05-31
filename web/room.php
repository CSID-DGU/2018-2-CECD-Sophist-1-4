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
                var st = $(this).attr("st");
                var done = $(this).attr("done");
                var endl = $(this).attr("endl");

                if(st == "0"){
                    swal("정보", "시작되지 않은 항목입니다.", "info");
                    return;
                }
                if(done == "1" && endl == "0"){
                    swal("정보", "마감된 항목입니다.", "info");
                    return;
                }

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
                            html += "<div class='col-12 genric-btn primary-border radius recommend jRecList mt-1'>" + data[w] + "</div>";
                        }
                        $(".jRec").html(html);
                    }
                );
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
                        <h1 class="non-bold">투표/설문</h1>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 text-right mb-3">
                    <button class="button jCRoom"><i class="fa fa-plus"></i> 추가</button>
                </div>
                <div class="widget-search btn-group col-12">
                    <input class="form-control placeholder hide-on-focus jSearchTxt col-10" type="text" value="<?=$_REQUEST["query"]?>" placeholder="투표/설문 검색"/>
                    <button class="button jSearch col-2" type="button"><i class="fa fa-search"></i></button>
                </div>
                <div class="col-12 recommend jRec">
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