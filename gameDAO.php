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
                " - Niveau : " . $resultat['niveau']."\n";
                // " - Arme : " . $resultat['nom_arme'] . "\n";
            }
        
            $idChoisi = readline("Choisissez le numéro du personnage avec lequel vous voulez combattre : ");

            $requete = $this->bdd->prepare("SELECT * FROM Personnages WHERE id = ?");
            $requete->execute([$idChoisi]);
            $personnage = $requete->fetch(PDO::FETCH_ASSOC);
        
            echo "L'aventure va commencer avec le personnage : " . $personnage['nom'] . "\n";
        
            $choix = readline("Êtes-vous prêt à commencer l'aventure ? (oui/non) : ");
            if ($choix == "oui") {
                $this->startGame($idChoisi);
            } else {
                echo "Au revoir !";
            }
        }

        public function startGame($idChoisi){
            $requete = $this->bdd->prepare("SELECT points_de_vie FROM Personnages WHERE id = ?");
            $requete->execute([$idChoisi]);
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            $points_de_vie = $resultat['points_de_vie'];

            while ($points_de_vie > 0) {
                $choix = intval(readline("Que voulez-vous faire ? (1. Marcher / 2. Sauvegarder / 3. Quitter) : "));

                if ($choix === 1) {
                    $this->marcher();
                } else if ($choix === 2) {
                    // À définir : logique pour sauvegarder
                    echo "Vous avez sauvegardé.\n";
                } else if ($choix === 3) {
                    echo "Au revoir !";
                    break; // Sortir de la boucle si le joueur choisit de quitter
                } else {
                    echo "Veuillez choisir une option valide !\n";
                }
            }

            if ($points_de_vie <= 0) {
                echo "Vous n'avez plus de points de vie. Game Over!\n";
            }
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
                $this->combat();
            }
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
                return $this->startGame();
            }
        }
        
        public function activePiege(){
            $this->pv -=30; 
        }
        
        

        public function combat(){
            $requete = $this->bdd->prepare("SELECT * FROM personnages WHERE niveau = ?");
            $requete = $this->bdd->prepare("SELECT * FROM monstres ORDER BY RAND() LIMIT 1");
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            if ($resultat['niveau'] == $this->niveau) {
                echo "Vous êtes tombé sur un monstre : " . $resultat['nom'] . "\n";
            } else if ($resultat['niveau'] > $this->niveau) {
                echo "Vous êtes tombé sur un monstre : " . $resultat['nom'] . "\n";
            } else if ($resultat['niveau'] < $this->niveau) {

            echo "Vous êtes tombé sur un monstre : " . $resultat['nom'] . "\n";
    }
    }
}
?>
