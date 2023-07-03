
const dropdownButton = document.getElementById('dropdown-menu-button');
const dropdownMenu = document.getElementById('dropdown-menu');

dropdownButton.addEventListener('click', function () {
  const expanded = dropdownButton.getAttribute('aria-expanded') === 'true' || false;
  dropdownButton.setAttribute('aria-expanded', !expanded);
  console.log(dropdownMenu)
  dropdownMenu.classList.toggle('hidden');
});

function initFormStep(form) {

  console.log("eto")
  var form = form.show();

  form.steps({
    showBackButton: true,
    onChange: function(currentIndex, newIndex, stepDirection){
      // Needed in some cases if the user went back (clean up)
      if (currentIndex === newIndex) {
        return true;
      }
      form.validate().settings.ignore = ":disabled,:hidden";
      return form.valid();
    },
    onFinish: function (event, currentIndex) {
      form.submit();
    }
  }).validate({
    errorPlacement: function errorPlacement(error, element) { 
      error.addClass('inline-flex items-center px-3 py-3 rounded-full text-xs leading-none bg-red-500 text-white'); 
      var elementId 
      var elementIndex 
      const parentElement = element.parent().parent().parent();
      element.each(function(index, element) {
        // Faites quelque chose avec chaque élément
        elementId = element.id
        elementIndex = index
        console.log(elementId, element)
      });
      if(elementId === "identity_account_1" || elementId === "identity_sectors_1" ){
        parentElement.after(error);
      }else{
        element.before(error)
      }
    },
    rules: {
      "identity[account]": {
          required: true
      },
      "identity[sectors][]": {
          required: true
      },
      // "identity[user][email]": {
      //     required: true
      // },
      // "identity[user][password]": {
      //     required: true
      // },
      // "identity[country]": {
      //     required: true
      // },
    },
    messages: {
      "identity[account]": {
          required: 'Vous devez séléctionner',
      },
      "identity[sectors][]": {
          required: 'Vous devez séléctionner au moins un sécteur',
      },
      // "identity[user][email]": {
      //     required: 'Veuillez saisir votre adresse éléctronique',
      // },
      // "identity[user][password]": {
      //     required: 'Veuillez saisir votre votre mot de passe',
      // },
      // "identity[country]": {
      //     required: 'Vous devez séléctionner votre pays',
      // },
    }
  })
}
// JavaScript
var btnGoogle = document.getElementById('btnGoogle');

btnGoogle.addEventListener('click', function() {
  // URL de l'authentification avec Google
  var authUrl = 'https://www.example.com/auth/google';
  
  // Ouvrir une nouvelle fenêtre avec l'URL d'authentification
  window.open(authUrl, '_blank', 'width=800,height=600');
});

