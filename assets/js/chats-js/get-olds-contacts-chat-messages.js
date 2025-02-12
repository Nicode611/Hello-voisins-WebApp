$.ajax({
    type: "GET",
    url: "../scripts/contacts-scripts/show-old-contacts-chat-messages.php",
    data: {
        contactId: channelId
    },
    
    success: function(response) {

        var messages = JSON.parse(response);

        if (messages === "Pas de Messages") {
            console.log('Pas de messages');
        } else {
            for (var i = 0; i < messages.length; i++) {
                var message = messages[i];

                if (myId == message.sender_id) {
                    ShowOldsSelfMessages(message.message, message.sender_profile_img_path);
                } else {
                    showOldsMessages(message.sender_first_name, message.message, message.sender_id, message.sender_profile_img_path, message.date, message.hour);
                }
            };
        }
        
    }
});

// Fonction pour afficher les anciens messages de la BDD à proximité
function showOldsMessages(username, message, id, profileImgPath, date, hour) {
    
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
    usernameText.textContent = username;

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
}

function ShowOldsSelfMessages(message, profileImgPath) {

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
}
