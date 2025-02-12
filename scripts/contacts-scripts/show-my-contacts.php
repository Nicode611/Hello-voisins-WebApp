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

            $sqlUsers = "SELECT first_name, last_name, profile_img_path FROM users WHERE id = $contactAddedUserId";

            $resultUsers = $conn->query($sqlUsers);

            // On récupere le nom et prénom de l'user selon l'id du contact dans la notif
            if ($resultUsers->num_rows > 0) { 
                $rowUsers = $resultUsers->fetch_assoc();

                $userProfileImg = $rowUsers["profile_img_path"];
                $userFirstName = $rowUsers["first_name"];
                $userLastName = $rowUsers["last_name"];

            }; ?>

            <div class="contact">
                <span class="hide contact-id"><?php echo $contactId ?></span>
                <div class="contact-infos">
                    <img class="contact-img" src="../<?php echo $userProfileImg ?>" alt="">
                    <h4 id="contactName"><?php echo $userFirstName . ' ' . $userLastName ?></h4>
                </div>
                <div class="contact-actions">
                    <svg class="contacts<?php echo $contactId; ?>" id="startContactDiscussion" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 3.33782C15.5291 2.48697 13.8214 2 12 2C6.47715 2 2 6.47715 2 12C2 13.5997 2.37562 15.1116 3.04346 16.4525C3.22094 16.8088 3.28001 17.2161 3.17712 17.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L6.39939 20.8229C6.78393 20.72 7.19121 20.7791 7.54753 20.9565C8.88837 21.6244 10.4003 22 12 22C17.5228 22 22 17.5228 22 12C22 10.1786 21.513 8.47087 20.6622 7" stroke="#656565" stroke-width="1.5" stroke-linecap="round"></path> <path d="M8 12H8.009M11.991 12H12M15.991 12H16" stroke="#656565" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>

                    <svg class="" id="deleteContact" viewBox="0 2 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.96967 16.4697C6.67678 16.7626 6.67678 17.2374 6.96967 17.5303C7.26256 17.8232 7.73744 17.8232 8.03033 17.5303L6.96967 16.4697ZM13.0303 12.5303C13.3232 12.2374 13.3232 11.7626 13.0303 11.4697C12.7374 11.1768 12.2626 11.1768 11.9697 11.4697L13.0303 12.5303ZM11.9697 11.4697C11.6768 11.7626 11.6768 12.2374 11.9697 12.5303C12.2626 12.8232 12.7374 12.8232 13.0303 12.5303L11.9697 11.4697ZM18.0303 7.53033C18.3232 7.23744 18.3232 6.76256 18.0303 6.46967C17.7374 6.17678 17.2626 6.17678 16.9697 6.46967L18.0303 7.53033ZM13.0303 11.4697C12.7374 11.1768 12.2626 11.1768 11.9697 11.4697C11.6768 11.7626 11.6768 12.2374 11.9697 12.5303L13.0303 11.4697ZM16.9697 17.5303C17.2626 17.8232 17.7374 17.8232 18.0303 17.5303C18.3232 17.2374 18.3232 16.7626 18.0303 16.4697L16.9697 17.5303ZM11.9697 12.5303C12.2626 12.8232 12.7374 12.8232 13.0303 12.5303C13.3232 12.2374 13.3232 11.7626 13.0303 11.4697L11.9697 12.5303ZM8.03033 6.46967C7.73744 6.17678 7.26256 6.17678 6.96967 6.46967C6.67678 6.76256 6.67678 7.23744 6.96967 7.53033L8.03033 6.46967ZM8.03033 17.5303L13.0303 12.5303L11.9697 11.4697L6.96967 16.4697L8.03033 17.5303ZM13.0303 12.5303L18.0303 7.53033L16.9697 6.46967L11.9697 11.4697L13.0303 12.5303ZM11.9697 12.5303L16.9697 17.5303L18.0303 16.4697L13.0303 11.4697L11.9697 12.5303ZM13.0303 11.4697L8.03033 6.46967L6.96967 7.53033L11.9697 12.5303L13.0303 11.4697Z" fill="#656565"></path></svg>
                </div>
            </div>

        <?php

        } else if ($contactAddedUserId == $selfId) { 

            $sqlUsers = "SELECT first_name, last_name, profile_img_path FROM users WHERE id = $contactAddedByUserId";

            $resultUsers = $conn->query($sqlUsers);

            // On récupere le nom et prénom de l'user selon l'id du contact dans la notif
            if ($resultUsers->num_rows > 0) { 
                $rowUsers = $resultUsers->fetch_assoc();

                $userProfileImg = $rowUsers["profile_img_path"];
                $userFirstName = $rowUsers["first_name"];
                $userLastName = $rowUsers["last_name"];

            }; ?>

            <div class="contact">
                <span class="hide contact-id"><?php echo $contactId ?></span>
                <div class="contact-infos">
                    <img class="contact-img" src="../<?php echo $userProfileImg ?>" alt="">
                    <h4 id="contactName"><?php echo $userFirstName . ' ' . $userLastName ?></h4>
                </div>
                <div class="contact-actions">
                    <svg class="contacts<?php echo $contactId; ?>" id="startContactDiscussion" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 3.33782C15.5291 2.48697 13.8214 2 12 2C6.47715 2 2 6.47715 2 12C2 13.5997 2.37562 15.1116 3.04346 16.4525C3.22094 16.8088 3.28001 17.2161 3.17712 17.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L6.39939 20.8229C6.78393 20.72 7.19121 20.7791 7.54753 20.9565C8.88837 21.6244 10.4003 22 12 22C17.5228 22 22 17.5228 22 12C22 10.1786 21.513 8.47087 20.6622 7" stroke="#656565" stroke-width="1.5" stroke-linecap="round"></path> <path d="M8 12H8.009M11.991 12H12M15.991 12H16" stroke="#656565" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>

                    <svg class="" id="deleteContact" viewBox="0 2 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.96967 16.4697C6.67678 16.7626 6.67678 17.2374 6.96967 17.5303C7.26256 17.8232 7.73744 17.8232 8.03033 17.5303L6.96967 16.4697ZM13.0303 12.5303C13.3232 12.2374 13.3232 11.7626 13.0303 11.4697C12.7374 11.1768 12.2626 11.1768 11.9697 11.4697L13.0303 12.5303ZM11.9697 11.4697C11.6768 11.7626 11.6768 12.2374 11.9697 12.5303C12.2626 12.8232 12.7374 12.8232 13.0303 12.5303L11.9697 11.4697ZM18.0303 7.53033C18.3232 7.23744 18.3232 6.76256 18.0303 6.46967C17.7374 6.17678 17.2626 6.17678 16.9697 6.46967L18.0303 7.53033ZM13.0303 11.4697C12.7374 11.1768 12.2626 11.1768 11.9697 11.4697C11.6768 11.7626 11.6768 12.2374 11.9697 12.5303L13.0303 11.4697ZM16.9697 17.5303C17.2626 17.8232 17.7374 17.8232 18.0303 17.5303C18.3232 17.2374 18.3232 16.7626 18.0303 16.4697L16.9697 17.5303ZM11.9697 12.5303C12.2626 12.8232 12.7374 12.8232 13.0303 12.5303C13.3232 12.2374 13.3232 11.7626 13.0303 11.4697L11.9697 12.5303ZM8.03033 6.46967C7.73744 6.17678 7.26256 6.17678 6.96967 6.46967C6.67678 6.76256 6.67678 7.23744 6.96967 7.53033L8.03033 6.46967ZM8.03033 17.5303L13.0303 12.5303L11.9697 11.4697L6.96967 16.4697L8.03033 17.5303ZM13.0303 12.5303L18.0303 7.53033L16.9697 6.46967L11.9697 11.4697L13.0303 12.5303ZM11.9697 12.5303L16.9697 17.5303L18.0303 16.4697L13.0303 11.4697L11.9697 12.5303ZM13.0303 11.4697L8.03033 6.46967L6.96967 7.53033L11.9697 12.5303L13.0303 11.4697Z" fill="#656565"></path></svg>
                </div>
            </div>

            <?php
        }
    };
} else {
    echo "Vous n'avez pas encore de contacts";
};

    $conn->close();



?>