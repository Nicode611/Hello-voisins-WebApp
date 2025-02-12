<?php
session_start();

$includeFile = "../../config/db/db.php";
if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$selfId = $_SESSION['user_id'];

$sqlNotifsContacts = "SELECT * FROM contacts WHERE added_user_id = $selfId AND statut = 'waiting'";

$resultNotifsContacts = $conn->query($sqlNotifsContacts);

// On récupere toutes les notifs
if ($resultNotifsContacts->num_rows > 0) {
    while ($rowNotifsContacts = $resultNotifsContacts->fetch_assoc()) {

        $notifsContactId = $rowNotifsContacts["id"];
        $notifsAddedByUserId = $rowNotifsContacts["added_by_user_id"];
        $notifsContactStatut = $rowNotifsContacts["statut"];
        $notifsContactMessage = 'Souhaite vous ajouter à ses contacts';
        
        $sqlUsers = "SELECT first_name, last_name, profile_img_path FROM users WHERE id = $notifsAddedByUserId";

        $resultUsers = $conn->query($sqlUsers);

        // On récupere le nom et prénom de l'user selon l'id du contact dans la notif
        if ($resultUsers->num_rows > 0) { 
            $rowUsers = $resultUsers->fetch_assoc();

            $notifsUserFirstName = $rowUsers["first_name"];
            $notifsUserLastName = $rowUsers["last_name"];
            $notifsUserProfileImgPath = $rowUsers["profile_img_path"];

        };
            $notifData = array (
                "contactId" => $notifsContactId,
                "contactMessage" => $notifsContactMessage,
                "userFirstName" => $notifsUserFirstName,
                "userLastName" => $notifsUserLastName,
                "userProfileImgPath" => $notifsUserProfileImgPath
            );

            $response[] = $notifData;

        }
        echo json_encode($response);
        
    $conn->close();
} else {
    $noNotif = "Pas de notifications";
    echo json_encode($noNotif);
    $conn->close();
};

?>

