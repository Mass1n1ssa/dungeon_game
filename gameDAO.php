<?php

class PersonnageDAO {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function LancementJeu(){
        $choix = readline("Voulez-vous Commencer l'aventure ? (oui/non) : ");
        if ($choix == "oui") {
            $this->listerPersonnages();
        } else {
            echo "Aurevoir !";
        }
    }

    public function listerPersonnages(){
        $requete = $this->bdd->prepare("SELECT Personnages.id, Personnages.nom, Personnages.points_de_vie, Personnages.points_attaque, Personnages.points_defense, Personnages.experience, Personnages.niveau, Armes.nom AS nom_arme FROM Personnages LEFT JOIN Armes ON Personnages.arme_id = Armes.id");
        $requete->execute();
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($resultats as $resultat) {
            echo  " " . $resultat['id'] .
                  " - Nom : " . $resultat['nom'] ."\n". 
                  " - Points de vie : " . $resultat['points_de_vie'] ."\n" . 
                  " - Points d'attaque : " . $resultat['points_attaque']."\n" . 
                  " - Points de défense : " . $resultat['points_defense']."\n" . 
                  " - Expérience : " . $resultat['experience']."\n" . 
                  " - Niveau : " . $resultat['niveau']."\n" . 
                  " - Arme : " . $resultat['nom_arme'] . "\n";
        }
    
        $idChoisi = readline("Choisissez le numéro du personnage avec lequel vous voulez combattre : ");
    
        $requete = $this->bdd->prepare("SELECT * FROM Personnages WHERE id = ?");
        $requete->execute([$idChoisi]);
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
    
        echo "L'aventure va commencer avec le personnage : " . $resultat['nom'] . "\n";

        $choix = readline("Maintenant vous etes préts à commencer l'aventure ! (oui/non) : ");
        if ($choix == "oui") {
            $this->startGame();
        } else {
            echo "Aurevoir !";
        }
    }

    public function startGame(){
        $choix = intval(readline("Que voulez-vous faire ? (1. Marcher / 2. Sauvegarder / 3. Quitter) : "));
    
        if ($choix === 1) {
            $this->marcher();
        } else if ($choix === 2) {
            $this->sauvegarder();
        } else if ($choix === 3) {
            echo "Aurevoir !";
        } else {
            echo "Veuillez choisir une option valide !";
            $this->startGame();
        }
    }

        public function marcher(){
            $requete = $this->bdd->prepare("SELECT * FROM salles");
            $requete->execute();
            $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);

            $salle = $resultats[rand(0, count($resultats) - 1)];

           echo "Vous arrivez dans une salle : " . $salle['type'] . "\n";
               
                if ($salle['type'] === "monstre") {
                    $this->combat();
                } else if ($salle['type'] === "enigme") {
                    $this->enigme();
                } else if ($salle['type'] === "piege") {
                    $this->piege();
                } else if ($salle['type'] === "marchand") {
                    $this->marchand();
                } else {
                    echo "Vous continuez votre chemin !\n";
                    $this->startGame();
                }
            
        }
    public function combat(){
        
    }
       
    
}

?>
