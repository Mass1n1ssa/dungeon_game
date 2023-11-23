<?php

class PersonnageDAO {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function listerPersonnages(){
        $requete = $this->bdd->prepare("SELECT Personnages.*, Armes.nom AS nom_arme FROM Personnages LEFT JOIN Armes ON Personnages.arme_id = Armes.id");
        $requete->execute();
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($resultats as $resultat) {
            echo  " " . $resultat['id'] .
                  " - Nom : " . $resultat['nom'] . 
                  " - Points de vie : " . $resultat['points_de_vie'] . 
                  " - Points d'attaque : " . $resultat['points_attaque'] . 
                  " - Points de défense : " . $resultat['points_defense'] . 
                  " - Expérience : " . $resultat['experience'] . 
                  " - Niveau : " . $resultat['niveau'] . 
                  " - Arme : " . $resultat['nom_arme'] . "\n";
        }
    
        $idChoisi = readline("Choisissez le numéro du personnage avec lequel vous voulez combattre : ");
    
        $requete = $this->bdd->prepare("SELECT * FROM personnages WHERE id = ?");
        $requete->execute([$idChoisi]);
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
    
        echo "L'aventure va commencer avec le personnage : " . $resultat['nom'] . "\n";
    }

    


}

?>
