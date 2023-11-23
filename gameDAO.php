<?php

class PersonnageDAO {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function listerPersonnages(){
        $requete = $this->bdd->prepare("SELECT * FROM personnages");
        $requete->execute();
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($resultats as $resultat) {
            echo  " - Nom : " . $resultat['nom'] . 
                  " - Points de vie : " . $resultat['points_de_vie'] . 
                  " - Points d'attaque : " . $resultat['points_attaque'] . 
                  " - Points de défense : " . $resultat['points_defense'] . 
                  " - Expérience : " . $resultat['experience'] . 
                  " - Niveau : " . $resultat['niveau'] . "\n";
        }
    }

}

?>