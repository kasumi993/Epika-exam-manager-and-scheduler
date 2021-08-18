<?php 
include("verification.php");
if(isset($_SESSION['identifiant']) && isset($_SESSION['password']) && isset($_REQUEST['valider'])){
	//verifier si ces identifiants sont bien dans la bd
	
	$recup_classe_etudiant=$bd->prepare('SELECT code_classe FROM etudiant WHERE code_etudiant=?');
	$recup_classe_etudiant->execute(array($_SESSION['identifiant']));
	$classe_etudiant=$recup_classe_etudiant->fetch();
    $nbr_exam=nbrExam($classe_etudiant[0]);
    $nom_etu=nomEtudiant($_SESSION['identifiant']);
?>

<!DOCTYPE html>
<html lang="en">
    

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Examen en cours...</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="css/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="css/custom.min.css" rel="stylesheet">
    <link rel="stylesheet" type="" href="css/page_examen.css">

    <?php
    $fin_exam=infoExamFin($classe_etudiant[0],0);
    $code_exam=infoExamCode($classe_etudiant[0],0);
    /*******************************************************************************
        * calcul des secondes
        ***************************************************************************/
    $dateFin=date_create($fin_exam[0]);
    $secondes =date_format($dateFin,"U") - time();
    ?>


</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="index.html" class="site_title"><i class="fa fa-pencil"></i> <span>Examen de </span></a>
                    </div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="images/user.png" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Bienvenue,</span>
                            <h2><?php echo $nom_etu; ?></h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->  
                    <div class="timer-group">
                        <div class="timer hour">
                            <div class="hand"><span></span></div>
                            <div class="hand"><span></span></div>
                        </div>
                        <div class="timer minute">
                            <div class="hand"><span></span></div>
                            <div class="hand"><span></span></div>
                        </div>
                        <div class="timer second">
                            <div class="hand"><span></span></div>
                            <div class="hand"><span></span></div>
                        </div>
                        
                        <div class="face">
                            <p id="minutes">00:00:00</p> 
                            <p style="font-size:8px; margin-top:30px; width:100%; position:absolute; left:-2%; color:white;">Avant fin examen</p> 
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>RECAPITULATIF </h3>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                
                                <div class="x_content">
                                    <h2>Voici les réponses que vous avez entrées: cliquez sur 'retour' pour modifier votre reponse. <br> <span style="color:red;"> Attention! si le temps pour cette question est écoulé vous ne pourrez acceder au lien.</span></h2>
                                    <!-- Tabs -->
                                    <br>
                                    <br>
                                    <div id="wizard" class="form_wizard wizard_horizontal ">
                                    
                                        <?php
                                        $nbr_Question=nbrQuestion($code_exam[0]);
                                        $i=0; while($i<$nbr_Question){ 

                                             $libelle=libelleQuestion($code_exam[0],$i);
                                             ?>
                                                <div class="form-group"> 
                                                    <h2 class="StepTitle">Question <?php echo $i+1 ;?></h2>
                                                    <p>
                                                        <?php echo $libelle[0] ?>
                                                    </p>
                                                </div>
                                            <?php 
                                            $qcm=typeQuestion($code_exam[0],$i);
                                            if($qcm==true){
                                                $code_question=infoQuestionCode($code_exam[0],$i);
                                                $nbr_rep=nbrReponse($code_question[0]);
                                                for ($k=0; $k<$nbr_rep ; $k++) { 
                                                    $reponse=libelleReponse($code_question[0],$k);
                                                    ?>  
                                                        <div class="custom_control custom-checkbox"> 
                                                                <input id="defaultChecked<?php echo $i.$k+1;?>" class="" 
                                                                <?php
                                                                $num=$i+1;
                                                                $reponseDonnee="reponseChecked$num";
                                                                echo $reponseDonnee;
                                                                if(isset($_REQUEST[$reponseDonnee])){
                                                                    foreach($_REQUEST[$reponseDonnee] as $valeur){
                                                                        //recuperation reponses
                                                                $_SESSION[$reponseDonnee]=$_REQUEST[$reponseDonnee];
                                                                        if($valeur==$reponse[0]){
                                                                            ?>
                                                                            checked='true'
                                                                            <?php
                                                                        }
                                                                    }
                                                                }else{
                                                                    echo "vous n'avez rien coché";
                                                                }
                                                                    
                                                                ?>
                                                                type="checkbox" disabled value="<?php echo $reponse[0]; ?>" name="reponseChecked<?php echo $i+1 .'[]';?>">
                                                                <label for="defaultChecked<?php echo $i.$k+1;?>" class="custom-control-label"> <?php echo $reponse[0]; ?></label> 
                                                        </div>
                                                <?php
                                                    }
                                            }else{
                                                ?>
                                                <div class="custom_control custom-checkbox"> 
                                                    <?php $_SESSION['reponse'.$i]=$_REQUEST['reponse'.$i]; echo $_REQUEST['reponse'.$i] ?>  
                                                </div>
                                            <?php
                                            }?>   
                                        
                                        <?php $i++;
                                                } ?>  

                                    </div>
                                    
                                    <!-- End SmartWizard Content -->
                                </div>
                                
                            </div>
                            <div style="width:100%; text-align:center; margin-top:5%;">
                                <button onclick="annule()" id="annule" style="border:none; border-radius:6px; padding:1%;color:white;background:#384158">Rectifier une reponse</button>
                                <button onclick="valide()" id="valide" style="border:none; border-radius:6px; padding:1%;color:white;background:#384158">Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->

        </div>
    </div>
    <?php
}else {
	header("location:connexion.php");
}

?>

<script type="text/javascript">

var temps = <?php echo $secondes;?>;
<?php $_SESSION['timer']=$secondes ?>
var timer =setInterval('CompteaRebour()',1000);
function CompteaRebour(){
    <?php $_SESSION['timer']-- ?>
  temps-- ;
  h = parseInt(temps/3600) ;
  m = parseInt((temps/60)%60) ;
  s = parseInt((temps)%60) ;
  var minutes=document.getElementById('minutes');
		minutes.innerHTML= (h<10 ? "0"+h : h) + ':' + (m<10 ? "0"+m : m) + ':' + (s<10 ? "0"+s : s);

if ((m <=5 && h <=0)) {
   $(".face p").css("color","red");
}

if ((s <= 0 && m <=0 && h <=0)) {
    <?php 
	$nbr_exam=nbrExam($classe_etudiant[0]); 
	for ($g=0; $g < $nbr_exam; $g++) {
		$nbr_exam=nbrExam($classe_etudiant[0]); 
		$fin_exam=infoExamFin($classe_etudiant[0],$g);
		$code=infoExamCode($classe_etudiant[0],$g);
		modifFait($code[0],$fin_exam[0]);
	}
    ?>
    clearInterval(timer);
    $("#annule").attr("disabled","true");
}
}

function annule() {
    window.location="page_examen.php";
}

function valide() {
    <?php
    $_SESSION['examen_fait']="true";
    $code=infoExamCode($classe_etudiant[0],0);
	fait($code[0]);
    ?>
    window.location="accueil_etudiant.php";
}

</script>

<style>
    .timer.hour .hand span {
        animation-duration: <?php echo $_SESSION['timer'];?>s;
    }
    .timer.minute .hand span {
        animation-duration: <?php echo $_SESSION['timer']/60%60;?>s;
    }
</style>


    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="js/fastclick.js"></script>
    <!-- NProgress -->
    <script src="js/nprogress.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="js/custom.min.js"></script>


</body>

</html>