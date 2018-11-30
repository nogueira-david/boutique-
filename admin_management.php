<?php 
session_start();
require_once('inc/bdd.php');
include('inc/header.php');
if($_SESSION['role'] === 'ROLE_ADMIN'){ ?>

    <a href="users_management.php">Management</a>
    <hr>
    <a href="ajout_categorie.php">Adding category</a>
    <hr>
    <a href="update_map.php">Update map</a>
    <hr>

<?php } ?>