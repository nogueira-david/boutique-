  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title></title>
  </head>
  <body>
    
  <div class="container">
  <div class="col-8 m-auto">
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
  <a class="navbar-brand" href="#">###</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
  <?php if(!isset($_SESSION['id'])){ ?>
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
            <a class="nav-link" href="products.php">Products</a>
      </li>
      <li class="nav-item active">
            <a class="nav-link" href="contact.php">Contact</a>
      </li>
      <li class="nav-item active">
            <a class="nav-link" href="connexion.php">Log in</a>
      </li>
      <li class="nav-item active">
            <a class="nav-link" href="inscription.php">Registration</a>
      </li>
  <?php }
      if(isset($_SESSION['id'])){
          ?>
          <li class="nav-item active">
            <a class="nav-link" href="profil.php">Profile</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="demande_reset.php">Change your password</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="deconnexion.php">Sign out</a>
          </li>
          <?php if($_SESSION['role'] === 'ROLE_ADMIN'){ ?>
            <li class="nav-item active">
              <a class="nav-link" href="admin_management.php">Management</a>
            </li>
            <?php } ?>
          <?php if($_SESSION['role'] === 'ROLE_ADMIN' || $_SESSION['role'] === 'ROLE_AUTEUR'){ ?>
            <li class="nav-item active">
              <a class="nav-link" href="ajout_article.php">Adding article</a>
            </li>
            <?php } ?>
          <?php
      }
      ?>
    </ul>
  </div>
</nav>
</div>
</div>