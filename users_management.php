<?php include('inc/top_header.php'); ?>
	<title>Users management</title>
<?php include('inc/bot_header.php'); ?>

	<?php

	if(!empty($_SESSION['role']) && $_SESSION['role'] === "ROLE_ADMIN"){
                        //l'utilisateur est connecté et admin

                        if(!empty($_POST)){
                            //formulaire de modification envoyé
                            $post = [];

                            foreach($_POST as $key => $value){
                                $post[$key] = strip_tags(trim($value));
                            }

                            $errors = [];

                            if(empty($post['pseudo']) || !preg_match("#^[a-zA-Z0-9_àéùèï -]{4,13}$#", $post['pseudo'])){
                                $errors[] = 'invalid pseudo';
                            }

                            if(empty($post['email']) || !filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
                                $errors[] = 'invalid email';
                            }

                            //on vérifie que l'email n'existe pas déjà sauf si c'est l'email de la personne qu'on update
                            $select = $connexion->prepare('SELECT id_users FROM users WHERE email = :email AND id_users <> :id');
                            $select->bindValue(':email', $post['email']);
                            $select->bindValue(':id', $post['idUser']);
                            $select->execute();
                            $users = $select->fetchAll();
                            if(count($users) > 0){
                                $errors[] = 'email already used';
                            }

                            $roles_acceptes = ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SELLER'];
                            if(empty($post['role']) || !in_array($post['role'], $roles_acceptes)){
                                $errors[] = 'role invalide';
                            }

                            if(empty($errors)){

                                $update = $connexion->prepare('UPDATE users SET pseudo = :pseudo, email  = :email, role = :role WHERE id_users = :id');
                                $update->bindValue(':pseudo', $post['pseudo']);
                                $update->bindValue(':email', $post['email']);
                  
                                $update->bindValue(':role', $post['role']);
                                $update->bindValue(':id', $post['idUser']);

                                if($update->execute()){
                                    echo 'User updated';
                                }

                            }
                            else{
                                echo implode('<br>', $errors);
                            }

                        }

                    }



	if(!empty($_SESSION['role']) && $_SESSION['role'] === "ROLE_ADMIN"){
		$select = $connexion->query('SELECT id_users, pseudo FROM users');
		$users = $select->fetchAll();
		?>
		<form>
			<div class="form-group">
				<label>Pseudo :</label>
				<select name="idUser" class="form-control">
					<option value="0">Pseudo : </option>
					<?php
					foreach($users as $user){
						?>
						<option value="<?= $user['id_users'] ?>"><?= $user['pseudo'] ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<button class="btn btn-secondary">Select</button>
		</form>
		<?php
	}
	?>

	<?php


	if(!empty($_GET['idUser']) && preg_match("#^\d+$#", $_GET['idUser'])){
                            //récupération des infos de l'utilisateur
		$select = $connexion->prepare('SELECT * FROM users WHERE id_users = :id');
		$select->bindValue(':id', $_GET['idUser']);
		$select->execute();
		$user = $select->fetch();

                            //affichage du formulaire de modif pré rempli
		?>
		<hr>
		<form method="post">
			<div class="form-group">
				<label>Pseudo</label>
				<input type="text" name="pseudo" class="form-control" value="<?= $user['pseudo'] ?>">
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="text" name="email" class="form-control" value="<?= $user['email'] ?>">
			</div>
			<div class="form-group">
				<label>Role</label>
				<select name="role" class="form-control">
					<option value="0">Select a role :</option>
					<option value="ROLE_USER" <?= $user['role'] === "ROLE_USER" ? 'selected' : ''; ?> >USER</option>
					<option value="ROLE_SELLER" <?= $user['role'] === "ROLE_SELLER" ? 'selected' : ''; ?> >SELLER</option>
					<option value="ROLE_ADMIN" <?= $user['role'] === "ROLE_ADMIN" ? 'selected' : ''; ?> >ADMIN</option>
				</select>
			</div>
			<input type="hidden" name="idUser" value="<?= $_GET['idUser'] ?>">
			<button type="submit" class="btn btn-info">Save changes</button>
		</form>
		<?php
	}
		?>
	</body>
	</html>