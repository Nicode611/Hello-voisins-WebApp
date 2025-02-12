<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use mysqli;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $usernames;
    protected $channels;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->usernames = [];
        $this->channels = [];
    }

    // Étape 1 : Lorsqu'une nouvelle connexion WebSocket est établie
    public function onOpen(ConnectionInterface $conn) {
        parse_str($conn->httpRequest->getUri()->getQuery(), $queryParameters);

        $username = $queryParameters['username'] ?? null;
        $id = $queryParameters['id'] ?? null;
        $channelName = $queryParameters['channelName'] ?? null;
        $profileImgPath = $queryParameters['profileImgPath'] ?? null;
        $messageDate = $queryParameters['messageDate'] ?? null;
        $messageHour = $queryParameters['messageHour'] ?? null;
        $channelId = $queryParameters['channelId'] ?? null;

        if ($username && $id) {
            $db_host = "mysql-garage-v-parrot.alwaysdata.net";
            $db_user = "331032";
            $db_pass = "Beta2k15";
            $db_name = "hello-voisins_2023";
            $connexion = new mysqli($db_host, $db_user, $db_pass, $db_name);

            if ($connexion->connect_error) {
                die("La connexion à la base de données a échoué : " . $connexion->connect_error);
            }

            $query = "SELECT latitude, longitude FROM users WHERE id = $id";
            $result = $connexion->query($query);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $userLatitude = $row["latitude"];
                $userLongitude = $row["longitude"];
            }

            $userData = [
                "username" => $username,
                "id" => $id,
                "channel" => $channelName,
                "profileImgPath" => $profileImgPath,
                "channelId" => $channelId,
                "userLatitude" => $userLatitude,
                "userLongitude" => $userLongitude,
                "messageDate"=> $messageDate,
                "messageHour"=> $messageHour,
            ];

            $this->usernames[$conn->resourceId] = $userData;

            if (isset($channelName)) {
                if (!isset($this->channels[$channelName])) {
                    $this->channels[$channelName] = new \SplObjectStorage();
                }
                $this->channels[$channelName]->attach($conn);

                // Envoyer la liste des utilisateurs connectés dans le canal au nouvel utilisateur
                $this->sendConnectedUsersDataToUserInChannel($conn, $channelName);
            }

            // Envoyer un message de connexion au canal
            $this->sendConnectionMessageToChannel($username, $id, $channelName, $profileImgPath, $userLatitude, $userLongitude, $messageDate, $messageHour);

            // Compter tous les utilisateurs connectés
            $countAllUsers = count($this->channels[$channelName]);

            // On envoie le compteur au channel grace a la fonction
            $this->sendUserCountToChannel($channelName, $countAllUsers);

            echo "Le :$messageDate, A :$messageHour, Nouvelle connexion ! ({$conn->resourceId}) - Username: $username, ID: $id, Channel: $channelName\n";
        }
    }

    // Étape 2 : Lorsqu'un message est reçu d'un client
    public function onMessage(ConnectionInterface $from, $msg) {
        $fromUserData = $this->usernames[$from->resourceId] ?? null;

        if ($fromUserData) {
            $fromUsername = $fromUserData['username'];
            $fromId = $fromUserData['id'];
            $fromProfileImgPath = $fromUserData['profileImgPath'];
            $channelId = $fromUserData['channelId'];
            $fromUserLatitude = $fromUserData['userLatitude'];
            $fromUserLongitude = $fromUserData['userLongitude'];
            $fromMessageDate = $fromUserData['messageDate'];
            $fromMessageHour = $fromUserData['messageHour'];

            $messageData = [
                "username" => $fromUsername,
                "id" => $fromId,
                "profileImgPath" => $fromProfileImgPath,
                "messageLatitude" => $fromUserLatitude,
                "messageLongitude" => $fromUserLongitude,
                "messageDate"=> $fromMessageDate,
                "messageHour"=> $fromMessageHour,
                "message" => $msg
            ];

            if (isset($fromUserData['channel'])) {
                $channelName = $fromUserData['channel'];

                $db_host = "mysql-garage-v-parrot.alwaysdata.net";
                $db_user = "331032";
                $db_pass = "Beta2k15";
                $db_name = "hello-voisins_2023";
                $connexion = new mysqli($db_host, $db_user, $db_pass, $db_name);

                if ($connexion->connect_error) {
                    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
                }

                $chatMessage = $messageData["message"];
                $chatSenderId = $messageData["id"];


                // Si c'est le chat global
                if ($channelName == "Global") { 

                    $query = "INSERT INTO global_chat_messages (message, sender_id, message_latitude, message_longitude, date, hour) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $connexion->prepare($query);
                    if ($stmt === false) {
                        echo ("Erreur de préparation de la requête : " . $connexion->error);
                    }
                    $stmt->bind_param('ssssss', $chatMessage, $chatSenderId, $fromUserLatitude, $fromUserLongitude, $fromMessageDate, $fromMessageHour);

                    // On execute la requette
                    $stmt->execute();
                    $connexion->close();


                    // Si c'est un channel de groupe
                } else if (strpos($channelName, "group") !== false) {

                    $query = "INSERT INTO groups_chat_messages (group_id, message, sender_id, date, hour) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $connexion->prepare($query);
                    if ($stmt === false) {
                        echo ("Erreur de préparation de la requête : " . $connexion->error);
                    }
                    $stmt->bind_param('sssss', $channelId, $chatMessage, $chatSenderId, $fromMessageDate, $fromMessageHour);

                    // On execute la requette
                    $stmt->execute();
                    $connexion->close();


                    // Si c'est un channel de contact
                } else if (strpos($channelName, "contact") !== false) {

                    $query = "INSERT INTO contacts_chat_messages (contact_id, message, sender_id, date, hour) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $connexion->prepare($query);
                    if ($stmt === false) {
                        echo ("Erreur de préparation de la requête : " . $connexion->error);
                    }
                    $stmt->bind_param('sssss', $channelId, $chatMessage, $chatSenderId, $fromMessageDate, $fromMessageHour);

                    // On execute la requette
                    $stmt->execute();
                    $connexion->close();
                }

                // Envoie le message au channel
                $this->sendToChannel($channelName, $messageData);
            }
        }
    }

    // Étape 3 : Lorsqu'une connexion se ferme
    public function onClose(ConnectionInterface $conn) {
        $userData = $this->usernames[$conn->resourceId] ?? null;

        if ($userData) {
            $username = $userData['username'];
            $id = $userData['id'];
            $channelName = $userData['channel'];
            $profileImgPath = $userData['profileImgPath'];
            $userLatitude = $userData['userLatitude'];
            $userLongitude = $userData['userLongitude'];
            $messageDate = $userData['messageDate'];
            $messageHour = $userData['messageHour'];


            // Envoyer un message de déconnexion au canal
            $this->sendDisconnectionMessageToChannel($username, $id, $channelName, $profileImgPath, $userLatitude, $userLongitude, $messageDate, $messageHour);


            // Envoyer la liste des utilisateurs connectés dans le canal au nouvel utilisateur
            $this->sendConnectedUsersDataToUserInChannel($conn, $channelName);

            // Supprime l'utilisateur de la liste
            unset($this->usernames[$conn->resourceId]);
        }


        // Détacher la connexion fermée de la liste des clients
        $this->channels[$channelName]->detach($conn);

        // Compter tous les utilisateurs connectés
        $countAllUsers = count($this->channels[$channelName]);
        // On envoie le compteur au channel grace a la fonction
        $this->sendUserCountToChannel($channelName, $countAllUsers);

    }

    // Étape 4 : En cas d'erreur sur une connexion
    public function onError(ConnectionInterface $conn, \Exception $e) {
        $username = $this->usernames[$conn->resourceId]['username'] ?? 'N/A';
        echo "WebSocket Error - Username: $username, Error Message: {$e->getMessage()}\n";
        $conn->close();
    }

    // Fonction utilitaire pour envoyer le nombre total d'utilisateurs connectés à un canal
    private function sendUserCountToChannel($channelName, $count) {
        if (isset($this->channels[$channelName])) {
            $countMessage = ["user_count" => $count];
            $this->sendToChannel($channelName, $countMessage);
        }
    }


    // Fonction pour envoyer un message à un canal spécifique
    private function sendToChannel($channelName, $message) {
        if (isset($this->channels[$channelName])) {
            $messageData = json_encode($message);
            foreach ($this->channels[$channelName] as $client) {
                $success = $client->send($messageData);
                if (!$success) {
                    echo "Sur le channel ". $channelName ." le message n'a pas été envoyé par : ". $client->resourceId . " Le message = " . $messageData;
                } else {
                    echo "Sur le channel ". $channelName ." le message a été envoyé par : ". $client->resourceId . " Le message = " . $messageData;
                };
            }
        }
    }

    // Fonction pour envoyer un message de connexion au canal
    private function sendConnectionMessageToChannel($username, $id, $channelName, $profileImgPath, $userLatitude, $userLongitude, $messageDate, $messageHour) {
        $connectionMessage = [
            "username" => $username,
            "id" => $id,
            "channel" => $channelName,
            "profileImgPath" => $profileImgPath,
            "userLatitude" => $userLatitude,
            "userLongitude" => $userLongitude,
            "messageDate"=> $messageDate,
            "messageHour"=> $messageHour,
            "message" => "A rejoint la conversation."
        ];
        $this->sendToChannel($channelName, $connectionMessage);
    }

    // Fonction pour envoyer un message de déconnexion au canal
    private function sendDisconnectionMessageToChannel($username, $id, $channelName, $profileImgPath, $userLatitude, $userLongitude, $messageDate, $messageHour) { 
        $disconnectionMessage = [
            "username" => $username,
            "id" => $id,
            "channel" => $channelName,
            "profileImgPath" => $profileImgPath,
            "userLatitude" => $userLatitude,
            "userLongitude" => $userLongitude,
            "messageDate"=> $messageDate,
            "messageHour"=> $messageHour,
            "message" => "A quitté la conversation."
        ];
        $this->sendToChannel($channelName, $disconnectionMessage);
    }

    // Fonction pour envoyer la liste des utilisateurs connectés dans un canal à un utilisateur
    private function sendConnectedUsersDataToUserInChannel(ConnectionInterface $userConnection, $channelName) {
        $connectedUsers = $this->getAllConnectedUsersDataInChannel($channelName);
        
        $userConnection->send(json_encode(["connected_users" => $connectedUsers]));
    }

    // Fonction utilitaire pour récupérer la liste des utilisateurs connectés dans un canal
    private function getAllConnectedUsersDataInChannel($channelName) {
        $connectedUsers = [];
    
        if (isset($this->channels[$channelName])) {
            foreach ($this->channels[$channelName] as $userConnection) {
                $userData = $this->usernames[$userConnection->resourceId];
                $connectedUsers[] = $userData;
            }
        }
        return $connectedUsers;
    }
}