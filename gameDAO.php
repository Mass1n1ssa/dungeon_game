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
            $requete = $this->bdd->prepare("SELECT Personnages.id, Personnages.nom, Personnages.points_de_vie, Personnages.points_attaque, Personnages.points_defense, Personnages.experience, Personnages.niveau FROM personnages");
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
            $this->startGame($idChoisi);
        }


        public function startGame($idChoisi){
            $requete = $this->bdd->prepare("SELECT points_de_vie FROM Personnages WHERE id = ?");
            $requete->execute([$idChoisi]);
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            $points_de_vie = $resultat['points_de_vie'];

            while ($points_de_vie > 0) {
                $choix = intval(readline("Que voulez-vous faire ? (1. Marcher / 2. Sauvegarder / 3. Quitter) : "));

                if ($choix === 1) {
                    $this->marcher($idChoisi);
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

        public function marcher($idChoisi) {
            $requete = $this->bdd->prepare("SELECT * FROM salles ORDER BY RAND() LIMIT 1");
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        
            echo "Vous entrez dans une salle : " . $resultat['description'] . "\n";
        
            if ($resultat['type'] == "enigme") {
                $this->poserEnigme($idChoisi);
            // } else if ($resultat['type'] == "piege") {
            //     $this->activePiege();
            //     echo "En marchant sur un piége vous activez des épines qui font mal et vous en fait perdre 30 points de vie et vous aurez !\n";
            } else if ($resultat['type'] == "marchand") {
                $this->marchand($idChoisi); // Passer l'argument $idChoisi
            // } else if ($resultat['type'] == "monstre") {
            //     $this->combat($idChoisi); // Passer l'argument $idChoisi
            // }
        }
    }
        
  

        public function poserEnigme($idChoisi) {
            $requete = $this->bdd->prepare("SELECT * FROM enigmes ORDER BY RAND() LIMIT 1");
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        
            echo $resultat['question'] . "\n";
            $reponseUtilisateur = trim(readline("Entrez votre réponse : "));
        
            if ($reponseUtilisateur == $resultat['reponse']) {
                echo "Bravo, c'est la bonne réponse !\n";
        
                // Sélection aléatoire d'un objet non déjà gagné par le joueur
                $requeteObjetAleatoire = $this->bdd->prepare("SELECT id, nom FROM objets WHERE id NOT IN (SELECT objet_id FROM inventaire WHERE personnage_id = ?) ORDER BY RAND() LIMIT 1");
                $requeteObjetAleatoire->execute([$idChoisi]);
                $objetGagne = $requeteObjetAleatoire->fetch(PDO::FETCH_ASSOC);
        
                if ($objetGagne) {
                    // Ajout de l'objet gagné dans l'inventaire du personnage
                    $requeteInsert = $this->bdd->prepare("INSERT INTO inventaire (personnage_id, objet_id, quantite) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE quantite = quantite + 1");
                    $requeteInsert->execute([$idChoisi, $objetGagne['id']]);
        
                    echo "Vous avez gagné un nouvel objet : " . $objetGagne['nom'] . " !\n";
                }
            } else {
                echo "Désolé, ce n'est pas la bonne réponse.\n";
                $this->marcher($idChoisi); // Revenir à la marche après une réponse incorrecte
            }
        }

        
        // public function activePiege(){
        //     $this->pv -=30; 
        // }
        
        

        // public function combat(){
        //     $requete = $this->bdd->prepare("SELECT * FROM personnages WHERE niveau = ?");
        //     $requete = $this->bdd->prepare("SELECT * FROM monstres ORDER BY RAND() LIMIT 1");
        //     $requete->execute();
        //     $resultat = $requete->fetch(PDO::FETCH_ASSOC);

        //     if ($resultat['niveau'] == $this->niveau) {
        //         echo "Vous êtes tombé sur un monstre : " . $resultat['nom'] . "\n";
        //     } else if ($resultat['niveau'] > $this->niveau) {
        //         echo "Vous êtes tombé sur un monstre : " . $resultat['nom'] . "\n";
        //     } else if ($resultat['niveau'] < $this->niveau) {

        //     echo "Vous êtes tombé sur un monstre : " . $resultat['nom'] . "\n";
        //     }
        // }

        public function marchand($idChoisi) {
            echo "Vous êtes tombé sur un marchand !\n";
            echo "Le marchand propose l'objet suivant :\n";
        
            $requeteObjets = $this->bdd->prepare("SELECT * FROM objets ORDER BY RAND() LIMIT 1");
            $requeteObjets->execute();
            $objetMarchand = $requeteObjets->fetch(PDO::FETCH_ASSOC);
        
            echo "1. " . $objetMarchand['nom'] . " - Points d'attaque bonus : " . $objetMarchand['points_attaque_bonus'] . "\n";
        
            $choixMarchand = strtolower(readline("Voulez-vous échanger un objet contre celui-ci ? (oui/non) : "));
        
            if ($choixMarchand == "oui") {
                $this->afficherInventaire($idChoisi);
                $choixInventaire = intval(readline("Choisissez le numéro de l'objet que vous souhaitez échanger : "));
        
                // À compléter : Logique pour effectuer l'échange avec l'inventaire du personnage
                $this->effectuerEchange($objetMarchand, $choixInventaire,$idChoisi);
            }
        }
        
        public function afficherInventaire($idChoisi) {
            $idPersonnage = $idChoisi; 
        
            $requeteInventaire = $this->bdd->prepare("SELECT objets.nom, inventaire.quantite FROM objets INNER JOIN inventaire ON objets.id = inventaire.objet_id WHERE inventaire.personnage_id = ?");
            $requeteInventaire->execute([$idPersonnage]);
            $objetsInventaire = $requeteInventaire->fetchAll(PDO::FETCH_ASSOC);
        
            echo "Votre inventaire :\n";
        
            foreach ($objetsInventaire as $index => $objet) {
                echo $index + 1 . ". " . $objet['nom'] . " - Quantité : " . $objet['quantite'] . "\n";
            }
        }        
        
        
        public function effectuerEchange($objetMarchand, $choixInventaire, $idChoisi) {
            $idPersonnage = $idChoisi; 
        
            // Récupérer l'objet choisi dans l'inventaire du personnage
            $requeteObjetChoisi = $this->bdd->prepare("SELECT * FROM inventaire WHERE personnage_id = ? LIMIT 1 OFFSET ?");
            $requeteObjetChoisi->execute([$idPersonnage, intval($choixInventaire) - 1]);
            $objetChoisi = $requeteObjetChoisi->fetch(PDO::FETCH_ASSOC);
        
            // À compléter : Ajoutez la logique pour vérifier si l'échange est possible (niveau requis, etc.)
            if ($objetMarchand['niveau_requis'] > $niveauPersonnage) {
                echo "Vous n'avez pas le niveau requis pour effectuer cet échange.\n";
                return;
            }
        
            // À compléter : Mettez à jour la table d'inventaire du personnage
            // Supprimez l'objet choisi de l'inventaire
            $requeteSuppression = $this->bdd->prepare("DELETE FROM inventaire WHERE id = ?");
            $requeteSuppression->execute([$objetChoisi['id']]);
        
            // Ajoutez l'objet du marchand dans l'inventaire
            $requeteAjout = $this->bdd->prepare("INSERT INTO inventaire (personnage_id, objet_id, quantite) VALUES (?, ?, 1)");
            $requeteAjout->execute([$idPersonnage, $objetMarchand['id']]);
        
            echo "Vous avez échangé avec le marchand et obtenu : " . $objetMarchand['nom'] . "\n";
        }
}
?>
