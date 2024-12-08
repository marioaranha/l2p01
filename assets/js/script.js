document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const name = document.getElementById('Nom et Prenom').value;
    const phone = document.getElementById('Phone Portable').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    if (name && phone && email && message) {
        alert(`Merci, ${name}! Nous avons reçu votre message.`);
        document.getElementById('contact-form').reset();
    } else {
        alert('Veuillez remplir tous les champs.');
    }
});

