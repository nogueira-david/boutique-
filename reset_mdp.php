<?php
include('inc/top_header.php');
include('inc/bot_header.php');
require_once('vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>

</head>
<body>    
    <div class="container">
        <div class="col-md-6">
            <?php
            if(!empty($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){

                $select = $connexion->prepare('SELECT id_users, pseudo FROM users WHERE email = :email');
                $select->bindValue(':email', $_GET['email']);
                $select->execute();
                $users = $select->fetchAll();

                if(count($users) === 1){

                    echo 'Bonjour ' . $users[0]['pseudo'] . ', vous allez reçevoir un email';

                    $token = md5(uniqid(rand(), true));

                    $id_user = $users[0]['id'];

                    
                    $insert = $connexion->prepare('INSERT INTO reset_password (id_user, token) VALUES(:iduser, :token)');
                    $insert->bindValue(':iduser', $id_user);
                    $insert->bindValue(':token', $token);
                    $insert->execute();

                    $mail = new PHPMailer(true);
                    
                    try{
                      
                        $mail->isSMTP();
                        $mail->Host = 'mail.gmx.com';

                        $mail->SMTPAuth = true;
                        $mail->Username = 'promo8wf3@gmx.fr';
                        $mail->Password = 'ttttttttt33'; 
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

              
                        $mail->SetFrom("promo8wf3@gmx.fr", "Matthieu");
                        $mail->addAddress('contact@shop33.com', 'Shop_33');
                        
                        $mail->isHTML(true);

                       
                        $mail->Subject = 'Demande de changement de mot de passe';

                        
                        $mail->Body = 'Veuillez cliquer <a href="http://localhost/promo8/boutique-/traitement_reset.php?id_user=' . $id_user .'&token=' . $token . '">ici</a>';


                        $mail->send();
                        echo 'message envoyé';
                        }
                        catch (Exception $e){
                            echo 'gros problème : ' . $mail->ErrorInfo;
                        }

                        echo 'Veuillez cliquer <a href="http://localhost/promo8/boutique-/traitement_reset.php?id_user=' . $id_user .'&token=' . $token . '">ici</a>';
                }
                else{
                    echo 'email inconnu';
                }

            }

             ?>
            <form>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" placeholder="Veuillez entrer votre email svp">
                </div>
                <button type="submit" class="btn btn-secondary">Demander un changement de mot de passe</button>
            </form>
        </div>
    </div>
    
</body>
</html>