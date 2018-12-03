  </head>
  <body>
    
  <div class="container">
    <div class="col-6 m-auto">
      <nav class="navbar navbar-expand-lg navbar-light bg-white">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="products.php">Products</a>
        </li>
      <!-- si pas connecté -->
      <?php if(!isset($_SESSION['id'])){ ?>
          <li class="nav-item active">
                <a class="nav-link" href="login.php">Log in</a>
          </li>
          <li class="nav-item active">
                <a class="nav-link" href="inscription.php">Registration</a>
          </li>
      <?php }
        //si connecté
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
              <!-- Si admin -->
              <?php if($_SESSION['role'] === 'ROLE_ADMIN'){ ?>
                <li class="nav-item active">
                  <a class="nav-link" href="admin_management.php">Management</a>
                </li>
                <?php } ?>
                <!-- Si admin ou auteur -->
              <?php if($_SESSION['role'] === 'ROLE_ADMIN' || $_SESSION['role'] === 'ROLE_AUTEUR'){ ?>
                <li class="nav-item active">
                  <a class="nav-link" href="ajout_product.php">Adding article</a>
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