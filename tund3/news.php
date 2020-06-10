<?php
	require("../../../../configuration.php");
	require("fnc_news.php");

	require("classes/Session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~mikk.herde/", "tigu.hk.tlu.ee");

	//kas on sisse loginud
	if(!isset($_SESSION["userid"])) {
		//jõuga avalehele
		header("Location: page.php");
	}

	//login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}

	
	$NewsHTML = ReadNews();
?>
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Uudised</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<div>
		<?php echo $NewsHTML; ?>
	</div>
	<p>Logi <a href="?logout=1">välja!</a></p>

</body>
</html>