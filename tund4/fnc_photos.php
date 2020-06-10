<?php

    function photoUploadToDB($fileName, $origName, $altText, $privacy) {
        $response = null;
        //Loon andmebaasi ühenduse
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        //valmistan ette SQL päringu
        $stmt = $conn->prepare("INSERT INTO vr20_photos (userid, filename, origname, alttext, privacy) VALUES (?, ?, ?, ?, ?)");
        echo $conn->error;
        //sean päringuga tegelikud andmed
        //$userid = 1;
        //i on integer, s on string, d on decimal
        $stmt->bind_param("isssi", $_SESSION["userid"], $fileName, $origName, $altText, $privacy);
        if($stmt->execute()){
            $response = 1;
        }else {
            $response = 0;
            echo $stmt->error;
        }
        //sulgen päringu ja andmebaasiühenduse
        $stmt->close();
        $conn->close();
        return $response;
        
    }

    function photoSize($myTempImage){
        $maxWidth = 600;
        $maxHeight = 400;
    
        $imageW = imagesx($myTempImage);
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
        imagecopyresampled($myNewImage, $myTempImage, 0, 0, 0, 0, $newW, $newH, $imageW, $imageH);
        
        return $myNewImage;

    }

    function thumbnail($myTempImage){
        $maxWidth = 100;
        $maxHeight = 100;
    
        $imageW = imagesx($myTempImage);
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
        imagecopyresampled($myNewImage, $myTempImage, 100, 100, 100, 100, $newW, $newH, $imageW, $imageH);
        
        return $myNewImage;
    }
