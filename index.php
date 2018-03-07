<?php
// VARIABLES HEROKU
$host = 'ec2-54-247-95-125.eu-west-1.compute.amazonaws.com' ;
$port = '5432' ;
$dbname = 'd7ook9abfl6f20' ;
$user = 'tqmvkyjwsmndzi' ;
$password = '900e46e58ebfa2201268d4aea8062dad79a33c9961dd820fe75bd933b0173b6c' ;


  //message après l'effacement d'éléments de la DB.
    $notif = '';  
    if (isset($_GET['notif']) && !empty($_GET['notif']))
    {
      $notif = htmlspecialchars($_GET['notif']);
    }

// ESSAI CONNECTION DB
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
    //CHARGER TOUTE LA DB
    $sql = $pdo->query('SELECT * FROM hiking');
    $Error = '';

     //Chargement de tous les éléments de la base de donnée dans une requête préparée.
    $req = $pdo->prepare('SELECT * FROM hiking ORDER BY id DESC');
    $req->execute();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">    
	<meta charset="utf-8">
    <title>Randonnées</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" media="screen and (max-width: 650px)" href="assets/css/media_queries.css" />
  </head>
  <body>
  	<div class="content">
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
						<td><a href="update.php?id=<?php echo $req['id']; ?>"><?php echo $req['name']; ?></a></td>
						<td><?php echo $req['difficulty']; ?></td>
						<td><?php echo $req['distance']; ?></td>
						<td><?php echo $req['duration']; ?></td>
						<td><?php echo $req['height_difference']; ?></td>
						<td class="ss"><input style='pointer-events: none' label='check' type="checkbox" name="randoList[]" value="<?php echo $req['id'] ?>"></td>
					</tr>
					<?php } 
					?>
		    </table>
			<button id="delete" type="submit">Effacer</button>
		</form>
		<?php  
			echo $notif;
		?>
	    <a href="create.php">Create a new entry</a>
	</div>
    <script>
		let ss = document.querySelectorAll('.ss');
    	for (let i = 0; i < ss.length; i++)
    	{
    		let ssInput = ss[i].querySelector('input');
    		ss[i].addEventListener('click', function(){
    			ssInput.checked = ssInput.checked == true ? false : true;
    		});
    	}
    </script>
  </body>
</html>
