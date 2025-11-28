<?php
// Fichier: login.php
session_start();
require_once "config.php";

// Si le formulaire n'a pas été soumis, rediriger l'utilisateur
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("location: index.html");
    exit;
}

// Récupération des données
$login_identifier = trim($_POST["login_identifier"]);
$password = $_POST["password"];

// 1. Préparation de la requête
// Tente de trouver l'utilisateur par Email OU par idPseudo (Nom d'utilisateur)
$sql = "SELECT id, idPseudo, Email, Password FROM Account WHERE Email = ? OR idPseudo = ?";

if ($stmt = $link->prepare($sql)) {
    // On utilise le même identifiant deux fois
    $stmt->bind_param("ss", $login_identifier, $login_identifier);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Utilisateur trouvé
            $stmt->bind_result($id, $pseudo, $email, $hashed_password);
            $stmt->fetch();
            
            // 2. Vérification du mot de passe haché
            if ($hashed_password !== null && password_verify($password, $hashed_password)) {
                
                // Mot de passe valide. Démarrer la session.
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["pseudo"] = $pseudo;
                $_SESSION["email"] = $email;
                
                // Redirection vers la page d'accueil
                header("location: index.html"); 
            } else {
                // Mot de passe invalide
                echo "Identifiant ou mot de passe invalide.";
            }
        } else {
            // Utilisateur non trouvé
            echo "Identifiant ou mot de passe invalide.";
        }
    } else {
        die("Erreur de requête.");
    }
    $stmt->close();
}

$link->close();
?>