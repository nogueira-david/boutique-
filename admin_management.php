<?php include('inc/top_header.php'); ?>
<title>Admin management</title>
<?php include('inc/bot_header.php'); 
if($_SESSION['role'] === 'ROLE_ADMIN'){ ?>


    <a href="users_management.php">Management</a>
    <hr>
    <a href="ajout_categorie.php">Adding category</a>
    <hr>
    <a href="update_map.php">Update map</a>
    <hr>

<?php }else{
    echo 'vous devez être admin pour accéder à cette page';
} ?>