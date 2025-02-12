function groupOptions() {

    const plusBtns = document.querySelectorAll('.plus');
    const groupsOverlay = document.querySelector('.overlay');

    plusBtns.forEach(plusBtn => {
        plusBtn.addEventListener('click', function() {
            let plusContainer = document.querySelector(".popup-group-options");
            plusContainer.classList.toggle('open');
            groupsOverlay.classList.toggle('active');

            groupsOverlay.addEventListener('click', function() {
                plusContainer.classList.remove('open');
                groupsOverlay.classList.remove('active');
            });
        });
    });

    const membersBtns = document.querySelectorAll('.number-container');

    membersBtns.forEach(membersBtn => {
        membersBtn.addEventListener('click', function() {
            let membersContainer = membersBtn.querySelector(".popup-group-members");
            membersContainer.classList.toggle('open');
            groupsOverlay.classList.toggle('active');

            groupsOverlay.addEventListener('click', function() {
                membersContainer.classList.remove('open');
                groupsOverlay.classList.remove('active');
            });
        });
    });

    const modifGroupOptions = document.querySelectorAll('.modif-group-options-container');

    modifGroupOptions.forEach(modifGroupOption => {
        modifGroupOption.addEventListener('click', function() {
            var modifGroupName = modifGroupOption.querySelector('.modif-group-name');
            modifGroupName.remove();
            
            var modifGroupOptionsInputContainer = document.querySelector('.modif-group-options-input-container');
            modifGroupOptionsInputContainer.classList.remove('hide');

            var modifGroupOptionsInput = document.createElement('input');
            modifGroupOptionsInput.type = 'text';
            modifGroupOptionsInput.classList.add('modif-group-name-text');

            var modifGroupOptionsBtn = document.createElement('input');
            modifGroupOptionsBtn.type = 'submit';
            modifGroupOptionsBtn.classList.add('modif-group-name-submit');
            modifGroupOptionsBtn.value = 'Changer';

            modifGroupOption.style.pointerEvents = 'none';
            modifGroupOptionsInput.style.pointerEvents = 'auto';
            modifGroupOptionsBtn.style.pointerEvents = 'auto';

            modifGroupOptionsInputContainer.appendChild(modifGroupOptionsInput);
            modifGroupOptionsInputContainer.appendChild(modifGroupOptionsBtn);
            modifGroupOption.appendChild(modifGroupOptionsInputContainer);.0

            modifGroupOptionsInput.addEventListener('click', function(event) {
                event.stopPropagation();
            });
            modifGroupOptionsBtn.addEventListener('click', function(event) {
                event.stopPropagation();
            });
            
            // Désactivez les interactions de souris pour l'élément modifGroupOption (pour éviter les clics supplémentaires)
            modifGroupOption.style.pointerEvents = 'none';
            
        });
    });
}

