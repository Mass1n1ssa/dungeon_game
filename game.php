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

    $personnageDAO = new PersonnageDAO($connexion);

    echo"Bienvenue dans le Donjon !\n";
    echo"Choisissez votre personnage :\n";


    $personnageDAO->listerPersonnages();
