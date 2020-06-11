<?php
	function readAllMyPictureThumbs(){
		$privacy = 3;
		$finalHTML = "";
        $html = "";
        $normalPhotoDir = "../../uploadNormalPhoto/";
        $thumbnailDir = "../../uploadThumbnail/";
    
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE userid=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->bind_result($filenameFromDb, $altFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<a href="' .$normalPhotoDir .$filenameFromDb .'" target="_blank"><img src="' .$thumbnailDir .$filenameFromDb .'" alt="'.$altFromDb .'"></a>' ."\n \t \t";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		
		$stmt->close();
		$conn->close();
		return $finalHTML;
	}
	
	function readAllSemiPublicPictureThumbs(){
		$privacy = 2;
		$finalHTML = "";
        $html = "";
        $normalPhotoDir = "../../uploadNormalPhoto/";
        $thumbnailDir = "../../uploadThumbnail/";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $altFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<a href="' .$normalPhotoDir .$filenameFromDb .'" target="_blank"><img src="' .$thumbnailDir .$filenameFromDb .'" alt="'.$altFromDb .'"></a>' ."\n \t \t";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $finalHTML;
     }
     function readAllPublicPictureThumbs(){
		$privacy = 1;
		$finalHTML = "";
        $html = "";
        $normalPhotoDir = "../../uploadNormalPhoto/";
        $thumbnailDir = "../../uploadThumbnail/";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $altFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<div class="galleryelement">' ."\n";
			$html .= "<a href=".$GLOBALS["normalPhotoDir"] .$filenameFromDb ." target='_blank'><img src=" .$GLOBALS["thumbnailDir"] .$filenameFromDb ."></a><br>";
			$html .= "</div> \n \t \t";		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $finalHTML;
     }
     
     function countPics($privacy){
        $notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($count);
		$stmt->execute();
		$stmt->fetch();
		$notice = $count;
		
		$stmt->close();
		$conn->close();
		return $notice;
    }
    
    function countPrivatePics(){
		$notice = null;
        $privacy = 3;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("ii", $privacy, $_SESSION["userid"]);
		$stmt->bind_result($count);
		$stmt->execute();
		$stmt->fetch();
		$notice = $count;
		
		$stmt->close();
		$conn->close();
		return $notice;
    }
	function readAllMyPictureThumbsPage($privacy, $page, $limit){
			
		$privacy = 3;
		$skip = ($page-1)*$limit;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("
		SELECT
		vr20_photos.id,
		vr20_photos.filename,
		vr20_users.firstname,
		vr20_users.lastname,
		vr20_photos.alttext,
		AVG(vr20_photoratings.rating) as AvgValue
		FROM vr20_photos
		JOIN vr20_users ON vr20_photos.userid = vr20_users.id
		LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
		WHERE vr20_photos.privacy <= ? AND vr20_photos.deleted IS NULL AND vr20_photos.userid=?
		GROUP BY vr20_photos.id DESC LIMIT ?, ?");
		echo $conn->error;
		$user = $_SESSION["userid"];
		$stmt->bind_param("iiii", $privacy, $user, $skip, $limit);
		$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<div class="galleryelement">'."\n";
			$html .= '<img src="' .$GLOBALS["thumbnailDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
			$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
			$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
			$html .= "</div>\n";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
	
		$stmt->close();
		$conn->close();
		return $finalHTML;
	}

	function readAllSemiPublicPictureThumbsPage($page, $limit){
		$privacy = 2;
		$skip = ($page-1)*$limit;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("
		SELECT
		vr20_photos.id,
		vr20_photos.filename,
		vr20_users.firstname,
		vr20_users.lastname,
		vr20_photos.alttext,
		AVG(vr20_photoratings.rating) as AvgValue
		FROM vr20_photos
		JOIN vr20_users ON vr20_photos.userid = vr20_users.id
		LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
		WHERE vr20_photos.privacy <= ? AND deleted IS NULL
		GROUP BY vr20_photos.id DESC LIMIT ?, ?");
		echo $conn->error;
		$stmt->bind_param("iii", $privacy, $skip, $limit);
		$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<div class="galleryelement">'."\n";
			$html .= '<img src="' .$GLOBALS["thumbnailDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
			$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
			$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
			$html .= "</div>\n";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}

		$stmt->close();
		$conn->close();
		return $finalHTML;
	}
