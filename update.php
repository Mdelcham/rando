<?php 
	// DECLARATION VARIABLES 
	if (!isset($_GET['id']))
	{
		$id = '';
	}
	else
	{
		$id = htmlspecialchars($_GET['id']);
	}
	$name = '';
	$difficulty = '';
	$distance = '';
	$duration = '';
	$height_difference = '';

	// LISTE DEROULANTE DE DIFFICULTE
	$tres_facile = '';
	$facile = '';
	$moyen = '';
	$difficile = '';
	$tres_difficile = '';

	// SI ERREUR
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
		if (filter_var($distance, FILTER_VALIDATE_FLOAT ) === false) 
		{
			$Error .= "L'entrée distance est invalide, veuillez entrer un nombre <BR>";
		}
		if (preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9]):([0-5][0-9])/", $duration) === false) 
		{
			$Error .= "L'entrée durée est invalide, veuillez entrer une heure au format suivant : xx:xx:xx <BR>";
		}
		if (filter_var($height_difference, FILTER_VALIDATE_INT) === false) 
		{
			$Error .= "L'entrée dénivelé est invalide, veuillez entrer un nombre <BR>";
		}

		// Verifier si 'name' n'existe pas deja
		$req = $pdo->prepare('SELECT name, id FROM hiking');
    	$req->execute();
    	while ($check = $req->fetch())
    	{
      		if ($check['name'] == $_POST['name'] && $check['id'] != $id)
      		{
	        	$Error = 'Cette entrée existe deja';
      		}
    	}
		// Si pas d'erreur --> Remplacement dans DB
	    if ($Error == '')
	    {
	    	$req = $pdo->prepare('UPDATE hiking SET name = :name, difficulty = :difficulty, distance = :distance, duration = :duration, height_difference = :height_difference WHERE id = '.$id.'');
		    $req->execute(array(
		        'name' => $name,
		        'difficulty' => $difficulty,
		        'distance' => $distance,
		        'duration' => $duration,
		    	'height_difference' => $height_difference,
		    ));
		    // MESSAGE DE CONFIRMATION
		    echo "<script type='text/javascript'>";
			echo "alert('Votre modification a bien été enregistrée');";
			echo "window.location.href='read.php';";
			echo "</script>";
	    }
	}

	// MODIFICATION ENTREE
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$id = htmlspecialchars($_GET['id']);
		$answer = $pdo->query('SELECT * FROM hiking WHERE id='.$id.'');
	   	$data = $answer->fetch();
	   	$name = $data['name'];
	   	$difficulty = $data['difficulty'];
	   	$distance = $data['distance'];
	   	$duration = $data['duration'];
	   	$height_difference = $data['height_difference'];

	}
	switch ($difficulty) {
		case 'très facile':
			$tres_facile = 'SELECTED';
			break;
		
		case 'facile':
			$facile = 'SELECTED';			
			break;

		case 'moyen':
			$moyen = 'SELECTED';
			break;

		case 'difficile':
			$difficile = 'SELECTED';
			break;

		case 'très difficile':
			$tres_difficile = 'SELECTED';
			break;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Modifier une randonnée</title>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/style.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
	<a href="read.php">Liste des données</a>
	<h1>Modifier une entrée</h1>
	<form action="update.php?id=<?=$id?>" method="post">
		<div>
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<label for="name">Name</label>
			<input type="text" name="name" value="<?php echo $name ?>">
		</div>

		<div>
			<label for="difficulty">Difficulté</label>
			<select name="difficulty">
				<option value="très facile" <?php echo $tres_facile; ?> >Très facile</option>
				<option value="facile" <?php echo $facile; ?> >Facile</option>
				<option value="moyen" <?php echo $moyen; ?> >Moyen</option>
				<option value="difficile" <?php echo $difficile; ?> >Difficile</option>
				<option value="très difficile" <?php echo $tres_difficile; ?> >Très difficile</option>
			</select>
		</div>
		
		<div>
			<label for="distance">Distance</label>
			<input type="text" name="distance" value="<?php echo $distance ?>">
		</div>
		<div>
			<label for="duration">Durée</label>
			<input type="duration" name="duration" value="<?php echo $duration ?>">
		</div>
		<div>
			<label for="height_difference">Dénivelé</label>
			<input type="text" name="height_difference" value="<?php echo $height_difference ?>">
		</div>
		<button type="submit" name="button">Enregistrer la modification</button>
	</form>
	<?php echo $Error ?>
</body>
</html>
