<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/proximity-chat.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Chat de proximité</title>
</head>
<body>

    <?php
        $includeFile = "../includes/navigation.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    ?>

    <script src="../assets/js/chats-js/get-olds-global-chat-messages.js"></script>

    <div class="main-content">

        

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

    <script>

        // Calcul de la distance
        function distance(lat1, lon1, lat2, lon2) {
            // Rayon de la Terre en mètres
            var R = 6371000;
            var dLat = (lat2 - lat1) * Math.PI / 180;
            var dLon = (lon2 - lon1) * Math.PI / 180;
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var distance = R * c; // Distance en mètres
            return distance;
        }

        // Fonction pour récupérer l'heure
        function getActualDate() {

            var newDate = new Date();
            var day = newDate.getDate();
            var month = newDate.getMonth() + 1;
            var year = newDate.getFullYear();
            var actualDate = day + '/' + month + '/' + year;

            var hours = newDate.getHours();
            var minutes = newDate.getMinutes();
            var actualHour = hours + ':' + (minutes < 10 ? '0' : '') + minutes;

            return [actualDate, actualHour];
        }

        function validationToConnect(latitude, longitude) {

            // Connection websocket
            channelName = 'Global'
            username = '<?php echo $_SESSION['user_firstName']; ?>';
            myId = ' <?php echo $id = $_SESSION['user_id']; ?>';
            profileImgPath = '<?php echo $_SESSION['user_profile_img_path']; ?>';
            channelId = "";
            actualDateResult = getActualDate();
            messageDate = actualDateResult[0];
            messageHour = actualDateResult[1];
            
                // Connection online
                let conn = new WebSocket('wss://hello-voisins.com/websocket?username=' + username + '&id=' + myId + '&profileImgPath=' + profileImgPath + '&channelName=' + channelName + '&channelId=' + channelId + '&messageDate=' + messageDate + '&messageHour=' + messageHour);
                // Connection en local
                // let conn = new WebSocket('ws://localhost:8888?username=' + username + '&id=' + myId + '&profileImgPath=' + profileImgPath + '&channelName=' + channelName + '&channelId=' + channelId + '&messageDate=' + messageDate + '&messageHour=' + messageHour);

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
                                if (distance(latitude, longitude, user.userLatitude, user.userLongitude) <= 500) {
                                    processConnectedUsersData(user.id, user.username, user.profileImgPath);
                                }
                            });
                            
                            

                        } else if (data.username !== undefined && data.message !== undefined && data.profileImgPath !== undefined && data.id !== myId) {
                            
                            if (distance(latitude, longitude, data.messageLatitude, data.messageLongitude) <= 500 || distance(latitude, longitude, data.userLatitude, data.userLongitude) <= 500) {
                                // console.log("La position 2 est dans un rayon de 500 mètres de la position 1.");
                                if (data.message === "A quitté la conversation." || data.message === "A rejoint la conversation.") {
                                    
                                    appendReceivedMessage(data.username, data.message, data.id, data.profileImgPath, latitude, longitude, "null", data.messageLongitude, data.messageDate, data.messageHour);
                                } else {
                                    appendReceivedMessage(data.username, data.message, data.id, data.profileImgPath, latitude, longitude, data.messageLatitude, data.messageLongitude, data.messageDate, data.messageHour);
                                }
                                
                            } else {
                                // console.log("La position 2 n'est pas dans un rayon de 500 mètres de la position 1.");
                            }

                            // Si c'est message de déconnexion et qu'on est à bonne distance
                            if (data.message === "A quitté la conversation." && (distance(latitude, longitude, data.userLatitude, data.userLongitude) <= 500) || (data.id == myId)) {
                                removeUserFromList(data.id);
                            }

                            // Si c'est message de connexion et qu'on est à bonne distance
                            if (data.message === "A rejoint la conversation." && (distance(latitude, longitude, data.userLatitude, data.userLongitude) <= 500) || (data.id == myId)) {
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
        }
        
    </script>

    <!-- Fenetre modale users -->
    <div class="popup-user">
    </div>


    <script src="../assets/js/chats-js/chat-scroll-auto.js"></script>
    <script src="../assets/js/chats-js/show-user.js"></script>
    <script src="../assets/js/chats-js/chats-messages-handler.js"></script>
    
</body>
</html>