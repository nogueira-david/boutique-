<?php 
session_start();
require_once('inc/bdd.php'); ?>
<!DOCTYPE html>
    <html>
    <head>
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Add a product</h1>
                <?php
                if(!empty($_POST)){

                    $errors = [];

                    $post = [];
                    foreach($_POST as $key => $value){
                        //$_POST['categories'] est un tableau, on ne peut pas le passer dans strip_tags
                        if(is_string($value)){
                        $post[$key] = strip_tags($value);
                        }
                    }
                
                    if(empty($post['name']) || mb_strlen($post['name']) > 150){
                        $errors [] = 'invalid name';
                    }

                    if(empty($post['price']) || !preg_match('#^[1-9][0-9]*\$$#', $post['price'])){
                        $errors [] = 'empty or invalid price';
                    }
                    if(!isset($post['availability'])  ){
                        $available = 0;
                    }
                    else{
                        $available = 1; 
                    }
                    
                    $newName = $extension = '';
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
                                    $folder = 'images_uploadees/miniatures/';
                    
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
                                    //------Début resize image principale 

                                    $newWidthFull = 400;
                                    if($extension === 'jpg' || $extension === 'jpeg'){
                                        $newImageFull = imagecreatefromjpeg($_FILES['file']['tmp_name']);
                                    }
                                    elseif($extension === 'png'){
                                        $newImageFull = imagecreatefrompng($_FILES['file']['tmp_name']);
                                    }
                                    elseif($extension === 'gif'){
                                        $newImageFull = imagecreatefromgif($_FILES['file']['tmp_name']);
                                    }
                                    //je peux transférer le fichier sur le serveur
                                    move_uploaded_file($_FILES['file']['tmp_name'], 'images_uploadees/tailleOrigine/' . $newName . '.' . $extension);

                                    //je vais devoir calculer la hauteur de l'image Full
                                    //largeur originale (en px)
                                    $oldWidth = imagesx($newImageFull);
                    
                                    //hauteur originale
                                    $oldHeight = imagesy($newImageFull);
                    
                                    //calcul de la nouvelle hauteur
                                    $newHeightFull = ($oldHeight * $newWidthFull) / $oldWidth;
                    
                                    //je crée une nouvelle image avec les dimensions (nouvelle largeur et nouvelle hauteur)
                                    $imgFull = imagecreatetruecolor($newWidthFull, $newHeightFull);
                    
                                    //on remplit la miniature à partir de l'image originale
                                    imagecopyresampled($imgFull, $newImageFull, 0, 0, 0, 0, $newWidthFull, $newHeightFull, $oldWidth, $oldHeight);
                    
                                    //chemin vers le dossier où je stocke mes images Full
                                    $folder = 'images_uploadees/';
                    
                                    if($extension === 'jpg' || $extension === 'jpeg'){
                                        imagejpeg($imgFull, $folder . $newName . '.' . $extension);
                                    }
                                    elseif($extension === 'png'){
                                        imagepng($imgFull, $folder . $newName . '.' . $extension);
                                    }
                                    elseif($extension === 'gif'){
                                        imagegif($imgFull, $folder . $newName . '.' . $extension);
                                    }
                    
                                    //------Fin de la création des images Full
                    
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
                                        
                    if(empty($errors)){
                        $insert = $connexion->prepare('INSERT INTO products (product_name, price, product_available, creation_date, picture) VALUES (:name, :price, :availability, :creationdate, :picture)');
                        $insert->bindValue(':name', strip_tags($post['name']));
                        $insert->bindValue(':price', strip_tags($post['price']));
                        $insert->bindValue(':availability', $available);
                        $insert->bindValue(':creationdate', date('Y-m-d H:i:s'));
                        $insert->bindValue(':picture', $newName . '.' . $extension);
                        if($insert->execute()){
                            echo '<div class="alert alert-success">Article ajouté</div>';
                            $idProduct = $connexion->lastInsertId();
                            $sql = 'INSERT INTO products_has_categories (products_id, categories_id) VALUES ';
                            foreach($_POST['categories'] as $key => $value){
                                $sql .= '( ' . $idProduct . ', :idcat' . $key . ' ),';
                            }
                            //j'enlève la dernière virgule
                            $sql = substr($sql, 0, -1);
                            $insert = $connexion->prepare($sql);
                            //je génère les bindValue
                            foreach($_POST['categories'] as $key => $value){
                                $insert->bindValue(':idcat' . $key, $value);
                            }
                            $insert->execute();

                        }
                        else{
                            echo 'pb sql';
                        }

                    }
                    else{
                        foreach($errors as $error){
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }

                }
                
                ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Product name : </label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="price">Price : </label>
                        <input type="text" name="price" id="price" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="availability">Availability : </label>
                        <input type="checkbox" name="availability" id="availability" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="file" name="file">
                    </div>
                    <?php
                        $select = $connexion->query('SELECT * FROM categories');
                        $categories = $select->fetchAll();
                    ?>
                    <div class="form-group">
                        <label>Category :</label>
                        <select multiple="multiple" name="categories[]" class="form-control">
                            <option value="0">Choose a category :</option>
                                <?php
                                foreach($categories as $category){
                                ?>
                            <option value="<?= $category['id'] ?>"><?= $category['wording'] ?></option>
                                <?php
                                }
                                ?>
                        </select>
                        </div>
                    <button type="submit" class="btn btn-success">Add</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>