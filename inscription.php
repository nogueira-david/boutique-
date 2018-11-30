<?php 
require_once('inc/bdd.php');

if(!empty($_POST)){
                            //formulaire d'ajout envoyé
	$post = [];

	foreach($_POST as $key => $value){
		$post[$key] = strip_tags(trim($value));
	}

	$errors = [];

	if(empty($post['pseudo']) || !preg_match("#^\w{4,10}$#", $post['pseudo'])){
		$errors[] = 'nom invalide';
	}

	if(empty($post['password']) || !preg_match("#^\w{4,10}$#", $post['password']) || empty($post['password2']) || $post['password'] !== $post['password2']){
		$errors[] = 'mdp invalide ou différents';
	}

	if(empty($post['email']) || !filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
		$errors[] = 'email invalide';
	}

                            //on vérifie que l'email n'existe pas déjà
	$select = $connexion->prepare('SELECT id_users FROM users WHERE email = :email');
	$select->bindValue(':email', $post['email']);
	$select->execute();
	$users = $select->fetchAll();
	if(count($users) > 0){
		$errors[] = 'email déjà présent';
	}

	if(empty($errors)){

		$insert = $connexion->prepare('INSERT INTO users (pseudo, email, password, role) VALUES (:nom, :email, :mdp, :role)');
		$insert->bindValue(':nom', $post['pseudo']);
		$insert->bindValue(':email', $post['email']);
		
		$insert->bindValue(':mdp', password_hash($post['password'], PASSWORD_DEFAULT));
		$insert->bindValue(':role', 'ROLE_USER');

		if($insert->execute()){
			echo 'Vous êtes bien enregistré!';
			echo '<a href=index.php>"Acceder au site!"</a>';
		}

	}
	else{
		echo implode('<br>', $errors);
	}

}
?>