<?php
  //message après l'effacement d'éléments de la DB.
    $notif = '';  
    if (isset($_GET['notif']) && !empty($_GET['notif']))
    {
      $notif = htmlspecialchars($_GET['notif']);
    }

// ESSAI CONNECTION DB
	try
    	{
    		$pdo = new PDO('mysql:host=localhost;dbname=reunion_island; charset=utf8','root', '');
    	}
    catch (Exception $e)
    	{
      		die('Erreur : ' . $e->getMessage());
    	}
    //CHARGER TOUTE LA DB
    $sql = $pdo->query('SELECT * FROM hiking');
    $Error = '';

     //Chargement de tous les éléments de la base de donnée dans une requête préparée.
    $req = $pdo->prepare('SELECT * FROM hiking ORDER BY id DESC');
    $req->execute();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Randonnées</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <h1>Liste des randonnées</h1>
    <form action="delete.php" method="post">
	    <table>
		    	<tr>
		    		<th>Nom</th>
		    		<th>Difficulté</th>
		    		<th>Distance<small> (en km)</small></th>
		    		<th>Durée</th>
		    		<th>Dénivelé<small> (en m)</small></th>
		    		<th>Effacer</th>
		    	</tr>
			<?php 
				while ($req = $sql->fetch())
				{
				?>
				<tr>
					<td> <a href="update.php?id=<?php echo $req['id']; ?>"><?php echo $req['name']; ?></a></td>
					<td><?php echo $req['difficulty']; ?></td>
					<td><?php echo $req['distance']; ?></td>
					<td><?php echo $req['duration']; ?></td>
					<td><?php echo $req['height_difference']; ?></td>
					<td><input type="checkbox" name="randoList[]" value="<?php echo $req['id'] ?>"></td>
				</tr>
				<?php } 
				?>
	    </table>
	    <input type="submit" value="effacer">
	</form>
	<?php  
		echo $notif;
	?>
    <a href="create.php">Create a new entry</a>
  </body>
</html>
