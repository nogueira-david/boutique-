<?php
require('../inc/bdd.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Geocoding service</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
           #map {
            height: 400px;
            width: 100%;
           }
        </style>
    </head>
    <body>
        <form>
            <input type="text" name="adresse">
            <button type="submit">Afficher</button>
        </form>
        <?php

        $select = $connexion->query('SELECT adresse_shop_lat FROM shop');
        $address = $select->fetch();

        var_dump($address);      
            
            $map_address = $address['adresse_shop_lat'];
            
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