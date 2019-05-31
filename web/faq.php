<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/WebInfoRoute.php"; ?>

<?
    $webInfo = new WebInfoRoute();
    $list = $webInfo->getFaqList();
?>
<script>
    $(document).ready(function(){
    });
</script>

    <div class="apartment_part">
        <div class="container">
            <div class="row justify-content-between align-content-center">
                <div class="col-md-8 col-lg-8 col-sm-8">
                    <div class="section_tittle">
                        <h1 class="non-bold">FAQ</h1>
                    </div>
                </div>
            </div>
            <div class="row jContainer mb-5">
                <? foreach ($list as $item){ ?>
                    <button class="collapsible"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;<?=$item["title"]?></button>
                    <div class="collapsible_content">
                        <p class="faq-answer"><?=$item["content"]?></p>
                    </div>
                <?}?>
            </div>
        </div>
    </div>

    <div class="cta_part">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="cta_iner">
                        <h1 class="non-bold">원하시는 답변이 없어 고민이신가요?</h1>
                        <a href="mailto:pullingpolling@gmail.com" class="cta_btn">1 : 1 메일 문의</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>