let offset = 12; // Commence à 10 car les 10 premiers sont déjà chargés
window.addEventListener('scroll', function () {
    const threshold = 2;
    const position = window.scrollY + window.innerHeight;
    const height = document.documentElement.scrollHeight;
    if (position >= height - threshold) {
        axios.get(`/store/ajax?offset=${offset}`)
            .then(response => {
                if (response.data) {
                    const produitItemDiv = document.querySelector('#produit-container .produit-item');
                    if (produitItemDiv) {
                        produitItemDiv.innerHTML += response.data.html;
                    } 
                    offset += 12; // Incrémente pour le prochain lot
                }
            })
            .catch(error => {
                console.error('Une erreur est survenue:', error);
            });
    }
});
