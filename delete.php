<?php
	$notif = '';
  	if (isset($_POST['randoList']) && !empty($_POST['randoList']))
  	{
		$parcours = $_POST['randoList'];
  		try
	  	{
	      	$pdo = new PDO('mysql:host=localhost;dbname=reunion_island;charset=utf8', 'root', 'root');
	    }
	  	catch (Exception $e)
	  	{
	    	die('Erreur : ' . $e->getMessage());
	  	}
	  	$req = $pdo->prepare('DELETE FROM hiking WHERE id = ?');
	  	for ($i = 0; $i < count($parcours); $i++)
	  	{
	  		$parcours[$i] = htmlspecialchars($parcours[$i]);
	  		$req->execute(array($parcours[$i]));
		}
		if (count($parcours) > 1)
		{
			$notif = 'Parcours effacé';
		}
		else
		{
			$notif = 'Parcours effacés';			
		}
        $req = NULL;
	}
	header("Location: read.php?notif=".$notif."");

?>