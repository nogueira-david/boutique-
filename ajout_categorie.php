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
                    <h2>Add a categorie</h2>
                    <?php
                    require_once('inc/bdd.php');
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
                    
                </div>
                <div class="col-md-4">
                </div>
            </div>
        </div>
    </body>
</html>