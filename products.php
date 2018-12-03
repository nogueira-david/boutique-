<?php 
session_start();
 require_once('inc/bdd.php'); ?>


<form>
    <label for="recherche">Recherche :</label>
    <input type="text" name="recherche">
    <label>Trier par : </label>
    <select name="order">
        <option value="none">Trier par</option>
        <option value="croissant">Prix croissants</option>
        <option value="decroissant">Prix decroissants</option>
    </select>
    <button>Envoyer</button>
</form>

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
        <article>       
            <h2>
                <?php 

                $productNameRecherche = preg_replace('#(' .strip_tags($_GET['recherche']). ')#', "<span style='background-color : #f5dbbe;'>$1</span>" , $article['product_name']);

                echo $productNameRecherche; ?>
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