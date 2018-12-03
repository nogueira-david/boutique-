<?php
include('inc/top_header.php');
include('inc/bot_header.php');
?>
    </head>
    <body>
        <?php
        include('inc/top_header.php');
        ?>
        <div class="container">
            <div class="col-md-6">
            <?php
                require_once('inc/bdd.php');
                if(!empty($_GET['id_user']) && !empty($_GET['token'])){

                    $select = $connexion->prepare('SELECT * FROM reset_password WHERE id_user = :iduser AND token = :token');
                    $select->bindValue(':iduser', $_GET['id_user']);
                    $select->bindValue(':token', $_GET['token']);
                    $select->execute();

                    $tokens = $select->fetchAll();
 
                    if(!empty($tokens)){

                        ?>
                        <form method="POST" action="traitement_reset.php">
                            <div class="form-group">
                                <label>Nouveau mot de passe</label>
                                <input type="password" name="mdp" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Répéter le mdp</label>
                                <input type="password" name="mdp2" class="form-control">
                            </div>
                            <input type="hidden" name="idUser" value="<?= $_GET['id_user']; ?>">
                            <input type="hidden" name="token" value="<?= $_GET['token']; ?>">
                            <button type="submit" class="btn btn-info">Changer le mdp</button>
                        </form>
                        <?php
                    }

                }


                if(!empty($_POST)){
                    $errors = [];

                    if(empty($_POST['mdp']) || mb_strlen($_POST['mdp']) < 4 || mb_strlen($_POST['mdp']) > 10){
                        $errors[] = 'mdp trop court ou trop long';
                    }

                    if(empty($_POST['mdp2']) || $_POST['mdp'] !== $_POST['mdp2']){		
                        $errors[] = 'les deux mdp sont différents';
                    }

                    if(empty($_POST['idUser']) || !is_numeric($_POST['idUser'])){
                        $errors[] = 'id invalide';
                    }

                    if(empty($_POST['token'])){
                        $errors[] = 'token invalide';
                    }

                    if(empty($errors)){

                        $select = $connexion->prepare('SELECT * FROM reset_password WHERE id_user = :iduser AND token = :token');
                        $select->bindValue(':iduser', $_POST['idUser']);
                        $select->bindValue(':token', $_POST['token']);
                        $select->execute();

                        $tokens = $select->fetchAll();

                        if(!empty($tokens)){

                            $update = $connexion->prepare('UPDATE users SET password = :mdp WHERE id = :iduser');
                            $update->bindValue(':mdp', password_hash(trim(strip_tags($_POST['mdp'])), PASSWORD_DEFAULT));
                            $update->bindValue(':iduser', $_POST['idUser']);
                            if($update->execute()){
                                echo 'mot de passe modifié!';
                            }
                        }
                    }
                    else{
                        echo implode('<br>', $errors);
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>