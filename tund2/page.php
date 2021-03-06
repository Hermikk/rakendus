<?php
	require("../../../../configuration.php");
	require("fnc_news.php");

	$myName = "Mikk Herde";
	$fullTimeNow = date("d.m.Y H.i.s");
	//<p>Lehe avamisel hetkel oli: 31.01.<strong>2020</strong> 11:32:07</p>
	$timeHTML = "\n <p>Lehe avamise hetkel oli: <strong>" .$fullTimeNow."</strong></p> \n";
	$hourNow = date("H");
	$partOfDay = "Hägune aeg";

	if($hourNow < 10){
		$partOfDay = "hommik";
	}
	if($hourNow >= 10 and $hourNow < 18){
		$partOfDay = "aeg aktiivselt tegutseda";
	}
	$partOfDayHTML = "<p>Käes on " .$partOfDay."!</p> \n";
	
	//info semestri kulgemise kohta
	$semesterStart = new DateTime("2020-01-27");
	$semesterEnd = new DateTime("2020-06-22");
	$semesterDuration = $semesterStart->diff($semesterEnd);
	//echo $semesterDuration;
	//var_dump($semesterDuration);
	$today = new DateTime("now");
	$fromSemesterStart = $semesterStart->diff($today);
	//<p>semester on hoos: <meter value="" min="0" max=""></meter></p>
	
	if ($today > $semesterStart and $today < $semesterEnd){
	$semesterProgressHTML = '<p>Semester on hoos:<meter min="0" max="';
	//.= lisab eelmisele reale juurde, kui paneks ainult = märgi, siis anname uue väärtuse
	$semesterProgressHTML .= $semesterDuration->format("%r%a");
	$semesterProgressHTML .= '" value="';
	$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
	$semesterProgressHTML .= '"></meter>.</p>' ."\n";
	}
	elseif ($today < $semesterStart){
		$semesterProgressHTML = "<p>Semester pole alanud.</p>";
	}
	elseif ($today > $semesterEnd){
		$semesterProgressHTML = "<p>Semester on läbi.</p>";
	}
	//loen etteantud kataloogist pildi faili
	$pildidDir = "../../pildid/";
	$photoTypesAllowed = ["image/jpeg", "image/png"];
	$photoList = [];
	$allFiles = array_slice(scandir($pildidDir), 2);
	//var_dump($allFiles);
	foreach($allFiles as $file){
		$fileInfo = getimagesize($pildidDir .$file);
		if(in_array($fileInfo["mime"], $photoTypesAllowed) == true){
			array_push($photoList, $file);
		}
	}
	$photoCount = count($photoList);
	$photoNum = mt_rand(0, $photoCount - 1);
	$randomImageHTML = '<img src="' .$pildidDir .$photoList[$photoNum] .'" alt="juhuslik pilt haapsalust">' ."\n";

	//3 juhuslikku pilti
	$KolmePildiList = [];
	$JuhuslikPildid = "";
	if($photoCount > 0){
		do {
			$JuhuslikPilt = $photoList[mt_rand(0, $photoCount - 1)];
			if(!in_array($JuhuslikPilt, $KolmePildiList)){
				array_push($KolmePildiList, $JuhuslikPilt);
				$JuhuslikPildid .= '<img src="' . $pildidDir . $JuhuslikPilt . '" alt="juhuslik pilt Haapsalust"></img>' . "\n";
			} 
		} 
		while (count($KolmePildiList)<=2);
		} 
	else {
		$JuhuslikPildid = "<p>Kuvamiseks pole ühtegi pilti</p>";
	}

	//Taustapilt kellaaja järgi
	$taust = "#FFFFFF";
	if ($hourNow < 10) {
		$taust = "hommik";
	   }
	if($hourNow >= 10 and $hourNow < 18){
		$taust = "l6una";
		}
	if ($hourNow > 18 and $hourNow < 5){
			$taust = "ohtu";
		}
	
		   
	 $newsHTML = readNewsPage(1);
	
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
	<style>
		.hommik {
			background-color: blue;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			color: gray;
		}
		.l6una {
			background-color: gray;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			color: lightblue;

		}
		.ohtu{
			background-color: greenyellow;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			color: indigo;
		}
		
	
	</style>
</head>
<body class=<?php echo $taust; ?>>
	<h1><?php echo $myName; ?></h1>
	<p>See leht on valminud õppetöö raames!</p>
	<?php
		echo $timeHTML;
		echo $partOfDayHTML;
		echo $semesterProgressHTML;
		
	?>
	<h2>3 juhuslikku pilti</h2>
	<?php
		echo $JuhuslikPildid;
		?>
	<
	<div>
		<?php echo $newsHTML; ?>
	</div>
</body>
</html>