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
                }
                else{ 
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
            } else if ($resultat['type'] == "piege") {
                $this->activePiege($idChoisi);    
            } else if ($resultat['type'] == "marchand") {
                $this->marchand($idChoisi);
            } else if ($resultat['type'] == "monstre") {
                $this->combat($idChoisi);
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

        
        public function activePiege($idChoisi) {
            $requete = $this->bdd->prepare("SELECT points_de_vie FROM Personnages WHERE id = ?");
            $requete->execute([$idChoisi]);
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        
            $points_de_vie = $resultat['points_de_vie'] - 10;
        
            $requeteUpdate = $this->bdd->prepare("UPDATE Personnages SET points_de_vie = ? WHERE id = ?");
            $requeteUpdate->execute([$points_de_vie, $idChoisi]);
        
            echo "En marchant sur un piège, vous perdez 10 points de vie !\n";
        }


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
        
            // Retrieve the level of the player from the database
            $requeteNiveau = $this->bdd->prepare("SELECT niveau FROM Personnages WHERE id = ?");
            $requeteNiveau->execute([$idPersonnage]);
            $resultatNiveau = $requeteNiveau->fetch(PDO::FETCH_ASSOC);
        
            $niveauPersonnage = $resultatNiveau['niveau'];
        
            // Récupérer tous les objets dans l'inventaire du personnage
            $requeteObjetsInventaire = $this->bdd->prepare("SELECT * FROM inventaire WHERE personnage_id = ?");
            $requeteObjetsInventaire->execute([$idChoisi]);
            $objetsInventaire = $requeteObjetsInventaire->fetchAll(PDO::FETCH_ASSOC);
        
            // Récupérer l'objet choisi dans l'inventaire du personnage
            $objetChoisi = $objetsInventaire[(int)$choixInventaire - 1];
        
            // Ajoutez la logique pour vérifier si l'échange est possible (niveau requis, etc.)
            if ($objetMarchand['niveau_requis'] > $niveauPersonnage) {
                echo "Vous n'avez pas le niveau requis pour effectuer cet échange.\n";
                return;
            }
        
            // Mettez à jour la table d'inventaire du personnage
            // Supprimez l'objet choisi de l'inventaire
            $requeteSuppression = $this->bdd->prepare("DELETE FROM inventaire WHERE id = ?");
            $requeteSuppression->execute([$objetChoisi['id']]);

            // Ajoutez l'objet du marchand dans l'inventaire
            $requeteAjout = $this->bdd->prepare("INSERT INTO inventaire (personnage_id, objet_id, quantite) VALUES (?, ?, 1)");
            $requeteAjout->execute([$idPersonnage, $objetMarchand['id']]);

            echo "Vous avez échangé avec le marchand et obtenu : " . $objetMarchand['nom'] . "\n";
        }
        

        public function combat($idChoisi){
            $requete = $this->bdd->prepare("SELECT * FROM monstres ORDER BY RAND() LIMIT 1");
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        
            $requeteStatsPersonnage = $this->bdd->prepare("SELECT points_de_vie, points_attaque, points_defense FROM Personnages WHERE id = ?");
            $requeteStatsPersonnage->execute([$idChoisi]);
            $statsPersonnage = $requeteStatsPersonnage->fetch(PDO::FETCH_ASSOC);
        
            $pointsViePersonnage = $statsPersonnage['points_de_vie'];
            $attaquePersonnage = $statsPersonnage['points_attaque'];
            $defensePersonnage = $statsPersonnage['points_defense'];
        
            // Récupérer les statistiques du monstre
            $requeteStatsMonstre = $this->bdd->prepare("SELECT points_de_vie, points_attaque, points_defense FROM monstres ORDER BY points_de_vie ASC");
            $requeteStatsMonstre->execute();
            $statsMonstre = $requeteStatsMonstre->fetch(PDO::FETCH_ASSOC);
        
            $pointsVieMonstre = $statsMonstre['points_de_vie'];
            $attaqueMonstre = $statsMonstre['points_attaque'];
            $defenseMonstre = $statsMonstre['points_defense'];
        
            echo "Vous entrez dans une salle : " . $resultat['nom'] . "\n";

    $personnageTour = true;
    $objetsInventaire = $this->afficherInventaire($idChoisi);

    while ($pointsViePersonnage > 0 && $pointsVieMonstre > 0) {
        if ($personnageTour) {
            $choixAction = strtolower(readline("Voulez-vous faire ? (attaquer/défendre/Inventaire) : "));

            if ($choixAction === "attaquer") {
                $degats = max(0, $attaquePersonnage - $defenseMonstre);
                $pointsVieMonstre -= $degats;
                echo "Vous avez infligé " . $degats . " points de dégâts au monstre!\n";
            } elseif ($choixAction === "défendre") {
               // Logique pour la défense
               $defensePersonnage = $defensePersonnage + rand(5, 15); // Augmentez la défense du personnage aléatoirement
               echo "Vous vous êtes défendu contre l'attaque du monstre!\n";
            } elseif ($choixAction === "inventaire") {
                $this->afficherInventaire($idChoisi);
                $choixInventaire = intval(readline("Sélectionnez l'objet à utiliser (entrez le numéro) : ")) - 1;

                if (is_array($objetsInventaire) && $choixInventaire >= 0 && $choixInventaire < count($objetsInventaire)) {
                    $objetSelectionne = $objetsInventaire[$choixInventaire];

                    if ($objetSelectionne['type'] === 'arme') {
                        $attaquePersonnage += $objetSelectionne['points_attaque_bonus'];
                        echo "Vous avez équipé : " . $objetSelectionne['nom'] . " (+{$objetSelectionne['points_attaque_bonus']} points d'attaque)!\n";
                    } elseif ($objetSelectionne['type'] === 'potion') {
                        $pointsViePersonnage += $objetSelectionne['points_vie'];
                        echo "Vous avez utilisé une potion : " . $objetSelectionne['nom'] . " (+{$objetSelectionne['points_vie']} points de vie)!\n";
                    } else {
                        echo "Cet objet ne peut pas être utilisé pendant le combat.\n";
                    }
                } else {
                    echo "Choix d'inventaire invalide.\n";
                }
            } else {
                echo "Veuillez choisir une action valide (attaquer/défendre/inventaire) !\n";
                continue;
            }
            $personnageTour = false;
        } else {
                    // Tour du monstre
                    $actionMonstre = rand(0, 1);
                    if ($actionMonstre === 0) {
                        // Attaque du monstre
                        $degatsMonstre = max(0, $attaqueMonstre - $defensePersonnage);
                        $pointsViePersonnage -= $degatsMonstre;
                        echo "Le monstre vous a infligé " . $degatsMonstre . " points de dégâts!\n";
                    } else {
                        // Défense du monstre
                        $defenseMonstre = $defenseMonstre; // Augmentez la défense du monstre aléatoirement
                        echo "Le monstre se défend contre votre attaque!\n";
                    }
                    $personnageTour = true;
                }
        
                // Vérifier si le personnage a encore des points de vie
                if ($pointsViePersonnage <= 0) {
                    echo "Vous avez été vaincu par le monstre!\n";
                    break;
                }
        
                // Afficher les points de vie restants du personnage et du monstre pour continuer le combat
                echo "Points de vie restants : Personnage = " . $pointsViePersonnage . ", Monstre = " . $pointsVieMonstre . "\n";
            }
        }
    }
        
    
?>
