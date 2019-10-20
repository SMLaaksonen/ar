<?php
	//echo "OK";
	if(!isset($_POST['marker']) || !isset($_POST['name']))
	{
		echo "FAIL";
		return;
	}
	$username = $_POST['name'];
	$markerid = $_POST['marker'];
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
		 $sql = "SELECT scantime FROM usersmarkers WHERE user = ? AND marker = ?";
		 $stmt = $pdo->prepare($sql);
		 $stmt->execute([$username,$markerid]);
		 $scantime = $stmt->fetchColumn();
		 if($scantime==FALSE)
		 {
			 $stmt = $pdo->prepare("INSERT INTO usersmarkers (user,marker) VALUES (?,?)");
			 $stmt->execute([$username,$markerid]);
			 $inserted = $stmt->rowCount();
			 if($inserted == 0)
			 {
				 echo "Adding marker scan failed";
				 return;
			 }
			 echo "OK";
		 } else {
			list($date,$time)=explode(' ',$scantime);
			if($date == date("Y-m-d"))
			{
				echo "Already scanned this marker!";
				return;
			} else {
				 $stmt = $pdo->prepare("INSERT INTO usersmarkers (user,marker) VALUES (?,?)");
				 $stmt->execute([$username,$markerid]);
				 $inserted = $stmt->rowCount();
				 if($inserted == 0)
				 {
					 echo "Adding scan marker failed";
					 return;
				 }
				 echo "OK";
			}
		 }
	} catch (\PDOException $e) {
		 echo "Database error";
	}
?>