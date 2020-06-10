<?php
	require("../../../../configuration.php");
	require("fnc_photos.php");

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

    //pildi üleslaadimise osa

    //var_dump($_POST); //siin on kogu muu kraam
    //var_dump($_FILES); // siin on üleslaetavad failid

    $originalPhotoDir = "../../uploadOriginalPhoto/";
    $normalPhotoDir = "../../uploadNormalPhoto/";
    $thumbnailDir = "../../uploadThumbnail/";
    $error = null;
    $notice = null;
    $imageFileType = null;
    $fileUploadSizeLimit = 1048576;
    $fileNamePrefix = "vr_";
    $fileName = null;
    $maxWidth = 600;
    $maxHeight = 400;

    if(isset($_POST["photoSubmit"])){

        //kas üldse on pilt?
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {     //!== ei võrdu
            //failitüübi väljaselgitamine ja sobivuse kontroll
            if($check["mime"] == "image/jpeg"){
                $imageFileType = "jpg";
            } elseif ($check["mime"] == "image/png"){
                $imageFileType = "png";
            }    else {
                    $error = "Ainult jpg ja png pildid on lubatud!";
            }
            
        } else {
            $error = "Valitud fail ei ole pilt! ";
        }

        //Ega fail pole liiga suur
        if($_FILES["fileToUpload"]["size"] > $fileUploadSizeLimit){
            $error .= "Valitud fail on liiga suur! ";
        }

        //loome oma nime failile
        $timestamp = microtime(1) * 10000;
        $fileName = $fileNamePrefix . $timestamp . "." .$imageFileType;

        //$originalTarget = $originalPhotoDir .$_FILES["fileToUpload"]["name"];
        $originalTarget = $originalPhotoDir .$fileName;
        
        //äkki on fail juba olemas?
        if(file_exists($originalTarget)){
            $error .= "Selline fail on juba olemas!";
        }

        //kui vigu pole
        if($error == null){ 
            //teen pildi väiksemaks

            if($imageFileType == "jpg"){
                $myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
            }
            if($imageFileType == "png"){
                $myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
            } 

            //Järgneva kommentaari tõstsin funktsiooni!
            /* $imageW = imagesx($myTempImage);
            $imageH = imagesy($myTempImage);

            if($imageW / $maxWidth > $imageH / $maxHeight) {
                $imageSizeRatio = $imageW / $maxWidth;
            } else {
                $imageSizeRatio = $imageH / $maxHeight;
            }
            $newW = round($imageW / $imageSizeRatio);
            $newH = round($imageH / $imageSizeRatio);
            //loome uue ajutise pildiobjekti
            $myNewImage = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($myNewImage, $myTempImage, 0, 0, 0, 0, $newW, $newH, $imageW, $imageH); */

            $myNewImage = photoSize($myTempImage);

            //salvestame vähendatud kujutise faili
            if($imageFileType == "jpg"){
                if(imagejpeg($myNewImage, $normalPhotoDir .$fileName, 90)){
                    $notice .= "Vähendatud pilt laeti üles!";
                } else {
                    $error .= "Vähendatud pildi salvestamisel tekkis viga!";
                }
            }
            if($imageFileType == "png"){
                if(imagepng($myNewImage, $normalPhotoDir .$fileName, 6)){ 
                    $notice .= "Vähendatud pilt laeti üles!";
                } else {
                    $error .= "Vähendatud pildi salvestamisel tekkis viga!";
                }
            }

            $myNewThumbnail = thumbnail($myTempImage);
            //Salvestame thumbnaili
            if($imageFileType == "jpg"){
                if(imagejpeg($myNewThumbnail, $thumbnailDir .$fileName, 90)){
                    $notice .= "Pisipilt laeti üles!";
                } else {
                    $error .= "Pisipildi salvestamisel tekkis viga!";
                }
            }
            if($imageFileType == "png"){
                if(imagepng($myNewThumbnail, $thumbnailDir .$fileName, 6)){ 
                    $notice .= "Pisipilt laeti üles!";
                } else {
                    $error .= "Pisipildi salvestamisel tekkis viga!";
                }
            }

            if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)){;
                $notice .= "Originaalpilt laeti üles!";         //punkt võrduse ees tähendab, et see lisatakse juurde eelnevale sama nimega teatele!
            } else {
                $error .= "Pildi üleslaadimisel tekkis viga!";
            }

            

            imagedestroy($myTempImage);
            imagedestroy($myNewImage);

            //andmebaasi salvestamine!!!
            if($error == null){
                //echo "Salvestame!";
                $response = photoUploadToDB($fileName, $_FILES["fileToUpload"]["name"], $_POST["altText"], $_POST["privacy"]);
                if($response == 1){
                    $notice .= "Pildiinfo on salvestatud!";
                }else {
                    $error .= "Pildiinfo salvestamisel tekkis viga!";
                }
            }
            
            

            

        }  
    }


?>
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Fotode üleslaadimine</h1>
    <p>See leht on valminud õppetöö raames!</p>
    <hr>
	<!-- Kui formile method post lisada, siis ei kuva aadressiribal infot -->
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data"> <!--enctype osa tuleb lisada, kui tahad, et form ka faile üles laeks! -->
		<label>Vali pildifail:</label><br>
        <input type="file" name="fileToUpload"><br>
        <label>Alt tekst: </label><input type="text" name="altText"><br>
        <input type="submit" name="photoSubmit" value="Lae valitud pilt üles!"><br>
        <label>Privaatsus</label><br>
        <label for="priv1">Privaatne</label><input id="priv1" type="radio" name="privacy" value="3" checked><br>
        <label for="priv2">Sisseloginud kasutajale</label><input id="priv2" type="radio" name="privacy" value="2"><br>
        <label for="priv3">Avalik</label><input id="priv3" type="radio" name="privacy" value="1"><br>

		<span><?php echo $error; echo $notice; ?></span>
        

    </form>
    <hr>
    <p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"]. ""; ?><p>Logi <a href="?logout=1">välja!</a></p>
    <p>Tagasi <a href="home.php">avalehele</a>!</p>
</body>
</html>