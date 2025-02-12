const allUsersContainer = document.querySelector('.all-users-container');
const popupUser = document.querySelector('.popup-user');
const messagesContainerPopup = document.querySelector('.messages-container');

// Au clic sur un user dans le chat
messagesContainerPopup.addEventListener('click', function(event) {
    if (event.target.classList.contains('other-users-img')) {
        const idPopup = event.target.nextElementSibling;

        // Selectionne l'ancienne popup et la supprime
        const popupUser = document.querySelector('.popup-user');
        popupUser.remove();
        
        // Envoie une requête AJAX pour obtenir les informations de l'utilisateur
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../scripts/infos-scripts/script-select-users-to-show.php?id=' + idPopup.textContent, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Contient les données JSON retournées par le script PHP
                const data = JSON.parse(xhr.responseText);

                // Genere la popup (placée ici pour gérer l'affichage du btn ou pas apres la recup de donnée dans la bdd)
                createPopupWindow();
                const popupUser = document.querySelector('.popup-user');
                const overlay2 = document.querySelector('.overlay');
                popupUser.classList.add('open');
                overlay2.classList.add('active');

                // Gestion du click sur l'overlay
                overlay2.addEventListener('click', function() {
                    popupUser.classList.remove('open');
                    overlay2.classList.remove('active');
                });
                
                const firstNameSpan = document.querySelector('.popup-first_name');
                const lastNameSpan = document.querySelector('.popup-last_name');
                const idSpan = document.querySelector('.popup-id');
                const containerDiv3 = document.querySelector('.popup-user-container3');
                const selfUserId = document.querySelector('.self-user-id');
                const userProfileImg = document.querySelector('.popup-user-img');

                // Remplace le texte dans les éléments span avec les données de l'utilisateur
                firstNameSpan.textContent = data.user_data.first_name;
                lastNameSpan.textContent = data.user_data.last_name;
                idSpan.textContent = data.user_data.id;
                userProfileImg.src = "../" + data.user_data.profileImgPath;


                // Afficher ou non le bouton de contact selon le statut du contact
                if (data.contact_statut == 'null' && selfUserId.textContent !== idSpan.textContent) {
                    const addContactIcon = document.createElement("img");
                    addContactIcon.classList.add('add-contact-btn');
                    addContactIcon.setAttribute("src", "../assets/images/add-user-icon.png");
                    addContactIcon.setAttribute("alt", "");

                    containerDiv3.appendChild(addContactIcon);

                } else if (data.contact_statut == 'waiting' && selfUserId.textContent !== idSpan.textContent) {
                    const waitingForValidation = document.createElement("p");
                    waitingForValidation.classList.add('waiting-for-validation');
                    waitingForValidation.textContent = 'Demande d\'ajout envoyée !';

                    containerDiv3.appendChild(waitingForValidation);

                } else if (data.contact_statut == 'added' && selfUserId.textContent !== idSpan.textContent) {
                    const alreadyAddText = document.createElement("p");
                    alreadyAddText.classList.add('already-add-text');
                    alreadyAddText.textContent = 'Cette personne est déja dans vos contacts';

                    containerDiv3.appendChild(alreadyAddText);
                }

                const addContactBtn = document.querySelector('.add-contact-btn');

                

                if (addContactBtn) {  
                    addContactBtn.addEventListener('click', function(event) {

                        // Requete pour ajouter un contact
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '../scripts/contacts-scripts/script-add-contact.php', true);

                        // Envoie des données du contact au script PHP
                        var contactData = new FormData();
                        contactData.append('idContact', idSpan.textContent);

                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var response = xhr.responseText;
                                var ok = JSON.parse(response);

                                // Si contact bien ajouté on enleve le btn et on met un texte de validation
                                if (ok == 'ok') {
                                    addContactBtn.remove();

                                    var validMessage = document.createElement('p');
                                    validMessage.innerText = 'Demande d\'ajout envoyée !'

                                    containerDiv3.appendChild(validMessage);
                                };
                            };
                        };

                        xhr.send(contactData);
                    });
                };

            };
        };
        xhr.send();
    }
});


