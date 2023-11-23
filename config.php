<?php
    try {
        $hote = "localhost";
        $utilisateur = "root";
        $motDePasse = "26082002";
        $dataBase = "game";

        $connexion = new PDO("mysql:host=$hote;dbname=$dataBase", $utilisateur, $motDePasse);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
?>