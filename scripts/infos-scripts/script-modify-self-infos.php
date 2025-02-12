<?php
if (isset($_POST["submit_modify_self_infos"])) {
    session_set_cookie_params(3600);
    session_start();

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $firstName = htmlspecialchars($_POST["selfFirstname"], ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars($_POST["selfLastname"], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST["selfPhone"], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST["selfEmail"], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST["selfPassword"], ENT_QUOTES, 'UTF-8');
    $confirmPassword = htmlspecialchars($_POST["selfConfirmPassword"], ENT_QUOTES, 'UTF-8');
    $previousProfileImg = "../../" . $_SESSION["user_profile_img_path"];
    $id = $_SESSION['user_id'];

    if (strlen($password) >= 8 && preg_match("/[0-9]/", $password) && preg_match("/[!@#$%^&*]/", $password)) { 

        if ($password == $confirmPassword) {

            $validPassword = $password;
            $hash_password = password_hash($validPassword, PASSWORD_DEFAULT);

            // Vérifie si un fichier a été téléchargé
            if(!empty($_FILES["selfImage"]["name"])) {

                // Modifie le nom du fichier afin d'y ajouter l'id
                $originalFileName = $_FILES["selfImage"]["name"];
                $fileInfo = pathinfo($originalFileName);
                $newFileName = $fileInfo['filename'] . '-' . $id . '.' . $fileInfo['extension'];

                $uploadDirectory = "../../assets/images/users-profile-imgs/";
                $targetFile = $uploadDirectory . $newFileName;
                
                // Vérifie si le fichier est une image valide
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedExtensions = array("jpg", "jpeg", "png", "gif");
                
                if (in_array($imageFileType, $allowedExtensions)) {
                    if (move_uploaded_file($_FILES["selfImage"]["tmp_name"], $targetFile)) {

                        // Compresse l'image
                        $maxWidth = 500; // Largeur maximale de l'image
                        $maxHeight = 700; // Hauteur maximale de l'image

                        list($width, $height) = getimagesize($targetFile);
                        $aspectRatio = $width / $height;

                        if ($width > $height) {
                            $newWidth = $maxWidth;
                            $newHeight = $maxWidth / $aspectRatio;
                        } else {
                            $newHeight = $maxHeight;
                            $newWidth = $maxHeight * $aspectRatio;
                        }

                        $image = imagecreatefromstring(file_get_contents($targetFile));
                        $newImage = imagecreatetruecolor($newWidth, $newHeight);
                        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                        // Enregistrez l'image compressée
                        imagejpeg($newImage, $targetFile, 80); // 80 est la qualité de compression

                        // Libére la mémoire
                        imagedestroy($image);
                        imagedestroy($newImage);

                        // Mise à jour du chemin dans la base de données
                        $profileImagePath = "assets/images/users-profile-imgs/" . $newFileName;
                        
                        // Si l'image précédente n'est pas l'image par défault, on supprime
                        if ($previousProfileImg !== "../../assets/images/default-profile-img/user-default.png") {
                            unlink($previousProfileImg);
                        }

                        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, phone = ?, profile_img_path = ? WHERE id = ?";
                        
                        $stmt = $conn->prepare($sql);
                        
                        if ($stmt === false) {
                            die("Erreur de préparation de la requête : " . $conn->error);
                        }
                        
                        $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $hash_password, $phone, $profileImagePath, $id);

                        $_SESSION['user_firstName'] = $firstName;
                        $_SESSION['user_lastName'] = $lastName;
                        $_SESSION['user_phone'] = $phone;
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_password'] = $hash_password;
                        $_SESSION['user_profile_img_path'] = $profileImagePath;

                        if ($stmt->execute()) {
                            $conn->close();
                            header("Location: ../../pages/self-profile.php");
                            exit();
                        } else {
                            $_SESSION["error"] = "<p class='error'>Changement non effectués !</p>";
                            $stmt->close();
                            $conn->close();
                            header("Location: ../../pages/self-profile.php");
                            exit();
                        }
                    } else {
                        // Erreur de téléchargement de l'image
                        $_SESSION["error"] = "<p class='error'>Erreur lors du téléchargement de l'image.</p>";
                        $conn->close();
                        header("Location: ../../pages/self-profile.php");
                        exit();
                    }
                } else {
                    // Format d'image non pris en charge
                    $_SESSION["error"] = "<p class='error'>Le format de l'image n'est pas pris en charge. Utilisez JPG, JPEG, PNG ou GIF.</p>";
                    $conn->close();
                    header("Location: ../../pages/self-profile.php");
                    exit();
                }
            } else {
                // Aucune image n'a été téléchargée, utilise l'ancien chemin d'image
                $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, phone = ? WHERE id = ?";
                
                $stmt = $conn->prepare($sql);
                
                if ($stmt === false) {
                    die("Erreur de préparation de la requête : " . $conn->error);
                }
                
                $stmt->bind_param("sssssi", $firstName, $lastName, $email, $hash_password, $phone, $id);
            }

            $_SESSION['user_firstName'] = $firstName;
            $_SESSION['user_lastName'] = $lastName;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_password'] = $hash_password;

            if ($stmt->execute()) {
                $conn->close();
                header("Location: ../../pages/self-profile.php");
                exit();
            } else {
                $_SESSION["error"] = "<p class='error'>Changement non effectués !</p>";
                $stmt->close();
                $conn->close();
                header("Location: ../../pages/self-profile.php");
                exit();
            }
        } else {
            $_SESSION["error"] = "<p class='error'>Les mots de passe ne correspondent pas.</p>";
            $conn->close();
            header("Location: ../../pages/self-profile.php");
            exit();
        }
    } else {
        $_SESSION["error"] = "<p class='error'>Le format du mot de passe n'est pas correct.</p>";
        $conn->close();
        header("Location: ../../pages/self-profile.php");
        exit();
    }
} else {
    $_SESSION["error"] = "<p class='error'>Format incorrect.</p>";
    $conn->close();
    header("Location: ../../pages/self-profile.php");
    exit();
}
?>
