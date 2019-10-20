<?php
	function showError($title,$subtitle="",$message)
	{
		echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error | Marker master</title>";
		echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/siimple@3.3.1/dist/siimple.min.css'>";
		echo "</head><body>";
		echo "<div class='siimple-box siimple-box--orange'><div class='siimple-box-title'>" . $title . "</div>";
		echo "<div class='siimple-box-subtitle'>" . $subtitle . "</div>";
		echo "<div class='siimple-box-detail'>" . $message . "</div>";
		echo "</body></html>";
		
	}
	if(!isset($_POST['ar_name']))
	{
		showError("Error", "Well, if droids could think, there’d be none of us here, would there? - Obi-Wan Kenobi", "Go back must you: <a href='index.html'>HOME</a>");
		return;
	}
	$arname = $_POST['ar_name'];
	$host = '127.0.0.1';
	$db   = 'argame';
	$user = 'root';
	$pass = '';
	$charset = 'utf8mb4';

	$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
	$options = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	try {
		 $pdo = new PDO($dsn, $user, $pass, $options);
		 $sql = "SELECT username FROM users WHERE username = ?";
		 $stmt = $pdo->prepare($sql);
		 $stmt->execute([$arname]);
		 $name = $stmt->fetchColumn();
		 if($name == FALSE)
		 {
			 $stmt = $pdo->prepare("INSERT INTO users (username) VALUES (?)");
			 $stmt->execute([$arname]);
			 $inserted = $stmt->rowCount();
			 if($inserted == 0)
			 {
				 showError("DatabaseError","Never tell me the odds! — Han Solo","Problem with username! Go back must you: <a href='index.html'>HOME</a>");
				 return;
			 }
		 }
		 $name = $arname;
		 // echo ar.js page
		 echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Marker master</title>";
		 echo "<script src='https://aframe.io/releases/0.8.0/aframe.min.js'></script>";
		 echo "<script src='https://cdn.rawgit.com/jeromeetienne/AR.js/1.6.0/aframe/build/aframe-ar.js'></script>";
		 echo "<script>function gotoHome(){ location.assign('index.html'); } </script>";
		 
		 echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/siimple@3.3.1/dist/siimple.min.css'></head>";
		 echo "<body style='margin : 0px; overflow: hidden;'>";

		 echo "<a-scene class='camera_style' embedded arjs='sourceType: webcam; debugUIEnabled: false;'>";
		 //echo "<a-marker-camera id='ahiro' preset='hiro'></a-marker-camera>";
		 // echo all markers
		 $stmt = $pdo->query('SELECT markerid,markername,markerimage,markerprimitive,primitivecolor FROM markers WHERE now()>starttime AND endtime>now()');
		foreach ($stmt as $row)
		{
			echo "<a-marker id='" . $row['markername'] . "_" . $row['markerid'] . "' preset='custom' url='markers/" . 
			$row['markerimage'] . "' registerevents>";
			echo "<" . $row['markerprimitive'] . " position='0 0.5 0' material='opacity: 0.5;' color='" . 
			$row['primitivecolor'] . "'></" . $row['markerprimitive'] . ">";
			echo "</a-marker>";
		}
		// end echo all markers
		 echo "<a-entity camera></a-entity></a-scene>";
		 echo "<div id='name' class='siimple-navbar siimple--bg-dark siimple--color-white' style='position:absolute;left:0;top:0;width:100%;'><div class='siimple-navbar-title' onclick='gotoHome();'>";
		 echo $name;
		 echo "</div></div>";
		 echo "<script src='loginjs.js'></script>";
		 echo "</body></html>";

	} catch (\PDOException $e) {
		 showError("PDOError","'Do. Or do not. There is no try.' — Yoda", "Database error! Go back must you: <a href='index.html'>HOME</a>");
		 return;
	}	
?>