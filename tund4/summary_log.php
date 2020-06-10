<?php
require ("../../../../configuration.php");
require ("fnc_news.php");

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


$summaryHTML = readLogsAll();
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Õppelogi</title>
</head>
<body>
    <h1>Õppelogi</h1>
<?php echo $summaryHTML; ?>
<hr>
<p>Logi <a href="?logout=1">välja!</a></p>
</body>
</html>