<?php include('inc/top_header.php'); ?>
<title>Ajout produit</title>
<?php include('inc/bot_header.php'); ?>

        <div class="container">
        <div class="row">
            <div class="col-md-6">
                <!----------------------------- AJOUT DE PRODUIT ----------------------------->
                <h1>Add a product</h1>
                <?php
                if(!empty($_POST['ajout'])){

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
                                    $newWidth = 250;
                    
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
                        $insert = $connexion->prepare('INSERT INTO products (product_name, price, product_available, creation_date, picture, miniature) VALUES (:name, :price, :availability, :creationdate, :picture, :miniature)');
                        $insert->bindValue(':name', strip_tags($post['name']));
                        $insert->bindValue(':price', strip_tags($post['price']));
                        $insert->bindValue(':availability', $available);
                        $insert->bindValue(':creationdate', date('Y-m-d H:i:s'));
                        $insert->bindValue(':picture', $newName . '.' . $extension);
                        $insert->bindValue(':miniature', $newName . '.' . $extension);
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
                    <input type="hidden" name="ajout">
                    <button type="submit" class="btn btn-success">Add</button>
                </form>

                <hr>
                <!----------------------------- MODIFICATION DE PRODUIT ----------------------------->

                            <h2>Edit product</h2>
                        <?php
                                if(isset($_POST['modif_product'])){
                                    //formulaire de modification envoyé
                                    $post = [];
        
                                    foreach($_POST as $key => $value){
                                        $post[$key] = strip_tags(trim($value));
                                    }
        
                                    $errors = [];
        
                                    if(empty($post['product_name']) || mb_strlen($post['product_name']) > 20){
                                        $errors[] = 'empty or invalid product '; 
                                    }

                                    if(empty($post['price']) || !preg_match('#^[1-9][0-9]*$#', $post['price'])){
                                        $errors [] = 'empty or invalid price';
                                    }

                                    if(!isset($post['availability']) ){
                                        $available = 0;
                                    }
                                    else{
                                        $available = 1; 
                                    }
        
                                    $select = $connexion->prepare('SELECT id FROM products WHERE product_name = :product AND id <> :id');
                                    $select->bindValue(':product', $post['product_name']);
                                    $select->bindValue(':id', $post['id']);
                                    $select->execute();
                                    $categories = $select->fetchAll();
                                    if(count($categories) > 0){
                                        $errors[] = '<p class="alert alert-danger">produit déjà présent</p>';
                                    }
                                
                                    if(empty($errors)){
        
                                        $update = $connexion->prepare('UPDATE products SET product_name = :name, price = :price, product_available = :availability WHERE id = :id');
                                        $update->bindValue(':id', $post['id']);
                                        $update->bindValue(':name', $post['product_name']);
                                        $update->bindValue(':price', $post['price']);
                                        $update->bindValue(':availability', $available);
                                        if($update->execute()){
                                            echo '<p class="alert alert-success">edited category</p>';
                                        }
        
                                    }
                                    else{
                                        echo implode('<br>', $errors);
                                    }
        
                                }?>
                            
                            <form>
                                <div class="form-group">
                                    <label>Title</label>
                                        <?php
                                            $select = $connexion->query('SELECT * FROM products');
                                            $products = $select->fetchAll();
                                        ?>
                                    <select name="products" class="form-control">
                                        <option value="0">Choose a product</option>
                                            <?php
                                            foreach($products as $product){
                                            ?>
                                        <option value="<?= $product['id'] ?>"><?= $product['product_name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                    </select>
                                </div>
                                <input type="hidden" name="update">
                                <button class="btn btn-default">Choose</button>
                            </form>
                            <?php

                            if(!empty($_GET['products']) && preg_match("#^\d+$#", $_GET['products'])){
                                //récupération des infos du produit
                                $select = $connexion->prepare('SELECT * FROM products WHERE id = :id');
                                $select->bindValue(':id', $_GET['products']);
                                $select->execute();
                                $products = $select->fetch();
                                
                                //affichage du formulaire de modif pré rempli
                            ?>
                            <hr>
                            <form method="post">
                                <div class="form-group">
                                    <label>Product name</label>
                                    <input type="text" name="product_name" class="form-control" value="<?= $products['product_name'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" name="price" class="form-control" value="<?= $products['price'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Product available</label>
                                    <input type="checkbox" name="availability" class="form-control" value="<?= $products['product_available'] ?>">
                                </div>
                                <input type="hidden" name="id" value="<?= $_GET['products'] ?>">
                                <input type="hidden" name="modif_product">
                                <button type="submit" class="btn btn-info">Edit</button>
                            </form>

                            <?php }?>

                            <hr>
                            <!----------------------------- SUPPRESSION DE PRODUIT ----------------------------->

                             <h2>Delete product</h2>

                                <?php
                                    if(!empty($_POST['delete'])){
                                    //formulaire de modification envoyé
                                    $post = [];
        
                                    foreach($_POST as $key => $value){
                                        $post[$key] = strip_tags(trim($value));
                                    }
        
                                    $errors = [];
        
                                    if($post['delete'] === "0"){
                                        $errors[] = 'empty or invalid category '; 
                                    }
                                
                                    if(empty($errors)){
        
                                        $update = $connexion->prepare('DELETE FROM products WHERE id = :id');
                                        $update->bindValue(':id', $post['delete']);
                                        if($update->execute()){
                                            echo '<p class="alert alert-success">deleted product</p>';
                                        }
        
                                    }
                                    else{
                                        echo implode('<br>', $errors);
                                    }
        
                                }?>
                            
                            <form method="post">
                                <div class="form-group">
                                    <label>Title</label>
                                        <?php
                                            $select = $connexion->query('SELECT * FROM products');
                                            $products = $select->fetchAll();
                                        ?>
                                    <select name="delete" class="form-control">
                                        <option value="0">Choose a category</option>
                                            <?php
                                            foreach($products as $product){
                                            ?>
                                        <option value="<?= $product['id'] ?>"><?= $product['product_name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                    </select>
                                </div>
                                <button class="btn btn-default">Delete</button>
                            </form>
            </div>
        </div>
    </div>
</body>
</html>