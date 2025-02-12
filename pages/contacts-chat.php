<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/proximity-chat.css">
    <link rel="stylesheet" href="../assets/css/private-chats.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Contact Chat</title>
</head>
<body>

    <?php
        $includeFile = "../includes/navigation.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    ?>

    <?php
        // Si on a le nom du channel ...
        $channelName = $_GET['channelName'] ?? null;
        $contactName = $_GET['contactName'] ?? null;
        $contactId = $_GET['contactId'] ?? null;

        if ($channelName) { 
    ?>
    
    <div class="chat-name-container-mobile">
                <span class="chat-name"><?php echo $contactName ?></span>
            </div>
            <div class="main-content">
                <div class="chat-name-container">
                    <span class="chat-name"><?php echo $contactName ?></span>
                </div>
                <div class="all-users-container">
                    <div class="loading">
                        <img class="loading-icon" src="../favicon.ico" alt="">
                        <span class="loading-message">Chargement</span>
                    </div>

                    <p id="user-count"></p>
                    <div class="all-users-list">
                    </div>
                </div>

                <div class="messages-container">

                </div>

                <div class="send-container">
                    <div class="send-message-container"><input type="text" name="sendMessage" id="sendMessage" placeholder="Ecrivez votre message ici"></div>
                    <button class="send-button">Envoyer</button>
                </div>

            </div>

            
            <script src="../assets/js/chats-js/chats-messages-handler.js"></script>
            <script src="../assets/js/infos-js/get-actual-date.js"></script>
            
            <script>

                // On défini toutes les variables utiles
                channelName = '<?php echo $channelName ?>';
                contactName = '<?php echo $contactName ?>';
                channelId = '<?php echo $contactId ?>';
                username = '<?php echo $_SESSION['user_firstName']; ?>';
                myId = '<?php echo $id = $_SESSION['user_id']; ?>';
                profileImgPath = '<?php echo $_SESSION['user_profile_img_path']; ?>';

                actualDateResult = getActualDate();
                messageDate = actualDateResult[0];
                messageHour = actualDateResult[1];


                // Connection online
                var conn = new WebSocket('wss://hello-voisins.com/websocket?username=' + username + '&id=' + myId + '&profileImgPath=' + profileImgPath + '&channelName=' + channelName + '&channelId=' + channelId + '&messageDate=' + messageDate + '&messageHour=' + messageHour);
                // Connection en local
                // var conn = new WebSocket('ws://localhost:8888?username=' + username + '&id=' + myId + '&profileImgPath=' + profileImgPath + '&channelName=' + channelName + '&channelId=' + channelId + '&messageDate=' + messageDate + '&messageHour=' + messageHour);

                // Action lors de l'envoi d'un message
                sendButton.addEventListener('click', function() {
                    var message = sendBar.value; // Obtiens la valeur du champ de texte
                    appendSentMessage(message, profileImgPath); // Ajoute le message localement
                    conn.send(message);
                    sendBar.value = '';
                });


                conn.onopen = function(e) {
                    console.log("Connection etablie!");
                };

                conn.onmessage = function(e) {
                    
                    var receivedMessage = e.data;

                    try {
                        var data = JSON.parse(receivedMessage);

                        // C'est un message de compteur d'utilisateurs
                        if (data.user_count !== undefined) {
                            updateUserCount(data.user_count);

                        // C'est un message contenantla liste des données des utilisateurs connectés
                        } else if (data.connected_users !== undefined) { 
                            data.connected_users.forEach(function(user) {
                                processConnectedUsersData(user.id, user.username, user.profileImgPath);
                            });
                            

                        } else if (data.username !== undefined && data.message !== undefined && data.profileImgPath !== undefined && data.id !== myId) {
                            
                            appendReceivedMessage(data.username, data.message, data.id, data.profileImgPath, "null", "null", "null", "null", data.messageDate, data.messageHour);
                                
                            // Si c'est message de déconnexion
                            if (data.message === "A quitté la conversation." || (data.id == myId)) {
                                removeUserFromList(data.id);
                            }

                            // Si c'est message de connexion
                            if (data.message === "A rejoint la conversation."|| (data.id == myId)) {
                                addUserToList(data.id, data.username, data.profileImgPath);
                            }
                        }
                    } catch (error) {
                        // Si une erreur se produit lors de l'analyse du JSON, cela signifie que c'est un message texte simple.
                        // Pour modifier le message de connexion au channel
                        // appendReceivedServerMessage(receivedMessage);
                    }
                };


                conn.onerror = function (event) {
                    console.error("WebSocket error: ", event);
                    console.log("Event type:", event.type);
                    console.log("Event message:", event.message);
                    console.log("Event target:", event.target);
                    // ... et ainsi de suite
                };


                conn.onclose = function(event) {
                    if (event.wasClean) {
                        console.log("WebSocket connection closed cleanly, code=" + event.code + ", reason=" + event.reason);
                    } else {
                        console.error("WebSocket connection abruptly closed");
                    }

                    const disconnectionData = {
                        username: username,
                        id: myId,
                        message: "A quitté la conversation."
                    };
                    
                    conn.send(JSON.stringify(disconnectionData));
                };
            
            // Faire défiler la liste des messages vers le bas
            var messagesContainerElement = document.querySelector(".messages-container");
            messagesContainerElement.scrollTop = messagesContainerElement.scrollHeight;

            </script>

            <!-- Fenetre modale users -->
            <div class="popup-user">
            </div>

            <script src="../assets/js/chats-js/chat-scroll-auto.js"></script>
            <script src="../assets/js/chats-js/show-user.js"></script>
            <script src="../assets/js/chats-js/get-olds-contacts-chat-messages.js"></script>
            
    <?php
        }
    ?>
</body>
</html>