let button = document.querySelector('.testbtn');
let liste = document.querySelector('.liste');
const apiUrl = 'http://212.227.27.90/contacts';

// Définir l'URL de l'API
button.addEventListener('click', () => {
     // Utiliser Fetch pour faire une requête GET
fetch(apiUrl)
.then(response => {
  if (!response.ok) {
    // Si la réponse n'est pas 2xx, jette une erreur
    throw new Error('Network response was not ok');
  }
  return response.json(); // Transforme la réponse en JSON
})
.then(data => {
  // Traiter les données ici
  console.log(data);
  // Par exemple, afficher les contacts dans la console ou les afficher dans votre HTML
})
.catch(error => {
  // Gérer les erreurs ici
  console.error('There has been a problem with your fetch operation:', error);
});
});





function replace() {

}


//$.ajax({
    //         url: "../scripts/test/get.php", // Remplacez par l'URL de votre API
    //         type: 'GET',
    //         dataType: 'json', // Type de données attendu en réponse
    //         success: function(data) {
    //           // Cette fonction est appelée en cas de succès
    //           console.log(data); // Affiche la réponse de l'API dans la console
    //           // Vous pouvez ici manipuler les données reçues
    //         },
    //         error: function(xhr, status, error) {
    //           // Cette fonction est appelée en cas d'erreur
    //           console.error("Erreur lors de la requête : ", status, error);
    //           // Gestion de l'erreur
    //         }
    //       });