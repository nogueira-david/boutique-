
<?php 
include ('inc/top_header.php');
?>
<title>Products</title>
<link rel="stylesheet" type="text/css" href="css/products.css">
<?php 
include ('inc/bot_header.php');
?>
<div class="container m-auto">
    <div class="col-6 m-auto">
        <form class="form-inline mb-5">
            <div class="form-group">
                <label for="recherche" class="sr-only">Recherche :</label>
                <input type="text" name="recherche" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="Rechercher...">
            </div>
            <div class="form-group">
                <label class="sr-only">Trier par : </label>
                <select name="order" class="form-control mb-2 mr-sm-2 mb-sm-0">
                    <option value="none">Trier par</option>
                    <option value="croissant">Prix croissants</option>
                    <option value="decroissant">Prix decroissants</option>
                </select>
            </div>
                <button><i class="fas fa-search fa-2x"></i></button>
        </form>
    </form>
</div>
</div>
<div class="container row m-auto">

    <?php
    if (!empty($_GET['recherche']) || !empty($_GET['order'])) {

        if (!empty($_GET['recherche'])){
            $sql = 'SELECT * FROM products WHERE product_name LIKE :recherche';
        } else {
           $sql = 'SELECT * FROM products';
       }

       if( $_GET['order'] === "decroissant" ){
        $sql .= ' ORDER BY price DESC';
    }

    if( $_GET['order'] === "croissant" ){
        $sql .= ' ORDER BY price ASC';
    }

    $select = $connexion->prepare($sql);
    $select->bindValue(':recherche', '%' .$_GET['recherche'] . '%');
    $select->execute();

    $articles = $select->fetchAll();
    foreach($articles as $article){
        ?>
        <article class="col-3">       
            <h2 class="text-center">
            <p><img src="images_uploadees/miniatures/<?php echo $article['picture'];?>"></p>

                <?php 

                $productNameRecherche = preg_replace('#(' .strip_tags($_GET['recherche']). ')#', "<span style='background-color : #f5dbbe;'>$1</span>" , $article['product_name']);

                echo $productNameRecherche; ?>
            </h2>
            créé le <?php echo  $article['creation_date']; ?>
            <p>
                <?php echo  $article['price']. '$'; ?>
                <p><?php echo  $article['price']. '$'; ?><a href="detail_product.php?id=<?=$article['id']?>"> + details</a></p>
            </p>

        </article>






        <?php

    } 
} else {

    $resultat = $connexion->query('SELECT * FROM products ORDER BY creation_date DESC LIMIT 10 OFFSET 0');//LIMIT 0,10
    //LIMIT 5,10 est équivalent à LIMIT 10 OFFSET 5)

    $products = $resultat->fetchAll(PDO::FETCH_ASSOC);
    foreach($products as $product){
        ?>
        <article>		
            <p><img src="images_uploadees/miniatures/<?php echo $product['picture'];?>"></p>
            <h2>
                <?php echo $product['product_name']; ?>
            </h2>
            créé le <?php echo  $product['creation_date']; ?>
            <p>
                <?php echo  $product['price']. '$'; ?>
                <a href="detail_product.php?id=<?=$product['id']?>"> + details</a>
            </p>
            
        </article>

        <?php
    }
}
?>

</div>
</body>
</html>