<?php
require_once('inc/bdd.php');
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
				<form action="traitement.php" method="POST" enctype="multipart/form-data">
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
                        <div value="<?= $image['id'] ?>" ><img src="images_uploadees/miniatures/<?= $image['image'] ?>"> <a href="#">Supprimer l'image</a></div>
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