<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/header.php"; ?>
<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/shared/public/classes/WebInfoRoute.php"; ?>

<?
    $webInfo = new WebInfoRoute();
    $list = $webInfo->getFaqList();
?>
    <script>
        var web3 = new Web3();
        web3.setProvider(new Web3.providers.HttpProvider('http://localhost:8551'));

        /**
         * Filter for notifying when triggering
         */
        var blockFilter = web3.eth.subscribe('newBlockHeaders', blockFilterCallback);
        function blockFilterCallback(error, blockHash) {
            var block = web3.eth.getBlock(blockHash);
            console.log(block);
        }

        function updateBalance() {
            var address = web3.eth.accounts[0];
            var balance = web3.fromWei(web3.eth.getBalance(address), 'ether');

            console.log(address);
            console.log(balance);
        }

        function sendTransaction(){
            var toAddress = "TO_ADDR";
            var sendAmount = web3.toWei("1", 'ether');

            var txHash = web3.eth.sendTransaction({
                from: web3.eth.accounts[0],
                to: toAddress,
                value: sendAmount
            });

            console.log(txHash);
        }

        $(document).ready(function(){
            web3.personal.unlockAccount(web3.eth.accounts[0],'1111');
        });
    </script>
<body>
	<header>
		<nav id="nav" class="navbar">
			<div class="container">
                <? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/navigator.php"; ?>
                <div class="header-wrapper sm-padding bg-grey">
                    <div class="container">
                        <h2>web3.js 테스트 페이지</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">홈</a></li>
                            <li class="breadcrumb-item">web3.js 테스트 페이지</li>
                        </ul>
                    </div>
                </div>
	</header>
	<!-- /Header -->

	<!-- Blog -->
	<div id="blog" class="section md-padding">

		<!-- Container -->
		<div class="container">

			<!-- Row -->
			<div class="row">

				<!-- Main -->
				<main id="main" class="col-md-12 text-center">
<!--                    <h3 class="title">FAQ</h3>-->
						<!-- reply form -->

				</main>
				<!-- /Main -->

			</div>
			<!-- /Row -->

		</div>
		<!-- /Container -->

	</div>
	<!-- /Blog -->

<? include_once $_SERVER["DOCUMENT_ROOT"]."/eVote/web/inc/footer.php"; ?>