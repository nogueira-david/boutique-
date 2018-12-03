<?php include('inc/top_header.php'); ?>
        <title>Geocoding service</title>
        <style>
           #map {
            height: 400px;
            width: 100%;
           }
        </style>
<?php include('inc/bot_header.php'); ?>
        <?php

         if (!empty($_POST['address'])) {
         $update = $connexion->prepare('UPDATE shop SET adresse = :adresse WHERE id_shop = 1');
         $update->bindValue(":adresse", $_POST['address']);
         $update->execute();
}

        $select = $connexion->query('SELECT adresse FROM shop');
        $address = $select->fetch();      
            
            $map_address = $address['adresse'];
         ?>
         <form method="POST">
            <input type="text" name="address">
            <button type="submit">Afficher</button>
        </form>
        <?php 
            
            $opts = array(
                    'http' => array(
                        'method' => 'GET'
                    )
            );

            $context = stream_context_create($opts);

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={".urlencode($map_address)."}&key=AIzaSyBjslA2cbupRwG-dJvPAKcfZp0ruzEFM38";

            $resultat = json_decode(file_get_contents($url, false, $context), true);
            ?>
            <pre>
                <?php 

                $lat = $resultat['results'][0]['geometry']['location']['lat'];
                $lng = $resultat['results'][0]['geometry']['location']['lng'];
                ?>
            </pre>
            <div id="map"></div>
            <script>
              function initMap() {
                var philo = {lat: <?= $lat ?>,  lng: <?= $lng ?>};
                var map = new google.maps.Map(document.getElementById('map'), {
                  zoom: 17,
                  center: philo
                });
                var marker = new google.maps.Marker({
                  position: philo,
                  map: map
                });
              }
            </script>
            <?php
        
        ?>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0xJoi5c9MwYIYQlwIEfLqLh95hLtcaYA&callback=initMap">
        </script>
    </body>
</html>