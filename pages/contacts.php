<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/contacts.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Contacts</title>
</head>
<body>

    <?php
        $includeFile = "../includes/navigation.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    ?>

    <div class="main-content">
        <h1>Vos contacts</h1>
        <input class="hide" type="search" name="findContacts" id="findContacts" placeholder="Rechercher">
        <?php
            $includeFile = "../scripts/contacts-scripts/show-my-contacts.php";
            if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
        ?>
    </div>

    <script>
        // Script pour lancer le chat (récupération du nom, envoie a la page groups-chat.php)
        const startContactChatBtns = document.querySelectorAll('#startContactDiscussion');

        startContactChatBtns.forEach(function(startContactChatBtn) {
            startContactChatBtn.addEventListener('click', function() {

                var contactContainer = startContactChatBtn.closest(".contact");
                var contactName = contactContainer.querySelector("#contactName").textContent;
                var contactId = contactContainer.querySelector(".contact-id").textContent;
                var channelName = "contact" + contactId;
                if (channelName, contactName) {
                    // Redirige l'utilisateur vers la page Contacts-chat.php avec le nom du canal en tant que paramètre d'URL
                    window.location.href = "contacts-chat.php?channelName=" + channelName + "&contactName=" + contactName + "&contactId=" + contactId;
                }
            });
        });
    </script>

    <script src="../assets/js/contacts-js/actions-contact.js"></script>
    
</body>
</html>