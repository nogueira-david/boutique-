<?php require_once('inc/bdd.php'); ?>


<form>
    <label for="recherche">Recherche :</label>
    <input type="text" name="recherche">
    <button>Envoyer</button>
</form>

<?php
if (!empty($_GET['recherche'])) {
    echo "recherche ok";

    $select = $connexion->prepare('SELECT * FROM products WHERE product_name LIKE :recherche');
    $select->bindValue(':recherche', '%' .$_GET['recherche'] . '%');
    $select->execute();

    $articles = $select->fetchAll();
    foreach($articles as $article){
    ?>
        <article>       
            <h2>
                <?php echo $article['product_name']; ?>
            </h2>
            créé le <?php echo  $article['creation_date']; ?>
            <p><?php echo  $article['price']; ?></p>
            <p><img src="images_uploadees/<?php echo $article['picture'];?>"></p>
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
            <h2>
                <?php echo $product['product_name']; ?>
            </h2>
            créé le <?php echo  $product['creation_date']; ?>
            <p><?php echo  $product['price']; ?></p>
            <p><img src="images_uploadees/<?php echo $product['picture'];?>"></p>
        </article>

    <?php
    }
}
    ?>