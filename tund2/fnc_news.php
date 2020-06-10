<?php
    function SaveNews($NewsTitle, $NewsContent){
        $response = null;
        //Loon andmebaasi ühenduse
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        //valmistan ette SQL päringu
        $stmt = $conn->prepare("INSERT INTO vr20_news (userid, title, content) VALUES (?, ?, ?)");
        echo $conn->error;
        //sean päringuga tegelikud andmed
        $userid = 1;
        //i on integer, s on string, d on decimal
        $stmt->bind_param("iss", $userid, $NewsTitle, $NewsContent);
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

    function readNewsPage($limit){
		if($limit == null){
			$limit = 1;
		}
		$response = null;
		//loon andmebaasiühenduse
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content FROM vr20_news");
		$stmt = $conn->prepare("SELECT title, content, created FROM vr20_news WHERE deleted IS NULL ORDER BY id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("i", $limit);
		$stmt->bind_result($titleFromDB, $contentFromDB, $createdFromDB);
		$stmt->execute();
		//if($stmt->fetch())
		//<h2>uudisepealkiri</h2>
		//<p>uudis</p>
		while ($stmt->fetch()){
			$addedDate = new DateTime($createdFromDB);
			$response .= "<h3>" .$titleFromDB ."</h3> \n";
			$response .= "<p>Lisatud: " .$addedDate->format("d.m.Y H:i:s") ."</p> \n";
			$response .= "<p>" .$contentFromDB ."</p> \n";
		}
		if($response == null){
			$response = "<p>Kahjuks uudised puuduvad!</p> \n";
		}
		
		//sulgen päringu ja andmebaasiühenduse
		$stmt->close();
		$conn->close();
		return $response;
	}

    function ReadNews(){
        $response = null;
        //Loon andmebaasi ühenduse
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        //SELECT järel * pannes, siis võtab kõik teemad andmebaasist, praegu valisime mida valida title ja content näol
        $stmt = $conn->prepare("SELECT title, content FROM vr20_news");
        echo $conn->error;
        $stmt->bind_result($TitleFromDB, $ContentFromDB);
        $stmt->execute();
        //if($stmt->fetch()) , fetch võtab ühekaupa
        //<h2>uudisepealkiri</h2>
        //<p>uudis</p>
        while ($stmt->fetch()){
            $response .= "<h2>" .$TitleFromDB ."</h> \n";
            $response .= "<p>" .$ContentFromDB ."</p> \n";
        }
        if($response == null){
            $response = "<p>Kahjuks uudised puuduvad!</p> \n";
        }

        //sulgen päringu ja andmebaasiühenduse
        $stmt->close();
        $conn->close();
        return $response;
    }

    function clean_inputs($input) {
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}

	function courseSelect(){
		if (isset($_POST["courseName"])) {
		$courseInput = $_POST["courseName"];
		} else {
			$courseInput = 0;
	
		}
		$response = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("SELECT id, course_name FROM vr20_studylog_courses");
		echo $conn->error;
		$stmt->bind_result($courseIdFromDB, $CourseNameFromDB);
		$stmt->execute();
		$selectionAttribute='';
		while ($stmt->fetch()){
			if ($courseInput==$courseIdFromDB) {  
				$response .= "<option selected value=" . $courseIdFromDB. ">" . $CourseNameFromDB . "</option>\n";  
			} else {
				$response .= "<option value=" . $courseIdFromDB. ">" . $CourseNameFromDB . "</option>\n";  
	
			} 
		}
		
		$stmt->close();
		$conn->close();
		return $response;
		
	}

	function activitySelect(){
		if (isset($_POST["activityType"])) {
		$activityInput = $_POST["activityType"];
		} else {
			$activityInput = 0;
	
		}
		$response = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("SELECT id, course_activitytype FROM vr20_studylog_activitytype");
		echo $conn->error;
		$stmt->bind_result($activityIdFromDB, $activityTypeFromDB);
		$stmt->execute();
		$selectionAttribute='';
		while ($stmt->fetch()){
			if ($activityInput==$activityIdFromDB) {  
				$response .= "<option selected value=" . $activityIdFromDB. ">" . $activityTypeFromDB . "</option>\n";  
			} else {
				$response .= "<option value=" . $activityIdFromDB. ">" . $activityTypeFromDB . "</option>\n";  
	
			} 
		}
		
		$stmt->close();
		$conn->close();
		return $response;
		
	}

	function saveActivity($courseName, $activityType, $elapsedTime){
		$response = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("INSERT INTO vr20_studylog (course, activity, time) values (?,?,?)");
		echo $conn->error;
		$user_id = 1;
		$stmt->bind_param('iid', $courseName, $activityType, $elapsedTime);
		if ($stmt->execute()) {
		$response = 1;
		
		} else {
			$response = 0;
			echo $stmt->error;  
		}
		$stmt->close();
		$conn->close();
		return $response;
		
	}

	function readLogsLatest() {
		$response = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("SELECT vr20_studylog.time, vr20_studylog.day, vr20_studylog_courses.course_name, vr20_studylog_activitytype.course_activitytype FROM vr20_studylog LEFT JOIN vr20_studylog_courses ON vr20_studylog.course=vr20_studylog_courses.id LEFT JOIN vr20_studylog_activitytype ON vr20_studylog.activity=vr20_studylog_activitytype.id ORDER BY vr20_studylog.id DESC LIMIT 3");
		echo $conn->error;
		$stmt->bind_result($timeFromDB, $timeAddedFromDB, $courseNameFromDB, $activityTypeFromDB);
		$stmt->execute();
		while($stmt->fetch()) {
			$response .= '<p>' .$courseNameFromDB. "---" .$activityTypeFromDB. "---" .$timeFromDB. "---" .$timeAddedFromDB.'</p><br>';
			if($response == null) {
				$response = "<p>Kahjuks andmed puuduvad</p> \n";
			}
		}
		$stmt->close();
		$conn->close();
		return $response;
	}

	function readLogsAll() {
		$response = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("SELECT vr20_studylog_activitytype.course_activitytype, SUM(vr20_studylog.time), vr20_studylog_courses.course_name FROM vr20_studylog LEFT JOIN vr20_studylog_courses ON vr20_studylog.course=vr20_studylog_courses.id LEFT JOIN vr20_studylog_activitytype ON vr20_studylog.activity=vr20_studylog_activitytype.id GROUP BY vr20_studylog_courses.course_name, vr20_studylog_activitytype.course_activitytype ORDER BY vr20_studylog_courses.course_name");
		echo $conn->error;
		$stmt->bind_result($activityTypeFromDB, $timeFromDB, $courseNameFromDB);
		$stmt->execute();
		$response .= '<table border=2><tr><td>Aine nimetus</td><td>Õppimise tüüp</td><td>Ajakulu tundides</td></tr>';
		while($stmt->fetch()) {
			$response .= '<tr><td>' .$courseNameFromDB. '</td><td>' .$activityTypeFromDB. '</td><td>' .$timeFromDB. '</td></tr>';
			if($response == null) {
				$response = "<p>Kahjuks andmed puuduvad</p>";
			}
		}
		$stmt->close();
		$conn->close();
		return $response;
	}