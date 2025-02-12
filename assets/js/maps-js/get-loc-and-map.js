selfImgElement = document.querySelector(".user-img-container");
mapLoadingIcon = document.querySelector(".loading-map");
selfImgPath = selfImgElement.src; 

function initMap() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
        
            // Récupère la localisation
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            mapLoadingIcon.remove();
            createMap(latitude, longitude);

    }, function(error) {
            var map = document.getElementById('map');
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    // L'utilisateur a refusé la demande de géolocalisation
                    map.innerText = 'L\'utilisateur a refusé la géolocalisation.';
                    console.log("L'utilisateur a refusé la géolocalisation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    // La position n'a pas pu être déterminée
                    map.innerText = 'La position n\'a pas pu être déterminée.';
                    console.log("La position n'a pas pu être déterminée.");
                    break;
                case error.TIMEOUT:
                    // La demande de géolocalisation a expiré
                    map.innerText = 'La demande de géolocalisation a expiré.';
                    console.log("La demande de géolocalisation a expiré.");
                    break;
                case error.UNKNOWN_ERROR:
                    // Une erreur inconnue s'est produite
                    map.innerText = 'Une erreur inconnue s\'est produite.';
                    console.log("Une erreur inconnue s'est produite.");
                    break;
            }
        });
    }

    function createMap(latitude, longitude) {
        // Vérifiez si les valeurs de latitude et longitude sont définies et valides
            // Les valeurs sont définies et valides, vous pouvez envoyer la requête AJAX
                
            $.ajax({
                type: "POST",
                url: "../scripts/infos-scripts/send-loc.php",
                data: { 
                    latitude: latitude,
                    longitude: longitude
                },
                success: function(response) {
                }
            });

            // Génère la carte
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: parseFloat(latitude), 
                    lng: parseFloat(longitude) 
                },
                zoom: 15
            });
            
            // Crée une icône personnalisée
            
            var myCustomIcon = {
                url: selfImgPath,
                scaledSize: new google.maps.Size(40, 40),
            };
            
            // Position de l'utilisateur principal
            var marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(latitude), 
                    lng: parseFloat(longitude) 
                },
                map: map,
                title: 'Votre position',
                icon: myCustomIcon
            });

            // Parcours le tableau récupéré dans la BDD
            for (var i = 0; i < userData.length; i++) {
                var user = userData[i];
                var userLatitude = parseFloat(user.latitude);
                var userLongitude = parseFloat(user.longitude);
                var firstName = user.first_name;
                var lastName = user.last_name;
                var profileImg = user.profile_img_path;
            
                var otherCustomIcons = {
                    url: '../' + profileImg,
                    scaledSize: new google.maps.Size(40, 40)
                };

                // Position des autres utilisateurs
                var marker = new google.maps.Marker({
                    position: {
                        lat: userLatitude,
                        lng: userLongitude
                    },
                    map: map,
                    title: firstName + ' ' + lastName,
                    icon: otherCustomIcons
                });
            }
        }
    }
