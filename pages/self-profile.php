<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/self-profile.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Chat de proximité</title>
</head>
<body>
    
    <?php
        $includeFile = "../includes/navigation.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    ?>

<div id="validation"><?php if (isset($_SESSION["success"])) { echo $_SESSION["success"]; unset($_SESSION["success"]); } ?></div>
<div id="error"><?php if (isset($_SESSION["error"])) { echo $_SESSION["error"]; unset($_SESSION["error"]); } ?></div>

    <div class="main-content">
        
        <form class="self-infos" action="../scripts/infos-scripts/script-modify-self-infos.php" method="POST" enctype="multipart/form-data">
            <div class="self-infos-section">
                <img class="self-img" src="../<?php echo $_SESSION["user_profile_img_path"] ?>" alt="">
                <input class="hide" type="file" name="selfImage" id="selfImage" accept="image/*">
            </div>
            <div class="name-section">
                <div class="self-infos-section">
                    <label for="selfFirstname">Prénom</label>
                    <span class="user-infos"><?php echo $_SESSION["user_firstName"]; ?></span>
                    <input class="hide" type="text" name="selfFirstname" id="selfFirstname" value="<?php echo $_SESSION['user_firstName']; ?>" required>
                </div>
                <div class="self-infos-section">
                    <label for="selfLastname">Nom</label>
                    <span class="user-infos"><?php echo $_SESSION["user_lastName"]; ?></span>
                    <input class="hide" type="text" name="selfLastname" id="selfLastname" value="<?php echo $_SESSION['user_lastName']; ?>" required>
                </div>
            </div>
            <div class="mail-tel-section">
                <div class="self-infos-section">
                    <label for="selfPhone">Téléphone</label>
                    <span class="user-infos"><?php echo $_SESSION["user_phone"]; ?></span>
                    <input class="hide" type="text" name="selfPhone" id="selfPhone" value="<?php echo $_SESSION['user_phone']; ?>" required>
                </div>
                <div class="self-infos-section">
                    <label for="selfEmail">Email</label>
                    <span class="user-infos"><?php echo $_SESSION["user_email"]; ?></span>
                    <input class="hide" type="text" name="selfEmail" id="selfEmail" value="<?php echo $_SESSION['user_email']; ?>" required>
                </div>
            </div>
            <div class="hide self-passwords">
                <div class="self-infos-section self-sections-passwords">
                    <label for="selfPassword">Mot de passe</label>
                    <input type="text" name="selfPassword" id="selfPassword" required>
                </div>
                <div class="self-infos-section self-sections-passwords">
                    <label for="selfConfirmPassword">Confirmer le mot de passe</label>
                    <input type="text" name="selfConfirmPassword" id="selfConfirmPassword" required>
                </div>
            </div>
            <input class="hide" type="submit" name="submit_modify_self_infos" id="submit_modify_self_infos" value="Valider les infos">
        </form>
        <div class="btns-container">
            <button class="modify">Modifier les infos</button>
            <form class="delete-account-form" action="../scripts/infos-scripts/script-delete-account.php" method="POST" enctype="multipart/form-data">
                <input class="delete-account-btn" type="submit" name="delete-account-btn" id="delete-account-btn" value="Supprimer le compte" onclick="confirm('Vous etes sur le point de supprimer votre compte.');">
            </form>
        </div>
    </div>
    
    <script src="../assets/js/infos-js/modify-infos.js"></script>
</body>
</html>