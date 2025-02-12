    // Ouverture / Fermeture de l'onglet notifications
    const notifBtn = document.querySelector('.notifications-icon');
    const notifBtnMobile = document.querySelector('.notifications-icon-mobile');
    const notifContainer = document.querySelector('.notifications-container');
    const overlay = document.querySelector('.overlay');
    
    notifBtn.addEventListener('click', function() {
        notifBtn.classList.toggle('open');
        notifContainer.classList.toggle('open');
        overlay.classList.toggle('active');
    });
    
    notifBtnMobile.addEventListener('click', function() {
        notifBtn.classList.toggle('open');
        notifContainer.classList.toggle('open');
        overlay.classList.toggle('active');
    });
    
    overlay.addEventListener('click', function() {
        notifBtn.classList.remove('open');
        notifContainer.classList.remove('open');
        overlay.classList.remove('active');
    });
    

    function chooseNotifs() {

        // Actions sur les boutons
        const validBtns = document.querySelectorAll('.valid-notification-icon');
        const deleteBtns = document.querySelectorAll('.cross-notification-icon');
        
        deleteBtns.forEach(deleteBtn => {
            deleteBtn.addEventListener('click', function(event) {
                var clickedBtn = event.target;
                var notifContainer = clickedBtn.closest(".notification-container");
                var notifContactId = notifContainer.querySelector('.notif-contact-id').textContent;
                var confirmMessage = document.createElement("p");
                confirmMessage.textContent = "Refusé";
                const choice = 'refuses';
        
                $.ajax({
                    type: 'POST',
                    url: '../scripts/notifs-scripts/script-choice-notifs.php',
                    data: {
                        choice_notifs: choice,
                        contactId: notifContactId
                    },
                    success: function(responseData) {
                        // Contient les données JSON retournées par le script PHP
                        responseData = JSON.parse(responseData);
        
                        if (responseData == 'deleted') {
                            notifContainer.replaceWith(confirmMessage);
        
                            var delai = 3000;
        
                            setTimeout(function() {
                                confirmMessage.remove();
                            }, delai);
                        }
                    }
                });
            });
        });
        
        validBtns.forEach(ValidBtn => {
            ValidBtn.addEventListener('click', function(event) {
                console.log('click');
                var clickedBtn = event.target;
                var notifContainer = clickedBtn.closest(".notification-container");
                var notifContactId = notifContainer.querySelector('.notif-contact-id').textContent;
                var confirmMessage = document.createElement("p");
                confirmMessage.textContent = "Contact ajouté";
                const choice = 'accept';
        
                $.ajax({
                    type: 'POST',
                    url: '../scripts/notifs-scripts/script-choice-notifs.php',
                    data: {
                        choice_notifs: choice,
                        contactId: notifContactId
                    },
                    success: function(responseData) {
                        // Contient les données JSON retournées par le script PHP
                        responseData = JSON.parse(responseData);
        
                        if (responseData == 'accepted') {
                            notifContainer.replaceWith(confirmMessage);
        
                            var delai = 3000;
        
                            setTimeout(function() {
                                confirmMessage.remove();
                            }, delai);
                        }
                    }
                });
            });
        });
    }
    