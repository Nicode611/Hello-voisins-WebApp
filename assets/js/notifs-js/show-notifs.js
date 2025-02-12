$(document).ready(function() {
    notifsContainer = document.querySelector('.notifications-container');
    // Code à exécuter lorsque la page est chargée
    $.ajax({
      url: '../scripts/notifs-scripts/show-notifs.php',
      type: 'GET',
      dataType: 'json',
      success: function(response) {

        if (response === "Pas de notifications") {
            
            var noNotifs = document.createElement('span');
            noNotifs.textContent = "Pas de notifications"
            notifsContainer.appendChild(noNotifs);

        } else {
            var notifs = response ;
            showPatch();

            for (var i = 0; i < notifs.length; i++) {
                var notif = notifs[i];

                createContactNotif(notif.contactId, notif.contactMessage, notif.userFirstName, notif.userLastName, notif.userProfileImgPath);
                chooseNotifs()
            }
        }   

      },
      error: function(error) {
        // Code à exécuter en cas d'échec de la requête
        console.log('Erreur de requête Ajax:', error);
      }
    });
  });



  function createContactNotif(contactId, contactMessage, userFirstName, userLastName, userProfileImgPath) {

    notifsContainer = document.querySelector('.notifications-container');

    // Créer les éléments HTML avec les valeurs en PHP
    var notificationContainer = document.createElement('div');
    notificationContainer.classList.add('notification-container');

    var notifLabel = document.createElement('label');
    notifLabel.classList.add('notif-label');
    notifLabel.setAttribute('for', 'notif');
    notifLabel.textContent = contactMessage;

    var notification = document.createElement('div');
    notification.classList.add('notification');
    notification.setAttribute('name', 'notif');

    var contactIdElement = document.createElement('span');
    contactIdElement.classList.add('hide');
    contactIdElement.classList.add('notif-contact-id');
    contactIdElement.textContent = contactId;

    var userDiv = document.createElement('div');
    var userImage = document.createElement('img');
    userImage.src = "../" + userProfileImgPath; // Remplacez par le chemin de l'image
    userImage.alt = userFirstName + ' ' + userLastName;
    var userName = document.createElement('span');
    userName.textContent = userFirstName + ' ' + userLastName;

    userDiv.appendChild(userImage);
    userDiv.appendChild(userName);

    var iconDiv = document.createElement('div');
    var validIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    validIcon.classList.add('valid-notification-icon');
    validIcon.setAttribute('fill', '#3dcc00');
    validIcon.setAttribute('version', '1.1');
    validIcon.setAttribute('id', 'Layer_1');
    validIcon.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    validIcon.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    validIcon.setAttribute('viewBox', '0 0 21 21');
    validIcon.setAttribute('xml:space', 'preserve');
    var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', 'M19.3,5.3L9,15.6l-4.3-4.3l-1.4,1.4l5,5L9,18.4l0.7-0.7l11-11L19.3,5.3z');
    validIcon.appendChild(path);


    var crossIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    crossIcon.classList.add('cross-notification-icon');
    crossIcon.setAttribute('fill', '#e00000');
    crossIcon.setAttribute('viewBox', '-5 -8 28 28');
    crossIcon.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    crossIcon.setAttribute('stroke', '#e00000');
    var crossPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    crossPath.setAttribute('d', 'M0 14.545L1.455 16 8 9.455 14.545 16 16 14.545 9.455 8 16 1.455 14.545 0 8 6.545 1.455 0 0 1.455 6.545 8z');
    crossPath.setAttribute('fill-rule', 'evenodd');

    crossIcon.appendChild(crossPath);

    iconDiv.appendChild(validIcon);
    iconDiv.appendChild(crossIcon);

    notification.appendChild(userDiv);
    notification.appendChild(iconDiv);

    notificationContainer.appendChild(contactIdElement);
    notificationContainer.appendChild(notifLabel);
    notificationContainer.appendChild(notification);

    // Ajouter le container généré au corps du document
    notifsContainer.appendChild(notificationContainer);
  }


function showPatch() {

    var patch = document.createElement('div');
    patch.classList.add('patch');

    var notifIcons = document.querySelectorAll('.notifications-icon, .notifications-icon-mobile');
    notifIcons.forEach(notifIcon => {
        notifIcon.insertAdjacentElement('afterend', patch.cloneNode(true));
    });

}
