<?php

if (isset($_POST['idContact'])) { 

    session_start();

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $selfId = $_SESSION['user_id'];
    $contactId = $_POST["idContact"];
    $statut = 'waiting';

    // Requette pour ajouter le contact
    $sqlContact = "INSERT INTO contacts (added_by_user_id, added_user_id, statut) VALUES ( ?, ?, ?) ";

    $stmtContact = $conn->prepare($sqlContact);

    if ($stmtContact === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmtContact->bind_param('sss', $selfId, $contactId, $statut);

    if ($stmtContact->execute()) { 

        $ok = 'ok';
        echo json_encode($ok);

    } else {
        header('Location: ../../pages/proximity-chat.php');
    };

    
};