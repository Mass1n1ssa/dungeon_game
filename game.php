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


    }

    class Salle {
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

    class EnigmeSalle extends Salle {
        private $enigme;
    
        public function __construct($description, $enigme) {
            parent::__construct($description, null, $enigme, null, null);
            $this->enigme = $enigme;
        }
    
        public function getEnigme() {
            return $this->enigme;
        }
    }
    
    class PiegeSalle extends Salle {
        private $piege;
    
        public function __construct($description, $piege) {
            parent::__construct($description, null, null, $piege, null);
            $this->piege = $piege;
        }
    
        public function getPiege() {
            return $this->piege;
        }
    }
    
    class MarchandSalle extends Salle {
        private $marchand;
    
        public function __construct($description, $marchand) {
            parent::__construct($description, null, null, null, $marchand);
            $this->marchand = $marchand;
        }
    
        public function getMarchand() {
            return $this->marchand;
        }
    }
    
    $personnageDAO = new PersonnageDAO($connexion);

    echo"Bienvenue dans le Donjon !\n";
    echo"Voici les personnages Disponibles :\n";


    $personnageDAO->listerPersonnages();

?>

