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
                <div class="col-md-8">
                <?php
                    if(!empty($_SESSION['role']) && $_SESSION['role'] === "ROLE_ADMIN"){?>
                        <h2>Add a categorie</h2>
                        <?php
                            if(!empty($_POST)){
                                //le formulaire été envoyé:
                                $post = [];
                                // trim() pour supprimer les espaces vides en début/fin
                                // strip_tags() pour supprimer les balises html/php 
                                foreach($_POST as $key => $value){
                                    $post[$key] = trim(strip_tags($value));
                                }

                                //on crée la variable qui va contenir les éventuelles erreurs
                                $errors = [];

                                if(empty($_POST['wording']) || mb_strlen($_POST['wording']) > 20){
                                    //le paramètre content n'existe pas ou est vide
                                    $errors['wording'] = 'empty or invalid categorie '; 
                                }

                                if(empty($errors)){
                                    //si le tableau $errors est vide, on peut enregistrer dans la bdd

                                    $resultat = $connexion->prepare('INSERT INTO categories (wording) VALUES (:wording)');
                                    $resultat->bindValue(':wording', $post['wording']);
                                    if($resultat->execute()){
                                        echo '<p class="alert alert-success">Registrated category</p>';
                                    }
                                    else{
                                        echo '<p class="alert alert-danger">Problem when saving</p>';
                                    }

                                }
                                else{
                                    foreach($errors as $error){
                                        echo '<p class="alert alert-danger">' . $error . '</p>';
                                    }
                                }


                            }


                        ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="wording" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-default">Save</button>
                        </form>

                        <h2>Edit category</h2>
                    <?php
                            if(!empty($_POST)){
                                //formulaire de modification envoyé
                                $post = [];
    
                                foreach($_POST as $key => $value){
                                    $post[$key] = strip_tags(trim($value));
                                }
    
                                $errors = [];
    
                                if(empty($_POST['wording']) || mb_strlen($_POST['wording']) > 20){
                                    $errors['wording'] = 'empty or invalid categorie '; 
                                }
    
                                //on vérifie que l'email n'existe pas déjà sauf si c'est l'email de la personne qu'on update
                                $select = $connexion->prepare('SELECT wording FROM categories WHERE wording = :wording AND id <> :id');
                                $select->bindValue(':wording', $post['wording']);
                                $select->execute();
                                $categories = $select->fetchAll();
                                if(count($categories) > 0){
                                    $errors[] = 'catégorie déjà présente';
                                }
                              
                                if(empty($errors)){
    
                                    $update = $connexion->prepare('UPDATE categories SET wording = :wording WHERE id = :id');
                                    $update->bindValue(':wording', $post[':wording']);
    
                                    if($update->execute()){
                                        echo 'edited category';
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
                                        $select = $connexion->query('SELECT * FROM categories');
                                        $categories = $select->fetchAll();
                                    ?>
                                <select name="categories" class="form-control">
                                    <option value="0">Choose a category</option>
                                        <?php
                                        foreach($categories as $category){
                                        ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['wording'] ?></option>
                                        <?php
                                        }
                                        ?>
                            </select>
                            </div>
                            <button type="submit" class="btn btn-default">Choose</button>
                        </form>
                        <?php

                        if(!empty($_GET['categories']) && preg_match("#^\d+$#", $_GET['categories'])){
                            //récupération des infos de l'utilisateur
                            $select = $connexion->prepare('SELECT * FROM categories WHERE id = :id');
                            $select->bindValue(':id', $_GET['categories']);
                            $select->execute();
                            $categories = $select->fetch();
                            
                            //affichage du formulaire de modif pré rempli
                        ?>
                         <hr>
                         <form method="post">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="wording" class="form-control" value="<?= $categories['wording'] ?>">
                            </div>
                            <button type="submit" class="btn btn-info">Modifier</button>
                        </form>

                    <?php 
                    }
                }?>

                </div>
            </div>
        </div>
    </body>
</html>