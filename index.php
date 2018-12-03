<?php 
session_start();
var_dump($_SESSION);
include('inc/header.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Inscription</title>
</head>
<body>
	<h1>Inscription</h1>
	<hr>
	<form action="inscription.php" method="POST">
		<label>Pseudo</label><br>
		<input type="text" name="pseudo"><br>
		<label>Email</label><br>
		<input type="email" name="email"><br>
		<label>Mot de Passe</label><br>
		<input type="text" name="password"><br>
		<label>Saisir a nouveau le mot de passe</label><br>
		<input type="text" name="password2"><br>
		<button>Inscription</button>
	</form>
	<form action="connexion.php" method="POST">
		<label>Email</label><br>
		<input type="email" name="email"><br>
		<label>Mot de passe</label><br>
		<input type="text" name="password"><hr>
		<button>Connection</button>
	</form>
	<a href="deconnexion.php?deco=set"><button>Deconnection</button></a>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>