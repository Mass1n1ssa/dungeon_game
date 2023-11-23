<?php

class PersonnageDAO {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function listerPersonnages(){
        $requete = $this->bdd->prepare("SELECT Personnages.id, Personnages.nom, Personnages.points_de_vie, Personnages.points_attaque, Personnages.points_defense, Personnages.experience, Personnages.niveau, Armes.nom AS nom_arme FROM Personnages LEFT JOIN Armes ON Personnages.arme_id = Armes.id");
        $requete->execute();
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($resultats as $resultat) {
            echo  " " . $resultat['id'] .
                  " - Nom : " . $resultat['nom'] . "\n" . 
                  " - Points de vie : " . $resultat['points_de_vie'] . "\n" . 
                  " - Points d'attaque : " . $resultat['points_attaque'] . "\n" . 
                  " - Points de défense : " . $resultat['points_defense'] . "\n" . 
                  " - Expérience : " . $resultat['experience'] . "\n" . 
                  " - Niveau : " . $resultat['niveau'] . "\n" . 
                  " - Arme : " . $resultat['nom_arme'] . "\n";
        }
    
        $idChoisi = readline("Choisissez le numéro du personnage avec lequel vous voulez combattre : ");
    
        $requete = $this->bdd->prepare("SELECT * FROM Personnages WHERE id = ?");
        $requete->execute([$idChoisi]);
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
    
        echo "L'aventure va commencer avec le personnage : " . $resultat['nom'] . "\n";
    }
    
    
    

}

?>
