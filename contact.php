<?php
require('inc/bdd.php');
?>
<?php
  session_start();
  ?>
  
</head>
<body style="padding:100px 0 200px 0">
  <div style="padding-bottom:100px" class="container">
  <div class="row">
  <div class="col-md-12">
  <hr>
  <div class="alert alert-info"><b>INFOS:</b> Ce formulaire est une démo, le fonctionnement est complet mais le message n'arrivera nul part, les spammer peuvent passer leur chemin!</div>
  <hr>
  </div>
  </div>
  </div>
<!-- CONTENT -->
  <div class="container">
  <?php if(array_key_exists('errors',$_SESSION)): ?>
  <div class="alert alert-danger">
  <?= implode('<br>', $_SESSION['errors']); ?>
  </div>
  <?php endif; ?>
  <?php if(array_key_exists('success',$_SESSION)): ?>
  <div class="alert alert-success">
  Votre email à bien été transmis !
  </div>
  <?php endif; ?>
<form action="send_form.php" method="post">
  <div class="row">
<div class="col-md-6">
  <div class="form-group">
  <label for="inputname">Votre nom</label>
  <input required type="text" name="name" class="form-control" id="inputname" value="<?php echo isset($_SESSION['inputs']['name'])? $_SESSION['inputs']['name'] : ''; ?>">
  </div><!--/*.form-group-->
  </div><!--/*.col-md-6-->
<div class="col-md-6">
  <div class="form-group">
  <label for="inputemail">Votre email</label>
  <input required type="email" name="email" class="form-control" id="inputemail" value="<?php echo isset($_SESSION['inputs']['email'])? $_SESSION['inputs']['email'] : ''; ?>">
  </div><!--/*.form-group-->
  </div><!--/*.col-md-6-->
<div class="col-md-12">
  <div class="form-group">
  <label for="inputmessage">Votre message</label>
  <textarea required id="inputmessage" name="message" class="form-control"><?php echo isset($_SESSION['inputs']['message'])? $_SESSION['inputs']['message'] : ''; ?></textarea>
  </div><!--/*.form-group-->
  </div><!--/*.col-md-12-->
<div class="col-md-12">
  <div class="checkbox">
  <label for="checkspam">
  <input type="checkbox" name="antispam" id="checkspam">Je suis un spammer et je l'assume!
  </label>
  </div>
  </div><!--/*.col-md-12-->
<div class="col-md-12">
  <button type='submit' class='btn btn-primary'>Envoyer</button>
  </div><!--/*.col-md-12-->
</div><!--/*.row-->
  </form>
</div><!--/*.container-->
  <!-- END CONTENT -->
  <?php
unset($_SESSION['inputs']); // on nettoie les données précédentes
  unset($_SESSION['success']);
  unset($_SESSION['errors']);

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
        <?php
              $select = $connexion->query('SELECT adresse_shop_lat FROM shop');
        $address = $select->fetch();      
            
            $map_address = $address['adresse_shop_lat'];
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