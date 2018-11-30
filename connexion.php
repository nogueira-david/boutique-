<?php 
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Connexion</title>
</head>
<body>
	<?php 
	require_once('inc/bdd.php');

	if(!empty($_POST)){

		$errors = [];
		$post = [];

		foreach($_POST as $key => $value){
			$post[$key] = strip_tags(trim($value));
		}

		if( empty($post['email']) ){
			$errors[] = 'email Incorrect';
		}

		if(empty($post['password']) || mb_strlen($post['password'])<4 ||  mb_strlen($post['password'])>10 ){
			$errors[] = 'mot de passe Incorrect';
		}

		if (!empty($errors) && $post['email'] != $_SESSION['email']){
			echo implode ($errors);
			echo '<a href=index.php>Index</a>';

		} else {

			$result = $connexion->prepare('SELECT email, password, role FROM users WHERE email = :email');
			$result->bindValue(':email', $post['email']);
			$result->execute();
			$user = $result->fetchAll();
			if (count($user) === 1 && password_verify($post['password'], $user[0]['password'])){
				echo 'Vous etes connécté!';
				echo '<a href="deconnexion.php"><button>Deconnection</button></a>';
				echo '<a href="index.php"><button>Index</button></a>';
				$_SESSION['email'] = $user[0]['email'];
				$_SESSION['mdp'] = $user[0]['password'];
				$_SESSION['role'] = $user[0]['role'];
				var_dump($_SESSION);

			} else {echo 'erreur connexion';}
		}
	} else {
		// header('location:index.php');}
		echo 'Erreur de connexion: formulaire de connexion vide';
	}
	?>

</body>
</html>