let modify = document.querySelector(".modify");
let form = document.querySelector('.self-infos');
let passwordsSections = document.querySelector('.self-passwords');
let userInfos = document.querySelectorAll('.user-infos');
let itemsToShow = form.querySelectorAll('input');

modify.addEventListener('click', function() {
    itemsToShow.forEach(function(input) {
        input.classList.remove('hide');
    });
    userInfos.forEach(function (info) { 
        info.classList.add('hide');
    });
    passwordsSections.classList.remove('hide');
    modify.classList.add('hide');
});




