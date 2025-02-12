const sendMessageInput = document.getElementById('sendMessage');

  sendMessageInput.addEventListener('blur', () => {
    // Faites défiler la page vers le haut
    window.scrollTo(0, 0);
  });