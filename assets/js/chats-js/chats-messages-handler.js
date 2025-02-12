        var sendButton = document.querySelector('.send-button');
        var sendBar = document.querySelector('#sendMessage');
        const messagesContainer = document.querySelector('.messages-container');

        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        window.addEventListener('load', scrollToBottom);

        // Simule un clic lorsque la touche "Entrée" est enfoncée.
        sendBar.addEventListener("keyup", function(event) {
            if (event.key === "Enter") {
            sendButton.click();
            }
        });


        // Fonction pour ajouter le message qu'on viens d'envoyer
        function appendSentMessage(message, profileImgPath) {
        
            var messageContainer = document.createElement('div');
            messageContainer.className = 'sent-message-container';

            var userImg = document.createElement('img');
            userImg.className = 'self-user-img';
            userImg.src = '../' + profileImgPath;
            userImg.alt = '';

            var messageText = document.createElement('p');
            messageText.className = 'sent-message';
            messageText.textContent = message;
            
            messagesContainer.appendChild(messageContainer);
            messageContainer.appendChild(userImg);
            messageContainer.appendChild(messageText);

            scrollToBottom();
        }

        


     // Fonction pour ajouter un message reçu au format souhaité
    function appendReceivedMessage(username, message, id, profileImgPath, userLatitude, userLongitude, messageLatitude, messageLongitude, date, hour) {
        var distance = null;
        
        if (messageLatitude !== "null") {
            distance = calculDistance(userLatitude, userLongitude, messageLatitude, messageLongitude);
        }

        var messageContainer = document.createElement('div');
        messageContainer.className = 'received-message-container';

        var userImg = document.createElement('img');
        userImg.className = 'other-users-img';
        userImg.src = '../' + profileImgPath;
        userImg.alt = '';

        var idText = document.createElement('p');
        idText.className = 'other-users-id hide';
        idText.textContent = id;

        var receivedMessage = document.createElement('div');
        receivedMessage.className = 'received-message';

        var usernameText = document.createElement('span');
        usernameText.className = 'received-message-username';

        if (messageLatitude !== "null") {
            usernameText.textContent = username + " " + distance.toFixed(0) + "m";
        } else {
            usernameText.textContent = username ;
        }
        
        var messageText = document.createElement('p');
        messageText.className = 'received-message-content';
        messageText.textContent = message;

        var receivedMessageFirstSection = document.createElement('div');
        receivedMessageFirstSection.className = 'received-message-first-section';

        var dateText = document.createElement('span');
        dateText.className = 'received-message-date';
        dateText.textContent = date;

        var hourText = document.createElement('span');
        hourText.className = 'received-message-hour';
        hourText.textContent = hour;

        messagesContainer.appendChild(messageContainer);
        messageContainer.appendChild(userImg);
        messageContainer.appendChild(idText);
        messageContainer.appendChild(receivedMessage);
        receivedMessage.appendChild(usernameText);
        receivedMessage.appendChild(messageText);
        receivedMessage.appendChild(receivedMessageFirstSection);
        receivedMessageFirstSection.appendChild(dateText);
        receivedMessageFirstSection.appendChild(hourText);

        scrollToBottom();
    }

    // Messages du serveur
    function appendReceivedServerMessage(message) {
        var messageContainer = document.createElement('div');
        messageContainer.className = 'received-message-container';

        var receivedMessage = document.createElement('div');
        receivedMessage.className = 'received-message';

        var messageText = document.createElement('span');
        messageText.className = 'received-message-username';
        messageText.textContent = message;

        messagesContainer.appendChild(messageContainer);
        messageContainer.appendChild(receivedMessage);
        receivedMessage.appendChild(messageText);

        scrollToBottom();
    }


    // Fonction pour mettre a jour le compteur d'utilisateurs
    function updateUserCount(count) {
        var userCountElement = document.querySelector('#user-count');
        userCountElement.textContent = count;
    }

    // Fonction pour afficher les utilisateurs connectés
    function processConnectedUsersData(userId, username, profileImgPath) {
        var allUsersList = document.querySelector('.all-users-list');

        var allUsers = document.createElement('div');
        allUsers.className = 'all-users';
        
        var userImg = document.createElement('img');
        userImg.className = 'all-users-img';
        userImg.src = '../' + profileImgPath;
        userImg.alt = '';

        var allUsersId = document.createElement('span')
        allUsersId.className = 'other-users-id hide';
        allUsersId.textContent = userId;

        var allUsersName = document.createElement('span')
        allUsersName.className = 'all-users-name';
        allUsersName.textContent = username;

        allUsers.appendChild(userImg);
        allUsers.appendChild(allUsersId);
        allUsers.appendChild(allUsersName);

        allUsersList.appendChild(allUsers);

        // Enlever l'icone de chargement
        var loadingMessages = document.querySelectorAll(".loading");
        loadingMessages.forEach(function(loadingMessage) {
            loadingMessage.remove();
        });
    }

    // fonction pour ajouter un utilisateur de la liste coté client 
    function addUserToList(userId, username, profileImgPath) {
        var allUsersList = document.querySelector('.all-users-list');
    
        // Vérifiez si un élément .all-users contient déjà un .other-users-id avec le même texte
        var userExists = [...allUsersList.querySelectorAll('.all-users')].some(function(user) {
            var idElement = user.querySelector('.other-users-id');
            return idElement && idElement.textContent === userId;
        });
    
        if (!userExists) {
            var allUsers = document.createElement('div');
            allUsers.className = 'all-users';
    
            var userImg = document.createElement('img');
            userImg.className = 'all-users-img';
            userImg.src = '../' + profileImgPath;
            userImg.alt = '';

            var allUsersId = document.createElement('span');
            allUsersId.className = 'other-users-id hide';
            allUsersId.textContent = userId;
    
            var allUsersName = document.createElement('span');
            allUsersName.className = 'all-users-name';
            allUsersName.textContent = username;
    
            allUsers.appendChild(userImg);
            allUsers.appendChild(allUsersId);
            allUsers.appendChild(allUsersName);
    
            allUsersList.appendChild(allUsers);
        }
    }
    

    // Fonction pour supprimer un utilisateur de la liste côté client
    function removeUserFromList(userId) {
        var userElements = document.querySelectorAll('.all-users');
        userElements.forEach(function(userElement) {
            var idSpan = userElement.querySelector('.other-users-id');
            if (idSpan && idSpan.textContent === userId) {
                userElement.remove(); // Supprime l'élément de la liste
            }
        });
    }

    // calcul de distance pour afficher la distance entre les utilisateurs
    function calculDistance(lat1, lon1, lat2, lon2) {
        const rayonTerre = 6371; // Rayon moyen de la Terre en kilomètres
    
        // Conversion des degrés en radians
        const radLat1 = (Math.PI * lat1) / 180;
        const radLon1 = (Math.PI * lon1) / 180;
        const radLat2 = (Math.PI * lat2) / 180;
        const radLon2 = (Math.PI * lon2) / 180;
    
        // Différence de coordonnées
        const deltaLat = radLat2 - radLat1;
        const deltaLon = radLon2 - radLon1;
    
        // Formule de la haversine
        const a =
            Math.sin(deltaLat / 2) ** 2 +
            Math.cos(radLat1) * Math.cos(radLat2) * Math.sin(deltaLon / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    
        // Distance en mètres
        const distance = rayonTerre * c * 1000;
    
        return distance;
    }

    function sayHello() {
        console.log('hello');
    }


