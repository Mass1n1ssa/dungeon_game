<?php

class PersonnageDAO {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function listerPersonnages(){
        $requete = $this->bdd->prepare("SELECT * FROM Personnages");
        $requete->execute();
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($resultats as $resultat) {
            echo  " " . $resultat['id'] .
                  " - Nom : " . $resultat['nom'] . 
                  " - Points de vie : " . $resultat['points_de_vie'] . 
                  " - Points d'attaque : " . $resultat['points_attaque'] . 
                  " - Points de défense : " . $resultat['points_defense'] . 
                  " - Expérience : " . $resultat['experience'] . 
                  " - Niveau : " . $resultat['niveau'] . "\n";
        }
    
        $idChoisi = readline("Choisissez le numéro du personnage avec lequel vous voulez combattre : ");
    
        $requete = $this->bdd->prepare("SELECT * FROM personnages WHERE id = ?");
        $requete->execute([$idChoisi]);
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
    
        echo "L'aventure va commencer avec le personnage : " . $resultat['nom'] . "\n";
    }
    

    public function equiperArme($personnageId, $armeId) {
        // Vérifier si l'arme existe et si le personnage peut l'équiper
        $requete = $this->bdd->prepare("SELECT * FROM Armes WHERE id = :armeId AND niveau_minimum_requis <= (SELECT niveau FROM Personnages WHERE id = :personnageId)");
        $requete->bindParam(':armeId', $armeId, PDO::PARAM_INT);
        $requete->bindParam(':personnageId', $personnageId, PDO::PARAM_INT);
        $requete->execute();
        $arme = $requete->fetch(PDO::FETCH_ASSOC);

        if ($arme) {
            // Équiper l'arme au personnage
            $updateArme = $this->bdd->prepare("UPDATE Personnages SET arme = :armeId WHERE id = :personnageId");
            $updateArme->bindParam(':armeId', $armeId, PDO::PARAM_INT);
            $updateArme->bindParam(':personnageId', $personnageId, PDO::PARAM_INT);
            $updateArme->execute();

            echo "L'arme " . $arme['nom'] . " a été équipée avec succès par le personnage.\n";
        } else {
            echo "Impossible d'équiper l'arme. Vérifiez les conditions requises.\n";
        }
    }

}

?>
