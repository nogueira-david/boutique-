<?php require_once('inc/bdd.php'); ?>
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

                	if(empty($post['price']) || preg_match('#^[1-9]{1,}\$$#', $post['price'])){
                		$errors [] = 'empty or invalid price';
                    }
                    if(!isset($post['availability'])  ){
                		$errors [] = 'indicate the validity of the product';
                    }
                    if(empty($post['picture']) ){
                		$errors [] = 'empty or invalid picture';
                    }

                	if(empty($errors)){
                		$insert = $connexion->prepare('INSERT INTO products (product_name, price, product_available, creation_date, picture) VALUES (:name, :price, :availability, :creationdate, :picture)'); //"'. date('Y-m-d H:i:s') .'" autre alternative
                		$insert->bindValue(':name', strip_tags($post['product_name']));
                        $insert->bindValue(':price', strip_tags($post['price']));
                        $insert->bindValue(':availability', strip_tags($post['product_available']));
                        $insert->bindValue(':creationdate', date('Y-m-d H:i:s'));
                        $insert->bindValue(':picture', strip_tags($post['picture']));
                		if($insert->execute()){
                            echo '<div class="alert alert-success">Article ajouté</div>';
                            $idProduct = $connexion->lastInsertId();
                            $sql = 'INSERT INTO articles_has_categories (products_id, categories_id) VALUES ';
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
                        <button class="btn btn-success">Send</button>
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