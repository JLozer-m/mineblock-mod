<?php
// Fichier: register.php
session_start();
require_once "config.php";

// Si le formulaire n'a pas été soumis, rediriger l'utilisateur
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("location: index.html");
    exit;
}

// Récupération et nettoyage des données
$pseudo = trim($_POST["pseudo"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

// 1. Validation de base
if (empty($pseudo) || empty($email) || empty($password) || empty($confirm_password)) {
    die("Erreur: Veuillez remplir tous les champs requis.");
}
if ($password !== $confirm_password) {
    die("Erreur: Les mots de passe ne correspondent pas.");
}
if (strlen($password) < 6) {
    die("Erreur: Le mot de passe doit contenir au moins 6 caractères.");
}

// 2. Vérification de l'unicité (Email et Pseudo)
$sql_check = "SELECT id FROM Account WHERE Email = ? OR idPseudo = ?";
if ($stmt_check = $link->prepare($sql_check)) {
    $stmt_check->bind_param("ss", $email, $pseudo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        die("Erreur: Cette adresse Email ou ce Nom d'utilisateur est déjà utilisé.");
    }
    $stmt_check->close();
}

// 3. HACHAGE SÉCURISÉ DU MOT DE PASSE (CRUCIAL!)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 4. Insertion des données
// Note : Le champ idUser est laissé NULL car il est facultatif selon votre structure SQL.
$sql_insert = "INSERT INTO Account (idPseudo, Email, Password) VALUES (?, ?, ?)";

if ($stmt_insert = $link->prepare($sql_insert)) {
    $stmt_insert->bind_param("sss", $pseudo, $email, $hashed_password);

    if ($stmt_insert->execute()) {
        // Enregistrement réussi, connexion automatique et redirection
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $link->insert_id; // Récupère l'ID nouvellement créé
        $_SESSION["pseudo"] = $pseudo;
        $_SESSION["email"] = $email;
        
        // Redirection vers l'accueil après l'inscription
        header("location: index.html"); 
    } else {
        // Erreur d'exécution de la requête
        die("Erreur lors de l'enregistrement du compte: " . $stmt_insert->error);
    }
    $stmt_insert->close();
}

$link->close();
?>