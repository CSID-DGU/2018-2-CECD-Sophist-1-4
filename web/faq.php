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
				<main id="main" class="col-md-9 text-center">
                    <h3 class="title">FAQ</h3>
						<!-- reply form -->
                    <? foreach ($list as $item){ ?>
                    <button class="collapsible"><?=$item["title"]?></button>
                    <div class="collapsible_content">
                        <p><?=$item["content"]?></p>
                    </div>
                    <?}?>
				</main>
				<!-- /Main -->

			</div>
			<!-- /Row -->

		</div>
		<!-- /Container -->

	</div>
	<!-- /Blog -->

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>