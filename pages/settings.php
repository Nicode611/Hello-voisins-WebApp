<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/settings.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Parametres</title>
</head>
<body>

    <?php
        $includeFile = "../includes/navigation.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    ?>

    <div class="main-content">
        <div class="settings">
            
            <div class="setting">
                <p>Apparaitre sur la carte</p>
                <label class="switch">
                    
                    
                    <?php
                    $includeFile = "../config/db/db.php";
                    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
                    $connexion = new mysqli($db_host, $db_user, $db_pass, $db_name);
                
                    if ($connexion->connect_error) {
                        die("La connexion à la base de données a échoué : " . $connexion->connect_error);
                    }

                    $userId = $_SESSION["user_id"];

                    $sql = "SELECT adress FROM users WHERE id = $userId";

                    $result = $connexion->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if ($row["adress"] == "yes") {
                                ?> <input class="hide-on-map" type="checkbox" checked > <?php
                            } else {
                                ?> <input class="hide-on-map" type="checkbox" > <?php
                            }
                        }
                    }
                    ?>
                    <span></span> 
                </label>
            </div>
            
            <div class="setting">
                <p>Distance de detection</p>
                <select name="chooseDistance" id="chooseDistance">
                    <option value="50m">50m</option>
                    <option value="100m">100m</option>
                    <option value="200m">200m</option>
                    <option value="400m">400m</option>
                    <option value="600m">600m</option>
                </select>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/settings-js/hide-on-map.js"></script>
    <script src="../assets/js/infos-js/get-loc.js"></script>
</body>
</html>