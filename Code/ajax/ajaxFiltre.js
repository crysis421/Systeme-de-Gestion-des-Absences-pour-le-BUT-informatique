document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('formFiltre');
    const conteneur = document.getElementById('conteneurAbsences');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('../../Model/ajaxFiltre.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(html => {
                conteneur.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    });
});