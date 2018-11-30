
    <ul>
      <?php if(!isset($_SESSION['id'])){ ?>
        <li>
            <a href="index.php">Home<span>(current)</span></a>
        </li>
        <li>
            <a href="products.php">Products</a>
        </li>
        <li>
            <a href="contact.php">Contact</a>
        </li>
        <li>
            <a href="connexion.php">Log in</a>
        </li>
        <li>
            <a href="inscription.php">Registration</a>
        </li>
      <?php } ?>
      <?php 
      if(isset($_SESSION['id'])){
          ?>
          <li>
            <a href="profil.php">Profile</a>
          </li>
          <li>
            <a href="demande_reset.php">Change your password</a>
          </li>
          <li>
            <a href="deconnexion.php">Sign out</a>
          </li>
          <?php if($_SESSION['role'] === 'ROLE_ADMIN'){ ?>
            <li>
              <a href="admin_management.php">Management</a>
            </li>
            <?php } ?>
          <?php if($_SESSION['role'] === 'ROLE_ADMIN' || $_SESSION['role'] === 'ROLE_AUTEUR'){ ?>
            <li>
              <a href="ajout_article.php">Adding article</a>
            </li>
            <?php } ?>
          <?php
      }
      ?>
    </ul