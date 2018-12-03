<?php 
 require_once('inc/bdd.php');
include ('inc/top_header.php');
  ?>
<title>Detail_product</title>
<link rel="stylesheet" href="css/detail_product.css">
<?php 
include ('inc/bot_header.php');



?>
<div class="container">
<?php 

if (!empty($_GET['id'])){
$select = $connexion->query('SELECT * FROM products WHERE id="' .$_GET['id']. '"');
$products = $select->fetchAll();
    foreach($products as $product){
    ?>
        <article class="container row m-auto">       
            <h2 class="text-center">
            <p><img src="images_uploadees/<?php echo $product['picture'];?>"></p>
            créé le <?php echo  $product['creation_date']; ?>
            <p><?php echo  $product['price']. '$'; ?></p>
            <p><?php 
            	if ($product['product_available'] === 1){
            		echo "produit disponible";
            	} else {
            		echo "produit indisponible";
            	}
            	?>

            </p>

        </article>
    <?php    
}
}
?>
</div>
</body>
</html>