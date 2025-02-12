<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/maps.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Carte</title>
</head>
<body>

    <?php
        $includeFile = "../includes/navigation.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    ?>

    <?php
        $includeFile = "../scripts/infos-scripts/script-get-users-locations.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    ?>

    <script type="text/javascript">
        // Transfert des données à JavaScript
        var userData = <?php echo $jsonUserData; ?>;
    </script>

    <div class="main-content">

        <div id="map">
            <div class="loading-map">
                <span>Chargement de la carte</span>
                <img class="map-loading-icon" src="../favicon.ico" alt="">
            </div>
        </div>

    </div>

    <script src="../assets/js/maps-js/get-loc-and-map.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGd9Tr4P6a71MMWhjcWjpApcEFhN7dURk&callback=initMap" async defer></script>
    
</body>
</html>