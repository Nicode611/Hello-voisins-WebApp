<?php
    session_start();

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $selfLatitude = $_SESSION["user_latitude"];
    $selfLongitude = $_SESSION["user_longitude"];

    // Cherche les messages dans un rayon de 500m autour de moi et les sort du 20ieme dernier au dernier
    $query = "SELECT * FROM (
        SELECT * FROM global_chat_messages 
        WHERE ST_Distance_Sphere(POINT($selfLongitude, $selfLatitude), POINT(message_longitude, message_latitude)) <= 500
        ORDER BY id DESC
        LIMIT 20
    ) AS subquery
    ORDER BY id ASC";




    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $senderId = $row["sender_id"];
            $message = $row["message"];
            $messageLatitude = $row["message_latitude"];
            $messageLongitude = $row["message_longitude"];
            $date = $row["date"];
            $hour = $row["hour"];

            $query2 = "SELECT first_name, profile_img_path FROM users WHERE id = $senderId";
            $result2 = $conn->query($query2);

            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {

                    $senderFirstName = $row2["first_name"];
                    $senderProfileImgPath = $row2["profile_img_path"];
                }
            }

            $messageData = array(
                'sender_id' => $senderId,
                'message' => $message,
                'sender_first_name' => $senderFirstName,
                'sender_profile_img_path' => $senderProfileImgPath,
                'message_latitude' => $messageLatitude,
                'message_longitude' => $messageLongitude,
                'date' => $date,
                'hour' => $hour
            );
        
            $messages[] = $messageData;
        
        };
        echo json_encode($messages);
    } else {
        $noMessages = "Pas de Messages";
        echo json_encode($noMessages);
    }
?>