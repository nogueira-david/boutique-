
<?php    
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
        </article>
    <?php
    }
    ?>