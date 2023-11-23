<?php

include "config.php";
include "gameDAO.php";

    class Personnage {
        private $id;
        private $nom;
        private $pv;
        private $pa;
        private $pd;
        private $exp;
        private $nv;
        private $arme;
    
        public function __construct($id, $nom, $pv, $pa, $pd, $exp, $nv, $arme) {
            $this->id = $id;
            $this->nom = $nom;
            $this->pv = $pv;
            $this->pa = $pa;
            $this->pd = $pd;
            $this->exp = $exp;
            $this->nv = $nv;
            $this->arme = $arme;
        }

        public function getId() {
            return $this->id;
        }

        public function getNom() {
            return $this->nom;
        }

        public function getPv() {
            return $this->pv;
        }

        public function getPa() {
            return $this->pa;
        }

        public function getPd() {
            return $this->pd;
        }

        public function getExp() {
            return $this->exp;
        }

        public function getNv() {
            return $this->nv;
        }

        public function getArme() {
            return $this->arme;
        }
    }

    Class Salle{
        private $description;
        private $monstre;
        private $enigme;
        private $piege;
        private $marchand;
    
        public function __construct($description, $monstre, $enigme, $piege, $marchand) {
            $this->description = $description;
            $this->monstre = $monstre;
            $this->enigme = $enigme;
            $this->piege = $piege;
            $this->marchand = $marchand;
        }
    

        public function getDescription() {
            return $this->description;
        }

        public function getMonstre() {
            return $this->monstre;
        }

        public function getEnigme() {
            return $this->enigme;
        }

        public function getPiege() {
            return $this->piege;
        }

        public function getMarchand() {
            return $this->marchand;
        }
    }

    class EnigmeS extends Salle {
        private $question;
        private $reponse;

        public function __construct($description, $monstre, $enigme, $piege, $marchand, $question, $reponse) {
            parent::__construct($description, $monstre, $enigme, $piege, $marchand);
            $this->question = $question;
            $this->reponse = $reponse;
        }

        public function getQuestion() {
            return $this->question;
        }

        public function getReponse() {
            return $this->reponse;
        }

    }
    
    class PiegeS extends Salle {
    }
    
    class MarchandS extends Salle {
       
    }

    Class MonstreS extends Salle {

    }
    
    // $personnageDAO = new PersonnageDAO($connexion);

    // echo"Bienvenue dans le Donjon !\n";
    // echo"Voici les personnages Disponibles :\n";


    // $personnageDAO->listerPersonnages();
    // $quitter = false;

    // while (!$quitter) {
    //     echo "1. Commencer une nouvelle partie\n";
    //     echo "2. Sauvegarder\n";
    //     echo "3. Recharger\n";
    //     echo "4. Quitter\n";

    //     $choix = readline("Choisissez une option : ");

    //     switch ($choix) {
    //         case '1':
    //             echo "Nouvelle partie...\n";
                
    //             break;
    //         case '2':
    //             echo "Sauvegarde...\n";
    //             // Mettre ici votre code pour sauvegarder
    //             break;
    //         case '3':
    //             echo "Charger...\n";
    //             // Mettre ici votre code pour recharger la partie
    //             break;
    //         case '4':
    //             echo "À bientôt !\n";
    //             $quitter = true;
    //             break;
    //         default:
    //             echo "Option invalide. Veuillez choisir une option valide.\n";
    //             break;
    //     }
    // }

    $donjon = new Donjon($connexion);
    // $donjon->marcher();
    $donjon->marcher();
?>


