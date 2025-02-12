<?php
    session_start();
if (isset($_POST['latitude'])) {

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupérer les données de localisation envoyées via la requête Ajax
    if (isset($_POST['latitude'])) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
    } else {
        echo 'non';
    }

    // Requête SQL pour insérer les données de localisation dans la table "users"
    $sql = "UPDATE users SET latitude = ?, longitude = ? WHERE id = ?"; 

    // Préparer la requête
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    // Liaison des paramètres
    $stmt->bind_param('sss', $latitude, $longitude, $_SESSION['user_id']);

    // Exécutez la requête
    if ($stmt->execute()) {
        echo "Données de localisation mises à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour des données de localisation : " . $stmt->error;
    }

    // Fermez la connexion
    $stmt->close();
    $conn->close();

    $_SESSION["user_latitude"] = $latitude;
    $_SESSION["user_longitude"] = $longitude;
};
?>

