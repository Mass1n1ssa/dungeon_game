<?php
    include "config.php";
    include "gameDAO.php";

    Class Personnage{
        private $nom;
        private $pv;
        private $pa;
        private $pd;
        private $xp;
        private $nv;
        private $arme;

        public function __construct($nom, $pv, $pa, $pd, $xp, $niveau){
            $this->nom = $nom;
            $this->pv = $pv;
            $this->pa = $pa;
            $this->pd = $pd;
            $this->xp = $xp;
            $this->nv = $nv;
            $this->arme = $arme;
        }
    }


    Class Salle {
        private $description;
        private $monstre;
        private $enigme;
        private $piege;
        private $marchand;
        
        public function __construct($description, $monstre, $enigme, $piege, $marchand){
            $this->description = $description;
            $this->monstre = $monstre;
            $this->enigme = $enigme;
            $this->piege = $piege;
            $this->marchand = $marchand;
        }
    }
    