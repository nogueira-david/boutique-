<?php

if(!empty($_FILES)){
	//on m'a envoyé au moins un fichier, je peux faire le traitement

	//je vérifie que le transfert s'est bien passé
	if($_FILES['file']['error'] == 0){
		//si error = 0, c'est bon

		//je vérifie la taille du fichier
		//1 ko = 1024 octets
		//1 Mo = 1 048 576 octets

		//je veux limiter mes fichiers à 500Ko
		$maxSize = 500 * 1024; //500Ko en octets
		if($_FILES['file']['size'] <= $maxSize){
			//le fichier a une taille acceptable

			//je vérifie l'extension du fichier
			$fileInfo = pathinfo($_FILES['file']['name']);
			//var_dump($fileInfo);
			$extension = strtolower($fileInfo['extension']);

			$extensionsAutorisees = ['jpg', 'jpeg', 'png', 'svg', 'gif'];

			if(in_array($extension, $extensionsAutorisees)){
				//l'extension est ok, on peut procéder au transfert définitif du fichier
				$newName = md5(uniqid(rand(), true));
				//echo $newName;

				//------Création de la miniature
				//je décide que mes miniatures deront 100px de large
				$newWidth = 100;

				if($extension === 'jpg' || $extension === 'jpeg'){
					$newImage = imagecreatefromjpeg($_FILES['file']['tmp_name']);
				}
				elseif($extension === 'png'){
					$newImage = imagecreatefrompng($_FILES['file']['tmp_name']);
				}
				elseif($extension === 'gif'){
					$newImage = imagecreatefromgif($_FILES['file']['tmp_name']);
				}

				//je vais devoir calculer la hauteur de ma miniature
				//largeur originale (en px)
				$oldWidth = imagesx($newImage);

				//hauteur originale
				$oldHeight = imagesy($newImage);

				//calcul de la nouvelle hauteur
				$newHeight = ($oldHeight * $newWidth) / $oldWidth;

				//je crée une nouvelle image avec les dimensions (nouvelle largeur et nouvelle hauteur)
				$miniature = imagecreatetruecolor($newWidth, $newHeight);

				//on remplit la miniature à partir de l'image originale
				imagecopyresampled($miniature, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);

				//chemin vers le dossier où je stocke mes miniatures
				$folder = 'images_uploades/miniatures/';

				if($extension === 'jpg' || $extension === 'jpeg'){
					imagejpeg($miniature, $folder . $newName . '.' . $extension);
				}
				elseif($extension === 'png'){
					imagepng($miniature, $folder . $newName . '.' . $extension);
				}
				elseif($extension === 'gif'){
					imagegif($miniature, $folder . $newName . '.' . $extension);
				}

				//------Fin de la création de miniature

				//je peux transférer le fichier sur le serveur
				move_uploaded_file($_FILES['file']['tmp_name'], 'images_uploadees/' . $newName . '.' . $extension);

				//se connecter à la base et effectuer la requête pour insérer les infos dans la base
				require_once('inc/connexion.php');

                $insert = $connexion->query('INSERT INTO products (picture) VALUES("' . $newName . '.' . $extension .'")');
                
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
		else{
			echo 'fichier trop gros';
		}

	}
	else{
		echo 'erreur lors du transfert';
	}
}
?>
