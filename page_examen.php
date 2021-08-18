<?php 
include("verification.php");
if(isset($_SESSION['identifiant']) && isset($_SESSION['password']) && $_SESSION['commence']){
    //verifier si ces identifiants sont bien dans la bd
    unset($_SESSION['commence']);
	$m=0; 
	$recup_classe_etudiant=$bd->prepare('SELECT code_classe FROM etudiant WHERE code_etudiant=?');
	$recup_classe_etudiant->execute(array($_SESSION['identifiant']));
	$classe_etudiant=$recup_classe_etudiant->fetch();
    $nbr_exam=nbrExam($classe_etudiant[0]);
    $nom_etu=nomEtudiant($_SESSION['identifiant']);
    $nom_exam=infoExamNom($classe_etudiant[0],0);
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
    $code_exam=infoExamCode($classe_etudiant[0],0);
    ?>


</head>

<body class="nav-md" >
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="index.html" class="site_title"><i class="fa fa-pencil"></i> <span>Examen : <?php echo $nom_exam[0]; ?></span></a>
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
                            <p style="font-size:8px; margin-top:30px; width:100%; position:absolute; left:-2%; color:white;">Avant fin question</p> 
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                           <h3>BONNE CHANCE ! </h3>
                           <span style="color:red;font-size='12px;"> Attention! quitter cette page equivaut à finir l'examen! .</span>
                        </div>
                        
                    </div>
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                
                                <div class="x_content">
                                    <h2>Questions</h2>
                                    <!-- Tabs -->
                                    <form class="form-horizontal form-label-left" action="recap_result.php" method="POST">
                                        
                                    <div id="wizard" class="form_wizard wizard_horizontal ">
                                        <ul class="wizard_steps">
                                        <?php 
                                        $nbr_Question=nbrQuestion($code_exam[0]);
                                       
                                        
                                        for ($j=1; $j<=$nbr_Question ; $j++) { ?>  
                                            <li >
                                                <a href="#step-<?php echo $j; ?>">
                                                    <span class="step_no"><?php echo $j; ?></span>
                                                </a>
                                            </li>
                                        <?php } ?>    
                                        </ul>

                                        <br>
                                        <br>
                                        <?php 
                                            
                                             $i=0; while($i<$nbr_Question){ 
                                             $code_question=infoQuestionCode($code_exam[0],$i);
                                              $libelle=libelleQuestion($code_exam[0],$i);

                                            $duree_quest=DureeQuestions($code_exam[0],$i);
                                            
                                            $duree_quest=date_create($duree_quest[0]);
                                            $duree_quest_h=date_format($duree_quest,"H");
                                            $duree_quest_m=date_format($duree_quest,"i");
                                            $duree_quest_s=date_format($duree_quest,"s");
                                            
                                             /*******************************************************************************
                                                 * calcul des secondes
                                                 ***************************************************************************/
                                             
                                                $heures   = $duree_quest_h;  // les heures < 24
                                                $minutes  = $duree_quest_m;   // les minutes  < 60
                                                $sec = $duree_quest_s;  // les secondes  < 60
                                                $annee = date("Y");  // par defaut cette année
                                                $mois  = date("m");  // par defaut ce mois
                                                $jour  = date("d");  // par defaut aujourd'hui
                                               /*******************************************************************************
                                                    * calcul des secondes
                                                    ***************************************************************************/
                                                $secondes[$i] = mktime(date("H") + $heures,date("i") + $minutes,date("s") + $sec,$mois,$jour,$annee) - time();
                                               ?>
                                          
                                        <div id="step-<?php echo $i+1 ;?>">
                                            
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
                                                for ($k=0; $k<4 ; $k++) { 
                                                    $reponse=libelleReponse($code_question[0],$k);
                                                    ?>  
                                                        <div class="custom_control custom-checkbox"> 
                                                                <input id="defaultChecked<?php echo $i.$k+1;?>"  
                                                                <?php
                                                                $num=$i+1;
                                                                $reponseDonnee="reponseChecked$num";
                                                                if(isset($_SESSION[$reponseDonnee])){
                                                                    foreach($_SESSION[$reponseDonnee] as $valeur){
                                                                        //recuperation reponses
                                                                        if($valeur==$reponse[0]){
                                                                            ?>
                                                                            checked='true'
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                type="checkbox" value="<?php echo $reponse[0]; ?>" name="reponseChecked<?php echo $i+1 .'[]';?>">
                                                                <label for="defaultChecked<?php echo $i.$k+1;?>" class="custom-control-label"><?php echo $reponse[0]; ?></label> 
                                                        </div>
                                                <?php
                                                    }
                                            }else{
                                                ?>
                                                    <div class="custom_control custom-checkbox"> 
                                                    <label for="defaultChecked2" class="custom-control-label">Entrez votre reponse: </label>
                                                    <input id="defaultChecked2" class="" autofocus type="text" value="<?php if(isset($_SESSION['reponse'.$i])){echo $_SESSION['reponse'.$i];}?>" name="reponse<?php echo $i; ?>">
                                                     
                                                </div>
                                            <?php
                                            }?>   
                                           
                                        </div>  
                                        
                                        <?php $i++;
                                                } ?>  
                                                
                                        
                                    </div>
                                    </form>
                                    <!-- End SmartWizard Content -->
                                </div>
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


<script>                                  

var h,m,s;

<?php  for ($m = 0; $m < $i; $m++) { ?>
var temps<?php echo $m ?>= <?php echo $secondes[$m];?>;
<?php } $m=0;?>;

var timer=setInterval('CompteaRebour()',1000);
function CompteaRebour(){
    <?php  for ($m = 0; $m < $i; $m++) { ?>
    if($("#step-<?php echo $m+1 ;?>").css("display")=="block"){

        <?php $_SESSION['timer']=$secondes[$m] ?>
        if (s== 0 && m==0 && h==0) {
    <?php 
	$nbr_exam=nbrExam($classe_etudiant[0]); 
	for ($g=0; $g < $nbr_exam; $g++) {
		$nbr_exam=nbrExam($classe_etudiant[0]); 
		$fin_exam=infoExamFin($classe_etudiant[0],$g);
		$code=infoExamCode($classe_etudiant[0],$g);
		modifFait($code[0],$fin_exam[0]);
	}
    ?>
    $("#step-<?php echo $m+1 ;?> input").attr('disabled','disabled');
}else{$("#step-<?php echo $m+1 ;?> input").attr('enabled','true');}
    
	if (temps<?php echo $m ?> >0) {
		temps<?php echo $m ?> -- ;
  h= parseInt((temps<?php echo $m ?>/3600)%24) ;
  m= parseInt((temps<?php echo $m ?>/60)%60) ;
  s= parseInt((temps<?php echo $m ?>)%60);
  var minutes=document.getElementById('minutes');
		minutes.innerHTML= (h<10 ? "0"+h: h) + ':' + (m<10 ? "0"+m: m) + ':' + (s<10 ? "0"+s: s);

if ((m <=5 && h <=0)) {
   $(".face p").css("color","red");
}else{$(".face p").css("color","white");$(".face #minutes").css("color","#14bdee");}

}
} 
   <?php } ?>
}

// window.onload = function() {

// function timerUpdate() {

// }

    
// }

window.onbeforeunload= function(event){
event.returnValue='Si vous quittez cette page vous ne pourrez plus passer cet examen, vos resultats seront directement enregistrés!';
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
    <!-- jQuery Smart Wizard -->
    <script src="js/jquery.smartWizard.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="js/custom.min.js"></script>


</body>

</html>