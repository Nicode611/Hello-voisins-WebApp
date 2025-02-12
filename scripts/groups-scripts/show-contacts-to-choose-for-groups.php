<?php

$includeFile = "../config/db/db.php";
if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$selfId = $_SESSION['user_id'];

$sqlContacts = "SELECT * FROM contacts WHERE added_user_id = $selfId AND statut = 'added' OR added_by_user_id = $selfId AND statut = 'added'";

$resultContacts = $conn->query($sqlContacts);

// On récupere toutes les contacts
if ($resultContacts->num_rows > 0) {
    while ($rowContacts = $resultContacts->fetch_assoc()) {

        $contactId = $rowContacts["id"];
        $contactAddedUserId = $rowContacts["added_user_id"];
        $contactAddedByUserId = $rowContacts["added_by_user_id"];
        $contactStatut = $rowContacts["statut"];

        if ($contactAddedByUserId == $selfId) { 

            $sqlUsers = "SELECT id, first_name, last_name FROM users WHERE id = $contactAddedUserId";

            $resultUsers = $conn->query($sqlUsers);

            // On récupere le nom et prénom de l'user selon l'id du contact dans la notif
            if ($resultUsers->num_rows > 0) { 
                $rowUsers = $resultUsers->fetch_assoc();

                $userFirstName = $rowUsers["first_name"];
                $userLastName = $rowUsers["last_name"];
                $userId = $rowUsers["id"];

            }; ?>

            <input type="checkbox" name="contacts[]" value="<?php echo $userId ?>"><?php echo $userFirstName . ' ' . $userLastName ?><br>

        <?php

        } else if ($contactAddedUserId == $selfId) { 

            $sqlUsers = "SELECT id, first_name, last_name FROM users WHERE id = $contactAddedByUserId";

            $resultUsers = $conn->query($sqlUsers);

            // On récupere le nom et prénom de l'user selon l'id du contact dans la notif
            if ($resultUsers->num_rows > 0) { 
                $rowUsers = $resultUsers->fetch_assoc();

                $userFirstName = $rowUsers["first_name"];
                $userLastName = $rowUsers["last_name"];
                $userId = $rowUsers["id"];

            }; ?>

            <input type="checkbox" name="contacts[]" value="<?php echo $userId ?>"><?php echo $userFirstName . ' ' . $userLastName ?><br>

            <?php
        }
    };
} else {
    echo "Vous n'avez pas encore de contacts";
};

    $conn->close();

?>