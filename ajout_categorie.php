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

                            <!----------------------------- AJOUT DE CATÉGORIE ----------------------------->

                            <h2>Add a categorie</h2>
                            <?php
                                if(!empty($_POST['wording'])){
                                    //le formulaire été envoyé:
                                    $post = [];
                                    // trim() pour supprimer les espaces vides en début/fin
                                    // strip_tags() pour supprimer les balises html/php 
                                    foreach($_POST as $key => $value){
                                        $post[$key] = trim(strip_tags($value));
                                    }

                                    //on crée la variable qui va contenir les éventuelles erreurs
                                    $errors = [];

                                    if(empty($post['wording']) || mb_strlen($post['wording']) > 20){
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
                                <button type="submit" class="btn btn-default">Add</button>
                            </form>

                            <!----------------------------- MODIFICATION DE CATÉGORIE ----------------------------->

                            <h2>Edit category</h2>
                        <?php
                                if(!empty($_POST['wording2'])){
                                    //formulaire de modification envoyé
                                    $post = [];
        
                                    foreach($_POST as $key => $value){
                                        $post[$key] = strip_tags(trim($value));
                                    }
        
                                    $errors = [];
        
                                    if(empty($post['wording2']) || mb_strlen($post['wording2']) > 20){
                                        $errors[] = 'empty or invalid categorie '; 
                                    }
        
                                    $select = $connexion->prepare('SELECT wording FROM categories WHERE wording = :wording AND id <> :id');
                                    $select->bindValue(':wording', $post['wording2']);
                                    $select->bindValue(':id', $post['id']);
                                    $select->execute();
                                    $categories = $select->fetchAll();
                                    if(count($categories) > 0){
                                        $errors[] = '<p class="alert alert-danger">catégorie déjà présente</p>';
                                    }
                                
                                    if(empty($errors)){
        
                                        $update = $connexion->prepare('UPDATE categories SET wording = :wording WHERE id = :id');
                                        $update->bindValue(':wording', $post['wording2']);
                                        $update->bindValue(':id', $post['id']);
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
                                <button class="btn btn-default">Choose</button>
                            </form>
                            <?php

                            if(!empty($_GET['categories']) && preg_match("#^\d+$#", $_GET['categories'])){
                                //récupération des infos du libellé
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
                                    <input type="text" name="wording2" class="form-control" value="<?= $categories['wording'] ?>">
                                </div>
                                <input type="hidden" name="id" value="<?= $_GET['categories'] ?>">
                                <button type="submit" class="btn btn-info">Edit</button>
                            </form>

                            <?php }?>
                                <!----------------------------- SUPPRESSION DE CATÉGORIE ----------------------------->

                    <h2>Delete category</h2>

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
        
                                        $update = $connexion->prepare('DELETE FROM categories WHERE id = :id');
                                        $update->bindValue(':id', $post['delete']);
                                        if($update->execute()){
                                            echo '<p class="alert alert-success">deleted category</p>';
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
                                            $select = $connexion->query('SELECT * FROM categories');
                                            $categories = $select->fetchAll();
                                        ?>
                                    <select name="delete" class="form-control">
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
                                <button class="btn btn-default">Delete</button>
                            </form>
                            <?php
                    }else{
                        echo 'vous devez être admin pour accéder à cette page <a href=index.php>Accueil</a>';
                    }?>

                </div>
            </div>
        </div>
    </body>
</html>