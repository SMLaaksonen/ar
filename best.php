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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Best of Marker master</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/siimple@3.3.1/dist/siimple.min.css">
	</head>
	<body>
		<div class="siimple-grid">
			<div class="siimple-grid-row">
				<div class="siimple-grid-col siimple-grid-col--12 siimple-grid-col--sm-12">
					<div class="siimple-box--large siimple--bg-dark siimple--color-white siimple--text-center siimple--rounded">
						<div class="siimple-box-title">Marker master
												<br>
						<a href="index.html">
						<img style="text-align:center;" src="spaceinvadermarker.jpg">
						</a>
						</div>
						<div class="siimple-box-subtitle">Best of scanners</div>
					</div>
				</div>
			</div>
			<div class="siimple-grid-row">
				<div class="siimple-grid-col siimple-grid-col--12 siimple-grid-col--sm-12">
					<div class="siimple-table siimple--table--border">
						<div class="siimple-table-header">
							<div class="siimple-table-row">
								<div class="siimple-table-cell">Name of scanner</div>
								<div class="siimple-table-cell">No. of scans</div>
							</div>
						</div>
						<div class="siimple-table-body">				
<?php
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
		// SELECT count(*), user FROM usersmarkers GROUP BY user HAVING COUNT(*) > 1
		$found = 0;
		$stmt = $pdo->query('SELECT count(*) AS maara, user FROM usersmarkers GROUP BY user HAVING COUNT(*) >= 1');
		while ($row = $stmt->fetch())
		{
			$found++;
			echo "<div class='siimple-table-row'>";
			echo "<div class='siimple-table-cell'>" . $row['user'] . "</div>";
			echo "<div class='siimple-table-cell'>" . $row['maara'] . "</div>";
			echo "</div>";
		}
		if($found==0)
		{
			echo "<div class='siimple-table-row'><div class='siimple-table-cell'>";
			echo "Where are all collectors?</div><div class='siimple-table-cell'>No-one has scanned markers yet.</div></div>";
		}
	} catch (\PDOException $e) {
		 showError("Database error","","Problem have we with database. Jedis are working on it. May the force be with you");
	}
?>
</div></div></div></div></div></div></body></html>