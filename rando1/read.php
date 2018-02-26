<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Reunion Island</title>
</head>
<body>
	<?php 


		$pdo = new PDO('mysql:host=localhost;dbname=reunion_island; charset=utf8','root', '');
		$sql = $pdo->query('SELECT * FROM hiking');
		while ($req = $sql->fetch())
		{
			echo $req['name'];
			echo $req['difficulty'];
			echo $req['distance'];
			echo $req['duration'];
		}

	?>
</body>
</html>