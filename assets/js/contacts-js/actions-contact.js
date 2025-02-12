const deleteContactBtns = document.querySelectorAll('#deleteContact');

deleteContactBtns.forEach(deleteContactBtn => {
    deleteContactBtn.addEventListener('click', function(event) { 

        if (window.confirm("Voulez-vous vraiment supprimer ce contact ?")) {

            var clickedBtn = event.target;
            var contactContainer = clickedBtn.closest(".contact");
            var contactIdElement = contactContainer.querySelector(".contact-id");
            var contactId = contactIdElement.textContent;


            // Envoie une requette au meme script que pour les notifs afin de delete le contact
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../scripts/contacts-scripts/script-delete-contact.php', true);

            // Envoie les données du btn au script PHP
            var actionData = new FormData();
            actionData.append('contact_id', contactId);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {

                    // Contient les données JSON retournées par le script PHP
                    const responseData = JSON.parse(xhr.responseText);

                    if (responseData == 'deleted') {
                        
                        contactContainer.remove();
                    }
                }
            }
            xhr.send(actionData);
        }
    });
});