allUsersContainer.addEventListener('click', function(event) {
    if (event.target.classList.contains('all-users-img')) {
        const idPopup = event.target.nextElementSibling;

        // Selectionne l'ancienne popup et la supprime
        const popupUser = document.querySelector('.popup-user');
        popupUser.remove();
        
        // Envoie une requête AJAX pour obtenir les informations de l'utilisateur
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../scripts/infos-scripts/script-select-users-to-show.php?id=' + idPopup.textContent, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Contient les données JSON retournées par le script PHP
                const data = JSON.parse(xhr.responseText);

                // Genere la popup (placée ici pour gérer l'affichage du btn ou pas apres la recup de donnée dans la bdd)
                createPopupWindow();
                const popupUser = document.querySelector('.popup-user');
                const overlay2 = document.querySelector('.overlay');
                popupUser.classList.add('open');
                overlay2.classList.add('active');

                // Gestion du click sur l'overlay
                overlay2.addEventListener('click', function() {
                    popupUser.classList.remove('open');
                    overlay2.classList.remove('active');
                });
                
                const firstNameSpan = document.querySelector('.popup-first_name');
                const lastNameSpan = document.querySelector('.popup-last_name');
                const idSpan = document.querySelector('.popup-id');
                const containerDiv3 = document.querySelector('.popup-user-container3');
                const selfUserId = document.querySelector('.self-user-id');
                const userProfileImg = document.querySelector('.popup-user-img');

                // Remplace le texte dans les éléments span avec les données de l'utilisateur
                firstNameSpan.textContent = data.user_data.first_name;
                lastNameSpan.textContent = data.user_data.last_name;
                idSpan.textContent = data.user_data.id;
                userProfileImg.src = "../" + data.user_data.profileImgPath;

                // Afficher ou non le bouton de contact selon le statut du contact
                if (data.contact_statut == 'null' && selfUserId.textContent !== idSpan.textContent) {
                    const addContactIcon = document.createElement("img");
                    addContactIcon.classList.add('add-contact-btn');
                    addContactIcon.setAttribute("src", "../assets/images/add-user-icon.png");
                    addContactIcon.setAttribute("alt", "");

                    containerDiv3.appendChild(addContactIcon);

                } else if (data.contact_statut == 'waiting' && selfUserId.textContent !== idSpan.textContent) {
                    const waitingForValidation = document.createElement("p");
                    waitingForValidation.classList.add('waiting-for-validation');
                    waitingForValidation.textContent = 'Demande d\'ajout envoyée !';

                    containerDiv3.appendChild(waitingForValidation);

                } else if (data.contact_statut == 'added' && selfUserId.textContent !== idSpan.textContent) {
                    const alreadyAddText = document.createElement("p");
                    alreadyAddText.classList.add('already-add-text');
                    alreadyAddText.textContent = 'Cette personne est déja dans vos contacts';

                    containerDiv3.appendChild(alreadyAddText);
                }

                const addContactBtn = document.querySelector('.add-contact-btn');

                

                if (addContactBtn) {  
                    addContactBtn.addEventListener('click', function(event) {

                        // Requete pour ajouter un contact
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '../scripts/contacts-scripts/script-add-contact.php', true);

                        // Envoie des données du contact au script PHP
                        var contactData = new FormData();
                        contactData.append('idContact', idSpan.textContent);

                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var response = xhr.responseText;
                                var ok = JSON.parse(response);

                                // Si contact bien ajouté on enleve le btn et on met un texte de validation
                                if (ok == 'ok') {
                                    addContactBtn.remove();

                                    var validMessage = document.createElement('p');
                                    validMessage.innerText = 'Demande d\'ajout envoyée !'

                                    containerDiv3.appendChild(validMessage);
                                };
                            };
                        };

                        xhr.send(contactData);
                    });
                };

            };
        };
        xhr.send();
    }
});



function createPopupWindow() {
    // Crée l'élément div avec la classe "popup-user"
    const popupUserDiv = document.createElement("div");
    popupUserDiv.classList.add("popup-user");

    // Crée le premier conteneur à l'intérieur de "popup-user"
    const container1Div = document.createElement("div");
    container1Div.classList.add("popup-user-container1");

    popupUserDiv.appendChild(container1Div);

    // Crée l'image à l'intérieur de container1Div
    const image = document.createElement("img");
    image.classList.add('popup-user-img')
    image.setAttribute("src", "../assets/images/user2.jpg");
    image.setAttribute("alt", "");

    // Crée le conteneur div pour le texte à l'intérieur de container1Div
    const textContainer = document.createElement("div");

    // Crée les éléments span pour le texte
    const firstNameSpan = document.createElement("span");
    firstNameSpan.classList.add("popup-first_name");
    const lastNameSpan = document.createElement("span");
    lastNameSpan.classList.add("popup-last_name");
    const idSpan = document.createElement("span");
    idSpan.classList.add("popup-id");
    idSpan.classList.add("hide");


    // Ajoute les éléments span au conteneur de texte
    textContainer.appendChild(firstNameSpan);
    textContainer.appendChild(lastNameSpan);
    textContainer.appendChild(idSpan);

    // Ajoute l'image et le texte au container1Div
    container1Div.appendChild(image);
    container1Div.appendChild(textContainer);

    // Crée le deuxième conteneur à l'intérieur de "popup-user"
    const container3Div = document.createElement("div");
    container3Div.classList.add("popup-user-container3");

    popupUserDiv.appendChild(container3Div);

    // Sélectionne le corps du document
    const body = document.body;

    // Ajoute les éléments créés au corps du document
    body.appendChild(popupUserDiv);
}