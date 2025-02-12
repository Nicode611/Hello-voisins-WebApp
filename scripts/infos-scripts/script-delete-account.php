<?php

if (isset($_POST["delete-account-btn"])) {
    session_start();

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $id = $_SESSION['user_id'];

    $query = "DELETE FROM users WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        session_destroy();
        header("Location: ../../index.php");
        exit();
    } else {
        $_SESSION["error"] = "<p class='error'>Changement non effectués !</p>";
        $stmt->close();
        $conn->close();
        header("Location: ../../pages/self-profile.php");
        exit();
    }
}

?>
