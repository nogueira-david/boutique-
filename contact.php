<?php
require('inc/bdd.php');
?>
<?php
  session_start();
  ?>
<!DOCTYPE html>
<html>
<head>
  <title>Contact</title>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/contact.css">
</head>
<body>
<section id="contact" class="content-section text-center">
        <div class="contact-section">
            <div class="container">
              <h2>Nous contacter</h2>
              <div class="row">
                <div class="col-md-8 col-md-offset-2">
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="exampleInputName2">Nom :</label>
                      <input type="text" class="form-control" id="exampleInputName2" placeholder="Contact">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail2">Email :</label>
                      <input type="email" class="form-control" id="exampleInputEmail2" placeholder="contact@shop.com">
                    </div>
                    <div class="form-group ">
                      <label for="exampleInputText">Votre Message :</label>
                     <textarea  class="form-control" placeholder="Description"></textarea> 
                    </div>
                    <button type="submit" class="btn btn-default">Envoyer</button>
                  </form>

                  <?php
        $select = $connexion->query('SELECT adresse FROM shop');
        $address = $select->fetch();      
            
            $map_address = $address['adresse'];
            
            $opts = array(
                    'http' => array(
                        'method' => 'GET'
                    )
            );

            $context = stream_context_create($opts);

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={".urlencode($map_address)."}&key=AIzaSyBjslA2cbupRwG-dJvPAKcfZp0ruzEFM38";

            $resultat = json_decode(file_get_contents($url, false, $context), true);  

                $lat = $resultat['results'][0]['geometry']['location']['lat'];
                $lng = $resultat['results'][0]['geometry']['location']['lng'];
                ?>
            
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
            $select = $connexion->query('SELECT adresse FROM shop');
            $adresse = $select->fetch();
            ?>
            <p> La boutique se situe au : <?= $adresse['adresse'] ?></p>

                    <h3>Nos réseaux sociaux</h3>
                  <ul class="list-inline banner-social-buttons">
                    <li><a href="#" class="btn btn-default btn-lg"><i class="fa fa-twitter"> <span class="network-name">Twitter</span></i></a></li>
                    <li><a href="#" class="btn btn-default btn-lg"><i class="fa fa-facebook"> <span class="network-name">Facebook</span></i></a></li>
                  </ul>
                </div>
              </div>
            </div>
        </div>
      </section>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0xJoi5c9MwYIYQlwIEfLqLh95hLtcaYA&callback=initMap">
        </script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
      </body>
</html>