<?php
// Fichier: config.php

// ⚠️ MODIFIEZ CES PARAMÈTRES AVEC LES VRAIS IDENTIFIANTS FOURNIS PAR VOTRE HÉBERGEUR
define('DB_SERVER', 'localhost');      // Généralement 'localhost' pour l'hébergement mutualisé
define('DB_USERNAME', 'root'); // EX: u12345678_mineblock
define('DB_PASSWORD', ''); // EX: P@ssw0rdComplexe!
define('DB_NAME', 'account');     // EX: db_mineblock

// Tentative de connexion à la base de données MySQL
$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Vérification de la connexion
if($link->connect_error){
    // Vous pouvez remplacer ce message par une redirection vers une page d'erreur en production
    die("ERREUR: Impossible de se connecter à la base de données. Veuillez vérifier config.php et la base de données. " . $link->connect_error);
}

// Définir l'encodage pour supporter l'UTF-8
$link->set_charset("utf8mb4");
?>