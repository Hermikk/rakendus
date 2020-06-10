<?php
	require("../../../../configuration.php");
	require("fnc_news.php");
	
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
</body>
</html>