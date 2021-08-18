<?php session_start(); ?>


    <?php
        if (extension_loaded('PDO')){
           
        }
        else{
       
        }

        $dsn = "mysql:host=localhost;dbname=examen;port=3306;charset=UTF8";

        try {
            
            $bd = new PDO ($dsn, 'root', '');
           
        }
        catch (PDOException $e){
            $m= $e->getMessage();
            echo "$m <br/>";
            exit("Erreur de connexion");
        }

        

        function verifierAdmin($login, $password){
            global $bd;
            $repLogin = $bd -> prepare('SELECT login FROM admin WHERE login=?');
            $repPass = $bd -> prepare('SELECT password FROM admin WHERE password=?');
            $repLogin->execute(array($login));
            $repPass->execute(array($password));
            $recupLogin=$repLogin->fetchAll();
            $recupPass=$repPass->fetchAll();

            if(count($recupLogin)>0 && count($recupPass)>0){
                return true;
            }else{return false;}   
        }

        

        function verifierEtudiant($code, $password){

            global $bd;
            $repLogin = $bd -> prepare('SELECT code_etudiant FROM etudiant WHERE code_etudiant=?');
            $repPass = $bd -> prepare('SELECT mot_de_passe FROM etudiant WHERE mot_de_passe=?');
            $repLogin->execute(array($code));
            $repPass->execute(array($password));
            $recupLogin=$repLogin->fetchAll();
            $recupPass=$repPass->fetchAll();

            if(count($recupLogin)>0 && count($recupPass)>0){
                return true;
            }else{return false;}   

        }

        function verifierEnseignant($code, $password){

            global $bd;
            $repLogin = $bd -> prepare('SELECT matricule FROM enseignant WHERE matricule=?');
            $repPass = $bd -> prepare('SELECT mot_de_passe FROM enseignant WHERE mot_de_passe=?');
            $repLogin->execute(array($code));
            $repPass->execute(array($password));
            $recupLogin=$repLogin->fetchAll();
            $recupPass=$repPass->fetchAll();

            if(count($recupLogin)>0 && count($recupPass)>0){
                return true;
            }else{return false;}   
        }

        function verifierConnexion($ident,$pass){
            if(verifierAdmin($ident,$pass)){
                unset($_SESSION["message"]);
                $_SESSION['identifiant']=$ident;
                $_SESSION['password']=$pass;
                header("location:admin/docs/acceuilAdmin.php");
                
            
            }elseif(verifierEnseignant($ident,$pass)){
                unset($_SESSION["message"]);
                $_SESSION['identifiant']=$ident;
                $_SESSION['password']=$pass;
                header("location: Paco/acceuil_proffesseur1p.php");

            }elseif (verifierEtudiant($ident,$pass)){
                unset($_SESSION["message"]);
                $_SESSION['identifiant']=$ident;
                $_SESSION['password']=$pass;
                 header("location: accueil_etudiant.php");
 
            }else{
                $_SESSION["message"]=" ";
                header("location: connexion.php");
            }
        }

        function insererEnseignant($matricule, $password, $nom, $prenom, $adresse, $telephone){
            global $bd;
            $insert = $bd -> prepare("INSERT INTO enseignant(matricule, mot_de_passe, nom, prenom, adresse, telephone)
            VALUES(:matri, :pass, :nom, :prenom, :adresse, :telephone)");

            $insert -> execute([
                ':matri'  => $matricule, 
                ':pass'  => $password, 
                ':nom'  => $nom, 
                ':prenom'  => $prenom, 
                ':adresse'  => $adresse, 
                ':telephone'  => $telephone,
            ]);
        }

        function insererEtudiant($matricule, $password, $nom, $prenom, $ddn, $ldn, $classe){
            global $bd;
            $insert = $bd -> prepare("INSERT INTO etudiant(code_etudiant, mot_de_passe, nom, prenom, date_naissance, lieu_naissance, code_classe)
            VALUES(:matri, :pass, :nom, :prenom, :ddn, :ldn, :class)");  
            
            $insert -> execute([
                ':matri'  => $matricule, 
                ':pass'  => $password, 
                ':nom'  => $nom, 
                ':prenom'  => $prenom, 
                ':ddn'  => $ddn,
                ':ldn'  => $ldn,
                ':class'    => $classe,
            ]);
        }

        function updateEtudiant($matricule, $newClasse){
            global $bd;
            $insert = $bd -> prepare("UPDATE etudiant SET classe=? WHERE code_etudiant=?");
            $insert -> execute([$newClasse, $matricule]);
        }

        function insererQuestion($numero, $libelle, $points, $malus, $duree, $exam){
            global $bd;
            $insert = $bd -> prepare("INSERT INTO question(numero, libelle, nb_points, malus, duree, code_examen)
            VALUES(:num, :lib, :pts, :mal, :duree, :exam)");

            $insert -> execute ([
                ':num'  => $numero, 
                ':lib' => $libelle, 
                ':pts' => $points, 
                ':mal' => $malus, 
                ':duree' => $duree, 
                ':exam' => $exam,
            ]);
        }

        /*function insererReponse($bd, $numero, $etudiant, $libelle, $question){
            $insert = $pdo -> prepare("INSERT INTO question(numero, reponse_etudiant, bonne_reponse, numero_question)
            VALUES(:num, :lib, :pts, :mal, :duree, :exam)");

            $insert -> execute ([
                ':num'  => $numero, 
                ':lib' => $libelle, 
                ':pts' => $points, 
                ':mal' => $malus, 
                ':duree' => $duree, 
                ':exam' => $exam,
            ]);
        }*/


        //exam related
        function verifierReponse($repEtudiant, $repEnseignant, $numero){
            global $bd;
            $stmt = $bd -> prepare('SELECT * FROM repenseignant WHERE reponse = ? AND id_question = ?');
            $stmt -> execute(array($repEnseignant, $numero));
            $d = $stmt -> fetch();
            $stmt2 = $bd -> prepare('SELECT * FROM repetudiant WHERE reponse = ? AND id_question = ?');
            $stmt2 -> execute(array($repEtudiant, $numero));
            $p = $stmt2 -> fetch();
            $rep = false;
            if($p == $d){
                $rep = true;
            }
            return $rep;
        }

        function calculerNote($codeExam, $codeEtudiant, $question, $repEnseignant, $numero){
            global $bd;
            $stmt = $bd -> prepare('SELECT reponse FROM repenseignant WHERE reponse = ? AND id_question = ?');
            $stmt -> execute(array($repEnseignant, $numero));
            $d = $stmt -> fetch_array();
            $vd = sizeof($d);
            $stmt2 = $bd -> prepare('SELECT reponse FROM repetudiant WHERE reponse = ? AND id_question = ?');
            $stmt2 -> execute(array($repEnseignant, $numero));
            $p = $stmt2 -> fetch_array();
            $vp = sizeof($p);
            $note = 0;
            for ($i = 0; $i < $vd; $i++){
                if(verifierReponse($repEtudiant, $repEnseignant, $numero) == true){
                    $note = $note + $d[$i];
                }
                else{
                    $note = $note + $p[$i];
                }
            }
            return $note;
        }

        function  nbrExam($codeClasse){
            global $bd;
           $stmt = $bd -> prepare ('SELECT COUNT(*) FROM examen2 WHERE code_classe = ? AND fait=?');
           $stmt -> execute(array($codeClasse,'0'));
           $d = $stmt -> fetch();
           return $d[0];
        }

        // function  infoExam($codeClasse){
        //     global $bd;
        //     $stmt = $bd -> prepare('SELECT nom_cours, date_debut, date_fin FROM examen2 WHERE code_classe = ?');
        //     $stmt -> execute(array($codeClasse));
        //     $d = $stmt -> fetchAll();

        //     foreach ($d as $row){
        //         $_SESSION['nom_cours']=$row["nom_cours"];
        //         $_SESSION['date_cours']=$row["date_debut"];
        //         $_SESSION['date_fin']=$row["date_fin"];
        //     }
            
        // }

        function infoExamNom($codeClasse,$j){
            global $bd;
            $stmt = $bd -> prepare('SELECT nom_cours FROM examen2 WHERE code_classe = ? AND fait=? ORDER BY date_debut');
            $stmt -> execute(array($codeClasse, '0'));
            $d = $stmt -> fetchAll();
            return $d[$j];
        }

        function infoExamDebut($codeClasse, $j){
            global $bd;
            $stmt = $bd -> prepare('SELECT date_debut FROM examen2 WHERE code_classe = ? AND fait=? ORDER BY date_debut' );
            $stmt -> execute(array($codeClasse,'0'));
            $d = $stmt -> fetchAll();
            return $d[$j];
        }

        function infoExamFin($codeClasse, $j){
            global $bd;
            $stmt = $bd -> prepare('SELECT date_fin FROM examen2 WHERE code_classe = ? AND fait=? ORDER BY date_debut');
            $stmt -> execute(array($codeClasse,'0'));
            $d = $stmt -> fetchAll();
            return $d[$j];
        }

        function infoExamCode($codeClasse,$j){
            global $bd;
            $stmt = $bd -> prepare('SELECT code_examen FROM examen2 WHERE code_classe = ? AND fait=? ORDER BY date_debut');
            $stmt -> execute(array($codeClasse, '0'));
            $d = $stmt -> fetchAll();
            return $d[$j];
        }

        function  nbrQuestion($codeExam){
            global $bd;
           $stmt = $bd -> prepare ('SELECT COUNT(*) FROM question WHERE code_examen = ?');
           $stmt -> execute(array($codeExam));
           $d = $stmt -> fetch();
           return $d[0];
        }

        function typeQuestion($codeExam,$j){
            global $bd;
            $stmt = $bd -> prepare('SELECT qcm FROM question WHERE code_examen = ?');
            $stmt -> execute(array($codeExam));
            $d = $stmt -> fetchAll();
            if($d[$j][0]==1){
                return true;
            }else{
                return false;
            }
        }

        function infoQuestionCode($codeExam,$j){
            global $bd;
            $stmt = $bd -> prepare('SELECT id_question FROM question WHERE code_examen = ?');
            $stmt -> execute(array($codeExam));
            $d = $stmt -> fetchAll();
            return $d[$j];
        }

        function  nbrReponse($codeQuestion){
            global $bd;
           $stmt = $bd -> prepare ('SELECT COUNT(*) FROM repenseignant WHERE id_question = ?');
           $stmt -> execute(array($codeQuestion));
           $d = $stmt -> fetch();
           return $d[0];
        }

        function libelleQuestion($codeExam,$j){
            global $bd;
            $stmt = $bd -> prepare('SELECT libelle FROM question WHERE code_examen = ?');
            $stmt -> execute(array($codeExam));
            $d = $stmt -> fetchAll();
            return $d[$j];
        }

        function libelleReponse($codeQuestion,$j){
            global $bd;
            $stmt = $bd -> prepare('SELECT reponse FROM repenseignant WHERE id_question = ?');
            $stmt -> execute(array($codeQuestion));
            $d = $stmt -> fetchAll();
            return $d[$j];
        }



        function recupMatiere($bd){
            global $bd;
            $stmt = $bd -> prepare('SELECT nom_cours FROM matiere');
            $stmt -> execute();
            $d = $stmt -> fetchColumn();
            $i = 0;
            foreach ($d as $row){
                $tableau[] = $row['nom_cours'];
            }      
            $size = count($tableau);
            while($i < $size){
                $_SESSION[$i] = $tableau[$i];
                $i++;
            }
        }

        function supprimerEleve($bd, $codeEtudiant){
            global $bd;
            $stmt = $bd -> prepare("DELETE FROM etudiant WHERE code_etudiant = ?");
            $stmt -> execute(array($codeEtudiant));
        }

        function supprimerEnseignant($bd, $matricule){
            global $bd;
            $stmt = $bd -> prepare("DELETE FROM enseignant WHERE matricule = ?");
            $stmt -> execute(array($matricule));
        }

        function supprimerClasse($bd, $codeClasse){
            global $bd;
            $stmt = $bd -> prepare("DELETE FROM classe WHERE code_classe = ?");
            $stmt -> execute(array($codeClasse));
        }

        function  nomEtudiant($ident){
            global $bd;
           $stmt = $bd -> prepare ('SELECT prenom FROM etudiant WHERE code_etudiant = ?');
           $stmt -> execute(array($ident));
           $d = $stmt -> fetch();
           return $d[0];
        }

        function modifFait($codeExam,$date_fin){
            $dateFin=date_create($date_fin);
            $dateFinf=date_format($dateFin,"U");
            if (time() >= $dateFinf){
                global $bd;
                $update = $bd -> prepare('UPDATE examen2 SET fait = "1" WHERE code_examen = ?');
                $update -> execute(array($codeExam));
            }
        }

        function fait($codeExam){
            global $bd;
                $update = $bd -> prepare('UPDATE examen2 SET fait = "1" WHERE code_examen = ?');
                $update -> execute(array($codeExam));
        }

        function DureeQuestions($code_exam,$j){
            global $bd;
            $reponse = $bd -> prepare("SELECT duree FROM question WHERE code_examen=?") ;
            $reponse -> execute(array($code_exam));
            $quest = $reponse -> fetchAll() ;
            return $quest[$j];
        }
    ?>



<?php
//appel des fonctions

        //verification connexion
    if (isset($_POST["identifiant"])) {
        verifierConnexion($_POST['identifiant'],$_POST['password']);
    }
?>
</body>
</html>