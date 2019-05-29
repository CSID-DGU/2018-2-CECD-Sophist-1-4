<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
    <script>

        $(document).ready(function(){

            var currentPage = 1;
            var isFinal = false;

            function loadMore(page){
                loadPageInto(
                    "/eVote/web/ajaxPages/ajaxNoticeList.php",
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

            $(".jSearch").click(function(){
                var searchText = encodeURI($(".jSearchTxt").val());
                location.href = "notice.php?query=" + searchText;
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
                        table : "tblNotice",
                        col : "title"
                    },
                    function(data){
                        console.log(data);
                        var html = "";
                        for(var w = 0; w < data.length; w++){
                            html += "<div class='col-12 genric-btn primary-border radius recommend jRecList mt-1 recommend jRecList'>" + data[w] + "</div>";
                        }
                        $(".jRec").html(html);
                    }
                );
            });

            $(document).on("click", ".jDetail", function(){
                var id = $(this).attr("noticeID");
                location.href = "noticeDetail.php?id=" + id;
            });

            buttonLink(".jMyGroup", "myGroup.php");
            buttonLink(".jCGroup", "createGroup.php");

        });
    </script>

    <div class="apartment_part">
        <div class="container">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold">공지사항</h1>
                    </div>
                </div>
                <div class="widget-search btn-group col-12">
                    <input class="form-control placeholder hide-on-focus jSearchTxt col-10" type="text" value="<?=$_REQUEST["query"]?>" placeholder="공지사항 검색"/>
                    <button class="button jSearch col-2" type="button"><i class="fa fa-search"></i></button>
                </div>
                <div class="col-12 recommend jRec">
                </div>
            </div>
            <div class="row jContainer mt-3">
            </div>
        </div>
        <div class="text-center mt-3 mb-5">
            <button class="genric-btn info-border radius jLoadMore"><i class="fa fa-spinner"></i> 더 보기</button>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>