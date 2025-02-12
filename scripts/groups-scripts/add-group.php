<?php

if (isset($_POST['create_group'])) { 

    session_start();

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $selfId = $_SESSION['user_id'];
    $groupName = $_POST["groupName"];
    $contacts = $_POST['contacts'];
    $contacts = array_merge($contacts, array($selfId));
    $jsonData = json_encode($contacts);
    

    // Requette pour ajouter le contact
    $sql = "INSERT INTO groups (group_name, users_ids, admin_id) VALUES ( ?, ?, ?) ";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param('sss', $groupName, $jsonData, $selfId);

    if ($stmt->execute()) { 
        header('Location: ../../pages/groups.php');

    } else {
        header('Location: ../../pages/contacts.php');
    };

    
};