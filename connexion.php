<?php 
session_start();
unset($_SESSION['identifiant']);
unset($_SESSION['password']);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Connexion</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap4/bootstrap.min.css">
        <link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="css/OwlCarousel2-2.2.1/owl.carousel.css">
        <link rel="stylesheet" type="text/css" href="css/OwlCarousel2-2.2.1/owl.theme.default.css">
        <link rel="stylesheet" type="text/css" href="css/OwlCarousel2-2.2.1/animate.css">
        <link rel="stylesheet" type="text/css" href="css/main_styles.css">
        <link rel="stylesheet" type="text/css" href="css/responsive.css">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        
    <!-- Header Content -->
		<div class="header_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="header_content d-flex flex-row align-items-center justify-content-start">
							<div class="logo_container">
								<a href="#">
									<div class="logo_text">Epika'<span>Scool</span></div>
								</a>
							</div>
							<nav class="main_nav_contaner ml-auto">
								<ul class="main_nav">
									<li class="active"><a href="connexion.php">Accueil</a></li>
									<li><a href="contact.html">Contact</a></li>
								</ul>
							</nav>

						</div>
					</div>
				</div>
			</div>
        </div>
        
        

	<div class="counter" style="max-height:100%;">
		<div class="counter_background" style="background-image:url(images/courses_background2.png)"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="counter_content">
						<h2 class="counter_title">Bienvenue à Epika</h2>
						<div class="counter_text"><p><br> Epika est une université en ligne qui vous fournit tous les cours dont vous aurez besoin pour reussir dans n'importe quel domaine. Nous vous offrons un enseignement de qualité basé sur vos competences et centres d'interets, notre systeme de gestion d'examens est d'ailleurs plus que performant.<br> <br> Veuillez vous connecter pour decouvrir notre plateforme de gestion d'examen en ligne.Nos professeurs les plus competents vous attendent!</p></div>
				</div>
			</div>

			<div class="counter_form">
				<div class="row fill_height">
					<div class="col fill_height">
						<form method="POST" class="counter_form_content d-flex flex-column align-items-center justify-content-center" action="verification.php">
						<?php if(isset($_SESSION["message"])){?>
							<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom:15%;">
								<strong> <?php  echo "Mauvais mot de passe ou identifiant";?></strong>
							</div>
						<?php } ?>
							<div class="counter_text" align-center style="color='red';"> </div>
							<div class="counter_form_title">CONNEXION</div>
							<input type="text" class="counter_input" placeholder="identifiant:" required="required" name="identifiant">
							<input type="password" class="counter_input" placeholder="mot de passe:" required="required" name="password">
							<button type="submit" class="counter_form_button">se connecter</button>
						</form>
					</div>
				</div>
			</div>

		</div>
    </div>
    


    
<script src="js/jquery/jquery.min.js"></script>
<script src="css/bootstrap4/bootstrap.min.js"></script>
<script src="js/TweenMax.min.js"></script>
<script src="js/ScrollMagic.min.js"></script>
<script src="js/custom.js"></script>

<?php session_destroy(); ?>
    </body>
</html>