<?php
	require("../../../../configuration.php");
	//sessiooni käivitamine või kasutamine
	//session_start();
	//var_dump($_SESSION); 

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

	/*require("fnc_news.php");
	
	$NewsHTML = ReadNews(); */
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Meie äge koduleht</h1>
	<p>Tere! <?php echo $_SESSION["userFirstName"] . " " .$_SESSION["userLastName"]; ?></p>
	<p>See leht on valminud õppetöö raames!</p>
	<hr> 
	<li><a href="news.php">Loe uudiseid!</a></li>
	<li><a href="addnews.php">Lisa uudiseid!</a></li>
	<li><a href="logi.php">Lisa tegevus!</a></li>
	<li><a href="summary_log.php">Tegevuste ülevaade</a></li>
	<li><a href="photoUpload.php">Fotode üleslaadimine</a></li>
    <li><a href="privategallery.php">Minu pildid</a></li>
    <li><a href="semipublicgallery.php">Kõigi kasutajate avaldatavad pildid</a></li>
    <li><a href="publicgallery.php">Avalikud pildid</a></li>
	<hr>
	<p>Logi <a href="?logout=1">välja!</a></p>
	
</body>
</html> 