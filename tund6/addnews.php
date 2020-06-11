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


	//include võib ka olla require asemel, lihtsalt jätkab,kui pole midagi, aga soovitatakse require, turvalisem.
	//var_dump($_POST);
	//echo $_POST["NewsTitle"];
	$NewsTitle = null;
	$NewsContent = null;
	$NewsError = null;

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	  }

	if(isset($_POST["NewsBtn"])){
		if(isset($_POST["NewsTitle"]) and !empty(test_input($_POST["NewsTitle"]))){
			$NewsTitle = test_input($_POST["NewsTitle"]);
		} else{
			$NewsError = "Uudise pealkiri on sisestamata! ";}
		if(isset($_POST["NewsEditor"]) and !empty(test_input($_POST["NewsEditor"]))){
			$NewsContent = test_input($_POST["NewsEditor"]);
		}	else{
			$NewsError = "Uudise sisu on kirjutamata! ";}
		//echo $NewsTitle ."\n";
		//echo $NewsContent;
		//Saadame andmebaasi
		if(empty($NewsError)){
			//echo "Salvestame!";
			$response = SaveNews($NewsTitle, $NewsContent);
			if($response == 1){
				$NewsError = "Uudis on salvestatud!";
			}else {
				$NewsError = "Uudise salvestamisel tekkis viga!";
			}
		}
	}
?>
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Uudise lisamine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<!-- Kui formile method post lisada, siis ei kuva aadressiribal infot -->
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
		<label>Uudise pealkiri:</label><br>
		<input type="text" name="NewsTitle" placeholder="Uudise pealkiri" value="<?php $NewsTitle; ?>"><br>
		<label>Uudise sisu:</label><br>
		<textarea name="NewsEditor" placeholder="Uudis" rows="6" cols="40" value="<?php $NewsEditor; ?>"></textarea>
		<br>
		<input type="submit" name="NewsBtn" value="Salvesta uudis!"><br>
		<span><?php echo $NewsError; ?></span>
		
	</form>
	<p>Logi <a href="?logout=1">välja!</a></p>
    <p>Tagasi <a href="home.php">avalehele</a>!</p>

</body>
</html>