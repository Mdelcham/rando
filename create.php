<?php
    $Error = '';
	// CONNECTION DB + Erreur si pas possible 
	try
    	{
    		$pdo = new PDO('mysql:host=localhost;dbname=reunion_island; charset=utf8','root', '');
    	}
    catch (Exception $e)
    	{
      		die('Erreur : ' . $e->getMessage());
    	}

    // VERIFIER SI ENTREE EXISTE OU NON
	if (isset($_POST['name'])&& isset($_POST['difficulty'])&& isset($_POST['distance'])&& isset($_POST['duration'])&& isset($_POST['height_difference']))
	{
		$name = htmlspecialchars($_POST['name']);
		$difficulty = htmlspecialchars($_POST['difficulty']);
		$distance = htmlspecialchars($_POST['distance']);
		$duration = htmlspecialchars($_POST['duration']);
		$height_difference = htmlspecialchars($_POST['height_difference']);

		// Verification de la validité des entrées
		if (filter_var($distance, FILTER_VALIDATE_INT ) === false) 
		{
			$Error .= "L'entrée distance est invalide, veuillez entrer un nombre<BR>";
		}
		if (preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9]):([0-5][0-9])/", $duration) === false) 
		{
			$Error .= "L'entrée durée est invalide, veuillez entrer une heure au format suivant : xx:xx:xx<BR>";
		}
		if (filter_var($height_difference, FILTER_VALIDATE_INT) === false) 
		{
			$Error .= "L'entrée dénivelé est invalide, veuillez entrer un nombre<BR>";
		}

		// Verifier si 'name' n'existe pas deja
		$req = $pdo->prepare('SELECT name FROM hiking');
    	$req->execute();
    	while ($check = $req->fetch())
    	{
      		if ($check['name'] == $_POST['name'])
      		{
	        	$Error = 'Cette entrée existe deja';
      		}
    	}
		// Si pas d'erreur --> Enregistrement dans DB
	    if ($Error == '')
	    {
	    	$req = $pdo->prepare('INSERT INTO hiking(name, difficulty, distance, duration, height_difference) VALUES(:name, :difficulty, :distance, :duration, :height_difference)');
		    $req->execute(array(
		        'name' => $name,
		        'difficulty' => $difficulty,
		        'distance' => $distance,
		        'duration' => $duration,
		    	'height_difference' => $height_difference,
		    ));

		    // MESSAGE DE CONFIRMATION
		    echo "<script type='text/javascript'>";
			echo "alert('Votre parcours a bien été enregistré!');";
			echo "window.location.href='read.php';";
			echo "</script>";
	    }
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajouter une randonnée</title>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/style.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<h1>Ajouter</h1>
	<form action="create.php" method="post">
		<div>
			<label for="name">Name</label>
			<input type="text" name="name" value="">
		</div>

		<div>
			<label for="difficulty">Difficulté</label>
			<select name="difficulty">
				<option value="très facile">Très facile</option>
				<option value="facile">Facile</option>
				<option value="moyen">Moyen</option>
				<option value="difficile">Difficile</option>
				<option value="très difficile">Très difficile</option>
			</select>
		</div>
		
		<div>
			<label for="distance">Distance</label>
			<input type="text" name="distance" value="">
		</div>
		<div>
			<label for="duration">Durée</label>
			<input type="duration" name="duration" value="">
		</div>
		<div>
			<label for="height_difference">Dénivelé</label>
			<input type="text" name="height_difference" value="">
		</div>
		<button type="submit" value="Envoyer">Envoyer</button>
	</form>
	<a href="read.php"> Retour à la liste des données</a>
	<?php 
		echo $Error;
	?>
</body>
</html>
