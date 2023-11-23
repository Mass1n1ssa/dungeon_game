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
        class Salle {
            private $description;
            private $monstre;
            private $enigme;
            private $piege;
            private $marchand;
        
            public function __construct($description) {
                $this->description = $description;
                $this->monstre = null;
                $this->enigme = null;
                $this->piege = null;
                $this->marchand = null;
            }
        

    }

 
    

    $personnageDAO = new PersonnageDAO($connexion);

    echo"Bienvenue dans le Donjon !\n";
    echo"Voici les personnages Disponibles :\n";


    $personnageDAO->listerPersonnages();
    // $personnageDAO->equiperArme(1, 1);
    $quitter = false;

while (!$quitter) {
    echo "1. Commencer une nouvelle partie\n";
    echo "2. Sauvegarder\n";
    echo "3. Recharger\n";
    echo "4. Quitter\n";

    $choix = readline("Choisissez une option : ");

    switch ($choix) {
        case '1':
            echo "Nouvelle partie...\n";
            
            break;
        case '2':
            echo "Sauvegarde...\n";
            // Mettre ici votre code pour sauvegarder
            break;
        case '3':
            echo "Charger...\n";
            // Mettre ici votre code pour recharger la partie
            break;
        case '4':
            echo "À bientôt !\n";
            $quitter = true;
            break;
        default:
            echo "Option invalide. Veuillez choisir une option valide.\n";
            break;
    }
}


