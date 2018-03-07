<?php
// VARIABLES HEROKU
$host = 'ec2-54-247-95-125.eu-west-1.compute.amazonaws.com' ;
$port = '5432' ;
$dbname = 'd7ook9abfl6f20' ;
$user = 'tqmvkyjwsmndzi' ;
$password = '900e46e58ebfa2201268d4aea8062dad79a33c9961dd820fe75bd933b0173b6c' ;
    $Error = '';
	// CONNECTION DB + Erreur si pas possible 
	try
    	{
    		// VERSION MYSQL
    		// $pdo = new PDO('mysql:host=localhost;dbname=reunion_island; charset=utf8','root', 'root');

    		// VERSION HEROKU POSTGRESS
    		$pdo = new PDO('pgsql:host='.$host.';port='.$port.';dbname='.$dbname.';user='.$user.';password='.$password.'');
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
		if (filter_var($distance, FILTER_VALIDATE_FLOAT ) === false) 
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
			echo "window.location.href='index.php';";
			echo "</script>";
	    }
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajouter une randonnée</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/style.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<div class="content_up_cr">
		<h1>Ajouter une nouvelle randonnée</h1>
		<form class="form_crea" action="create.php" method="post">
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
		<a href="index.php"> Retour à la liste des données</a>
		<?php 
			echo $Error;
		?>
	</div>
</body>
</html>
