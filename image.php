<?php
require_once('inc/bdd.php');
if(!empty($_GET['delete']) && is_numeric($_GET['delete'])){
	$image = $_GET['image'];
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

				// ------------------ Création d'une miniature
				// je décide que mes miniatures seront de 100px de large
				$newWidth = 100; 

				if($extension == 'jpg' || $extension === 'jpeg'){
					$newImage = imagecreatefromjpeg($_FILES['fichier']['tmp_name']); 
				}
				elseif($extension === 'png'){
					$newImage = imagecreatefrompng($_FILES['fichier']['tmp_name']); 
				}
				
				// je vais devoir calculer la hauteur de ma miniature
				// largeur originale (en px) 

				$oldWidth = imagesx($newImage); 

				// hauteur originale 

				$oldHeight = imagesy($newImage); 

				// calcul de la nouvelle hauteur 

				$newHeight = ($oldHeight * $newWidth) / $oldWidth; 

				// je crée une nouvelle image avec les dimensions (nouvelle largeur et nouvelle hauteur)

				$miniature = imagecreatetruecolor($newWidth, $newHeight); 

				// on remplit la miniature à partir de l'image originale 

				imagecopyresampled($miniature, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight); 

				// chemin vers le dossier ou je stocke mes miniatures

				$folder = 'images_uploadees/miniatures/'; 

				if($extension === 'jpg' || $extension === 'jpeg'){
					imagejpeg($miniature, $folder . $newName . '.' .$extension); 
				}
				elseif($extension === 'png'){
					imagepng($miniature, $folder . $newName . '.' .$extension); 
				}
				elseif($extension === 'gif'){
					imagegif($miniature, $folder . $newName . '.' .$extension); 
				}

				// je peux transferer le fichier sur le serveur 
				move_uploaded_file($_FILES['fichier']['tmp_name'],
			 'images_uploadees/' .$newName. '.' .$extension); 
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
                        <div value="<?= $image['id'] ?>" ><img src="images_uploadees/miniatures/<?= $image['image'] ?>"> <a href="image.php?delete=<?= $image['id'] ?>&image=$image['image']">Supprimer l'image</a></div>
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