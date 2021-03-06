<?php
require_once('inc/bdd.php');
if(!empty($_GET['delete']) && is_numeric($_GET['delete'])){
	$image = $_GET['image'];
	var_dump($image);
	var_dump($_GET['delete']);
	$delete = $connexion->prepare('DELETE FROM slider WHERE id = :id'); 
	$delete->bindValue(':id', $_GET['delete']);
	if($delete->execute()){
	 if(file_exists('images_uploadees/miniatures/' . $image)){
		unlink('images_uploadees/miniatures/' . $image);
		}
	 if(file_exists('images_uploadees/' . $image)){
		unlink('images_uploadees/' . $image);
		}
	}
}
if(!empty($_FILES)){
	// on m'a envoyé au moins un fichier, je peux faire le traitement 

	// je vérifie que le transfert s'est bien passé :
	if($_FILES['fichier']['error'] == 0){
		// so error = 0, c'est bon 

		// je vérifie la taille du fichier
		// 1 ko = 1024 octets
		// 1 Mo = 1 048 576 octets

		// je veux limiter mes fichiers à 500 ko
		$maxSize = 500 * 1024;  // 500 ko en octets
		if($_FILES['fichier']['size'] <= $maxSize){
			// le fichier a une taille acceptable

			// je vérifie l'extension du fichier 
			$fileInfo = pathinfo($_FILES['fichier']['name']); 
			/*var_dump($fileInfo); */
			$extension = strtolower($fileInfo['extension']); 

			$extensionsAutorisees = ['jpg', 'jpeg', 'png']; 

			if(in_array($extension, $extensionsAutorisees)){

				// l'extension est ok, on peut proceder au transfert définitif du fichier 
				$newName = md5(uniqid(rand(), true)); 
				/*echo $newName; */

			// je peux transferer le fichier sur le serveur 
				move_uploaded_file($_FILES['fichier']['tmp_name'],
			 'img/carousel/' .$newName. '.' .$extension); 
			}

			// se connecter à la base et effectuer la requête pour insérer les infos dans la base

			$insert = $connexion->query("INSERT INTO slider (image) VALUES ('$newName.$extension')");
			if($insert){
				echo 'image uploadée'; 
			}
			else{
				echo 'upload ko'; 
			}
		}
		else{
			echo 'mauvaise extension'; 
			
	}
		}

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Ajouter une nouvelle image</title>
	<!--Bootstrap-->
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
	<!-- method POST obligatoire quand on upload un fichier -->
	<div class="container">
        <div class="row">
            <div class="col-md-8">
				<form  method="POST" enctype="multipart/form-data">
					<br><label><strong>Ajouter une image : </strong></label>
					<input type="file" name="fichier" class="form-control">
					<br><button type="submit" class="btn btn-primary">Envoyez</button><br>

					<br><label><strong>Modifier une image : </strong></label>
					<div type="file" name="image" class="form-control">
					<?php
					$resultat=$connexion->prepare('SELECT id, image FROM slider'); 
					$resultat->execute();
                    $slider = $resultat->fetchAll();
                     foreach($slider as $image){
                         ?>
                        <div value="<?= $image['id'] ?>" ><img src="images_uploadees/miniatures/<?= $image['image'] ?>"> <a href="image.php?delete=<?= $image['id'] ?>&image=<?=$image['image']?>">Supprimer l'image</a></div>
                        <?php
                        }
                    ?>
                    </div>
					<br><button type="submit" class="btn btn-primary">Envoyez</button>
				</form>
			</div>
		</div>
	</div>
	
</body>
</html>