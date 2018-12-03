<?php include('inc/top_header.php'); ?>
		<title>Online Shop</title>
		<!--CSS-->
		<link rel="stylesheet" type="text/css" href="css/home.css">
		<!--Javascript-->
		<link rel="stylesheet" type="text/css" href="js/home.js">
	</head>

	<body>
		<!--Header-->
		<header class="background">
			<div id="carousel" class="carousel slide" data-ride="carousel">
			  <div class="carousel-inner">
				<div class="picto">
			    	<i class="fas fa-user-alt fa-2x"></i>
			    </div>
				<div class="slogan">
					<h1>HELLO</h1>
					<h2>ONE DAY ONE STYLE</h2>
				</div>
				<?php
				require_once('inc/bdd.php');
				$select = $connexion->query('SELECT * FROM slider');
				$images = $select->fetchAll();
				$cpt = 1;
				foreach($images as $img){
					$class = $cpt === 1 ? 'active' : '';
				?>
					<div class="carousel-item <?= $class ?>">
			     		<img class="d-block w-100" src="img/carousel/<?= $img['image'] ?>" alt="<?= $img['image'] ?>">
			    	</div>	
		    	<?php
		    		$cpt ++;
		    	}				
				?>
			  </div>
			</div>
		</header>

		<!--Import Header-->
		
		<title>Titre de la page</title>
		

		<!--Contenu principal -->
		<main class="container5">
			<div class="shop">
				<p>Nihil est enim virtute amabilius, nihil quod magis adliciat ad diligendum, quippe cum propter virtutem et probitatem etiam eos, quos numquam vidimus, quodam modo diligamus. Quis est qui C. Fabrici, M'. Curi non cum caritate aliqua benevola memoriam usurpet, quos numquam viderit? quis autem est, qui Tarquinium Superbum, qui Sp. Cassium, Sp. Maelium non oderit? Cum duobus ducibus de imperio in Italia est decertatum, Pyrrho et Hannibale; ab altero propter probitatem eius non nimis alienos animos habemus, alterum propter crudelitatem semper haec civitas oderit.Iam virtutem ex consuetudine vitae sermonisque nostri interpretemur nec eam, ut quidam docti, verborum magnificentia metiamur virosque bonos eos, qui habentur, numeremus, Paulos, Catones, Galos, Scipiones, Philos; his communis vita contenta est; eos autem omittamus, qui omnino nusquam reperiuntur.</p>
			</div>
			
			<div class="container1">
				<p>blablabla</p>
			</div>

		<!--Articles-->
			<div class="container2">
				<div class="carre">
				</div>

				<div class="container21">
					<div class="image1">
						<img src="img/pantalon_1.jpg" alt="pantalon_femme">
					</div>
					<div class="image1">
						<img src="img/pantalon_4.jpg" alt="pantalon_homme">
					</div>
					<div class="image1">
						<img src="img/pantalon_3.jpg" alt="pantalon_homme">
					</div>
					<div class="image1">
						<img src="img/pantalon_2.jpg" alt="pantalon_fomme">
					</div>
				</div>

				<div class="carre1">
				</div>
			</div>

			<div class="container3">
				<p>blablabla</p>
			</div>
		</main>

		<!--Footer-->
		<div class="container-fluid">
		</div>
		<!--jQuery-->
		<script src="js/jQuery_v3.3.1.js"></script>
		<!--Bootstrap.js-->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		<!-- LIBRAIRIE JS -->
		<script src="js/home.js"></script>
	</body>
</html>