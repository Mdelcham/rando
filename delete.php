<?php
// VARIABLES HEROKU
$host = 'ec2-54-247-95-125.eu-west-1.compute.amazonaws.com' ;
$port = '5432' ;
$dbname = 'd7ook9abfl6f20' ;
$user = 'tqmvkyjwsmndzi' ;
$password = '900e46e58ebfa2201268d4aea8062dad79a33c9961dd820fe75bd933b0173b6c' ;
	$notif = '';
  	if (isset($_POST['randoList']) && !empty($_POST['randoList']))
  	{
		$parcours = $_POST['randoList'];
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
	header("Location: index.php?notif=".$notif."");

?>