<?php 
include("verification.php");
if(isset($_SESSION['identifiant']) && isset($_SESSION['password'])){
	//verifier si ces identifiants sont bien dans la bd

	//verifier si la date d'un examen est passée
		
	$recup_classe_etudiant=$bd->prepare('SELECT code_classe FROM etudiant WHERE code_etudiant=?');
	$recup_classe_etudiant->execute(array($_SESSION['identifiant']));
	$classe_etudiant=$recup_classe_etudiant->fetch();
	$nbr_exam=nbrExam($classe_etudiant[0]); 
	for ($g=0; $g < $nbr_exam; $g++) {
		$nbr_exam=nbrExam($classe_etudiant[0]); 
		$fin_exam=infoExamFin($classe_etudiant[0],$g);
		$code=infoExamCode($classe_etudiant[0],$g);
		modifFait($code[0],$fin_exam[0]);
	}

	$nom_etu=nomEtudiant($_SESSION['identifiant']);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Bienvenue</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap4/bootstrap.min.css">
        <link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="css/OwlCarousel2-2.2.1/owl.carousel.css">
        <link rel="stylesheet" type="text/css" href="css/OwlCarousel2-2.2.1/owl.theme.default.css">
        <link rel="stylesheet" type="text/css" href="css/OwlCarousel2-2.2.1/animate.css">
        <link rel="stylesheet" type="text/css" href="css/main_styles.css">
        <link rel="stylesheet" type="text/css" href="css/responsive.css">
        <link href="css/accueil-etudiant.css" rel="stylesheet">
    </head>
    <body onload="timer">
        
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
									<li><a href="connexion.php">Deconnexion</a></li>
								</ul>
							</nav>

						</div>
					</div>
				</div>
			</div>
        </div>
        
        

	<div class="counter">
		<div class="counter_background" style="background-image:url(images/counter_background.jpg)"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="counter_content">
					<?php if(isset($_SESSION["examen_fait"])){?>
						<div style="text-align:center;" class="alert alert-success alert-dismissible fade show" role="alert">
							<strong> vos reponses ont bien été enregistrées </strong>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="unset()">
									<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<?php } ?>
						<h2 class="counter_title welcom-mess">Bienvenue <b style="color:#14bdee;"> <?php echo $nom_etu?> </b></h2>
						<div class="counter_text welcom-mess si"><p>Vous avez </p>
						<div class="milestones align-items-center justify-content-between">
							<!-- Milestone -->
							<div class="milestone">
								<div class="milestone_counter" data-end-value="<?php echo $nbr_exam ?>">0</div>
								<div class="milestone_text">examens à venir</div>
							</div>
						</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php 
	for($i=0;$i<$nbr_exam;$i++){ ?>
	<div class="counter">
		<div class="counter_background" style="background: 
		<?php 
			if (($i+3)%3==0) {
				echo "#14bdee";
			}
			if (($i+2)%3==0) {
				echo "#384158";
			}
			if (($i+1)%3==0) {
				echo "rgba(24,34,100,0.5)";
			}
		?>
		"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="counter_content">
						<h2 class="counter_title welcom-mess" style="color:
						<?php 
							if (($i+3)%3==0) {
								
								echo "#fff";
							}
							if (($i+2)%3==0) {
								
								echo "#14bdee";
							}
							if (($i+1)%3==0) {
								echo "#384158";
							}
						?>
						;"><?php 		
						$nom_exam=infoExamNom($classe_etudiant[0],$i);
						echo $nom_exam[0];
						?></h2>
						<h2 class="counter_title welcom-mess" style="font-size:22px; margin-top:2%;" >Date examen:
						<?php 		
						$debut_exam=infoExamDebut($classe_etudiant[0],$i);
						echo $debut_exam[0];
						/*******************************************************************************
							* calcul des secondes
							***************************************************************************/
						$date=date_create($debut_exam[0]);
						$secondes[$i] =date_format($date,"U") - time();
						?></h2>
							<div class="counter_text welcom-mess si"> <span style="font-size:22px;color:#fff;" class="termi/com">Commence dans: </span>
							<span id="" class="counter_text welcom-mess si minutes" style="font-size:20px;color:white;"><i class="fa fa-warning" style="color:red;"> En cours !</i></span></div>
							
							<div class="save-btn">
								<button class="btn btn-dark save" type="submit" id="boutton<?php echo $i; ?>"  onclick="commence()">Commencer</button>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
<?php
	}
}else {
	header("location:connexion.php");
}

?>


<script type="text/javascript">	
<?php
for ($k = 0; $k <$nbr_exam ; $k++) {
	?>
	var temps<?php echo $k; ?> = <?php echo $secondes[$k];?>;
<?php
}
?>
var timer =setInterval('CompteaRebour()',1000);
function CompteaRebour(){
	<?php
	for ($k = 0; $k <$nbr_exam ; $k++) {
	?>
	if (temps<?php echo $k; ?> >=0) {
		temps<?php echo $k; ?>-- ;
  var j<?php echo $k; ?> = parseInt(temps<?php echo $k;?>/86400) ;
  var h<?php echo $k; ?> = parseInt((temps<?php echo $k; ?>/3600)%24) ;
  var m<?php echo $k; ?> = parseInt((temps<?php echo $k; ?>/60)%60) ;
  var s<?php echo $k; ?> = parseInt((temps<?php echo $k; ?>)%60);
  var minutes=document.getElementsByClassName('minutes');
		minutes[<?php echo $k; ?>].innerHTML= (j<?php echo $k; ?> <10 ? "0"+j<?php echo $k; ?> : j<?php echo $k; ?>) + '  Jours   '+ (h<?php echo $k; ?><10 ? "0"+h<?php echo $k; ?> : h<?php echo $k; ?>) + '  Heures   ' + (m<?php echo $k; ?><10 ? "0"+m<?php echo $k; ?> : m<?php echo $k; ?>) + ' Minutes  ' + (s<?php echo $k; ?><10 ? "0"+s<?php echo $k; ?> : s<?php echo $k; ?>) + ' Secondes ';

if (s<?php echo $k; ?> == 0 && m<?php echo $k; ?> ==0 && h<?php echo $k; ?> ==0) {
   clearInterval(timer);
   enable(<?php echo $k; ?>);
}else{$("#boutton<?php echo $k; ?>").attr('disabled','disabled');}
}
<?php
;}
?>
}

function Redirection(url) {
setTimeout("window.location=url", 500);
}


function enable(num){

	$("#boutton"+num).removeAttr('disabled');

}

function commence(){
	<?php
	$_SESSION['commence']='true';
	?>
	window.location='page_examen.php';
}

function unset(){
	<?php
	unset($_SESSION["examen_fait"]);
	?>  
}

</script>



<script src="js/jquery/jquery.min.js"></script>
<script src="css/bootstrap4/bootstrap.min.js"></script>
<script src="js/bootstrap.js"> </script>  
<script src="js/TweenMax.min.js"></script>
<script src="js/ScrollMagic.min.js"></script>
<script src="js/custom.js"></script>
    </body>
</html>