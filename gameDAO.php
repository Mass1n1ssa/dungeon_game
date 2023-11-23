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
        
            $requete = $this->bdd->prepare("SELECT * FROM Personnages WHERE id = ?");
            $requete->execute([$idChoisi]);
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        
            echo "L'aventure va commencer avec le personnage : " . $resultat['nom'] . "\n";
        }
    }

    Class Donjon {
        private $bdd;
    
        public function __construct($bdd) {
            $this->bdd = $bdd;
        }
    
        public function poserEnigme() {
            $requete = $this->bdd->prepare("SELECT * FROM enigmes ORDER BY RAND() LIMIT 1");
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
    
            echo $resultat['question'] . "\n";
            $reponseUtilisateur = trim(fgets(STDIN));
    
            if ($reponseUtilisateur == $resultat['reponse']) {
                echo "Bravo, c'est la bonne réponse !\n";
            } else {
                echo "Désolé, ce n'est pas la bonne réponse.\n";
            }
        }

        public function activePiege(){
            $this->pv -=30; 
        }

        public function marcher() {
            $requete = $this->bdd->prepare("SELECT * FROM salles ORDER BY RAND() LIMIT 1");
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
    
            echo "Vous entrez dans une salle : " . $resultat['description'] . "\n";

            if ($resultat['type'] == "enigme") {
                $this->poserEnigme();
            } else if ($resultat['type'] == "piege") {
                $this->activePiege();
                echo "En marchant sur un piége vous activez des épines qui font mal et vous en fait perdre 30 points de vie et vous aurez !\n";
            } else if ($resultat['type'] == "marchand") {
                echo "Vous êtes tombé sur un marchand !\n";
            } else if ($resultat['type'] == "monstre") {
                echo "Vous êtes tombé sur un monstre !\n";
            }
        }

    }

?>
