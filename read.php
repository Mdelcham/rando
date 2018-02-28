<?php
	$pdo = new PDO('mysql:host=localhost;dbname=reunion_island; charset=utf8','root', '');
	$sql = $pdo->query('SELECT * FROM hiking');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Randonnées</title>
    <link rel="stylesheet" href="css/basics.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <h1>Liste des randonnées</h1>
    <table>
	    	<tr>
	    		<th>Nom</th>
	    		<th>Difficulté</th>
	    		<th>Distance<small> (en km)</small></th>
	    		<th>Durée</th>
	    		<th>Dénivelé<small> (en m)</small></th>
	    	</tr>
		<?php 
			while ($req = $sql->fetch())
			{
			?>
			<tr>
				<td><?php echo $req['name']; ?></td>
				<td><?php echo $req['difficulty']; ?></td>
				<td><?php echo $req['distance']; ?></td>
				<td><?php echo $req['duration']; ?></td>
				<td><?php echo $req['height_difference']; ?></td>
			</tr>
			<?php } ?>
    </table>
    <a href="create.php">Create a new entry</a>
  </body>
</html>
