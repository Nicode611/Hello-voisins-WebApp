# Hello Voisins

## 📌 Introduction

Hello Voisins is a web application designed to facilitate communication between neighbors using geolocation and real-time messaging. The idea came from a personal need: being able to easily interact with neighbors without having to knock on every door.

The application allows users to:
	•	Find nearby neighbors
	•	Engage in private or group discussions
	•	View neighbors on an interactive map
	•	Control their visibility to ensure privacy

The project is live at hello-voisins.com.

## 🚀 Features
	•	🔍 Geolocation: Displays nearby neighbors on a Google Maps-based interactive map
	•	💬 Real-time messaging: Instant chat powered by WebSockets (Ratchet)
	•	🤝 Contact management: Add, accept, and remove contacts
	•	👥 Group chats: Create discussion groups for buildings and neighborhoods
	•	🔒 Data security: Protection against SQL injection and XSS attacks
	•	🌍 Responsive UI: Optimized for both mobile and desktop

## 🛠 Technologies Used
	•	Frontend:
	•	HTML, CSS (no framework)
	•	JavaScript (vanilla) + jQuery for AJAX
	•	Google Maps API for geolocation
	•	Backend:
	•	PHP (server and request management)
	•	Ratchet (WebSockets for real-time chat)
	•	MySQL (database)
	•	Infrastructure:
	•	XAMPP (local development)
	•	DigitalOcean (VPS deployment)
	•	CertBot (SSL security)

## 📦 Installation & Setup
	1.	Clone the repository:

git clone https://github.com/Nicode611/Hello-voisins.git


	2.	Navigate to the project directory:

cd Hello-voisins


	3.	Install PHP dependencies (via Composer):

composer install


	4.	Set up the MySQL database and update the credentials in config/db.php.
	5.	Start the WebSocket server:

php config/server.php


	6.	Run a local server for the frontend (XAMPP, Apache, etc.).
	7.	Open the application in your browser:

http://localhost



## 📝 Usage
	•	Sign up and log into the application.
	•	Find nearby neighbors and start chatting.
	•	Create discussion groups for your building or neighborhood.
	•	Manage your visibility on the map.

## 🎯 Future Improvements
	•	🔔 Browser notifications for new messages
	•	📩 Password recovery via email
	•	📄 Detailed user profiles
	•	📲 Mobile app version

## 🤝 Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request.

## 📧 Contact

For any questions or suggestions, feel free to reach out at nicode611@gmail.com.
