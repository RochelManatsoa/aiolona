let offset = 12; // Commence à 10 car les 10 premiers sont déjà chargés
window.addEventListener('scroll', function () {
    console.log('scroll', window.scrollY + window.innerHeight, document.documentElement.scrollHeight);
    if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight) {
        axios.get(`/all/expert/ajax?offset=${offset}`)
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
