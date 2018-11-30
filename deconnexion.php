<?php
session_start();
//traitement de la déconnexion
        if(isset($_GET['deco'])){
            //on supprime la session
            session_destroy();
            //on redirige vers la page d'accueil
            header('location:index.php');
        }
?>