// assets/controllers/custom-autocomplete_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    initialize() {
        this._onPreConnect = this._onPreConnect.bind(this);
        this._onConnect = this._onConnect.bind(this);
    }

    connect() {
        this.element.addEventListener('autocomplete:pre-connect', this._onPreConnect);
        this.element.addEventListener('autocomplete:connect', this._onConnect);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side-effects
        this.element.removeEventListener('autocomplete:pre-connect', this._onConnect);
        this.element.removeEventListener('autocomplete:connect', this._onPreConnect);
    }

    _onPreConnect(event) {
        // TomSelect has not been initialized - options can be changed
        console.log(event.detail.options); // Options that will be used to initialize TomSelect        
        event.detail.options.onItemAdd = (value) => {
          console.log(value);   
          fetch(value);
        };
      }

    _onConnect(event) {
        // TomSelect has just been intialized and you can access details from the event
        console.log(event.detail.tomSelect); // TomSelect instance
        console.log(event.detail.options); // Options used to initialize TomSelect
    }
}

async function fetch(id) {
  const url = "/ajax/" + id;
  const urlActuelle = window.location.origin;
  const urlComplete = urlActuelle + url;
  await axios.get(urlComplete).then(function(resp){
      addRatingElement(id, resp.data.aicore)
  })
}

function addRatingElement(id, aicore) {
  // Création du conteneur div
  let ratingDiv = document.createElement('div');
  ratingDiv.id = 'rating-' + id;
  ratingDiv.className = 'relative mb-3';

  // Création du nouveau div à ajouter
  let newTextDiv = document.createElement('div');
  newTextDiv.setAttribute('data-te-chip-init', '');
  newTextDiv.setAttribute('data-te-ripple-init', '');
  newTextDiv.className = '[word-wrap: break-word] my-[5px] mr-4 flex h-[32px] cursor-pointer items-center justify-between rounded-[16px] bg-[#eceff1] px-[12px] py-0 text-[13px] font-normal normal-case leading-loose text-[#4f4f4f] shadow-none transition-[opacity] duration-300 ease-linear hover:!shadow-none active:bg-[#cacfd1] dark:bg-neutral-600 dark:text-neutral-200';
  newTextDiv.setAttribute('data-te-close', 'true');
  newTextDiv.textContent = aicore.name;

  // Création du div flex
  let flexDiv = document.createElement('div');
  flexDiv.className = 'flex items-center';

  // Ajout du nouveau div au div flex
  flexDiv.appendChild(newTextDiv);

  let starsDiv = document.createElement('div');
  starsDiv.className = 'flex items-center';

  // Ajout des boutons d'étoiles
  for(let i = 1; i <= 5; i++) {
      let starButton = document.createElement('a');
      starButton.href = '/ajax/' + aicore.slug + '/' + i;
      starButton.className = 'js-stars';

      let svgElement = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      svgElement.setAttribute('class', 'h-5 w-5 text-gray-300');
      svgElement.setAttribute('viewBox', '0 0 20 20');
      svgElement.setAttribute('fill', 'currentColor');

      let pathElement = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      pathElement.setAttribute('d', 'M10 1L12.39 6.75H19.78L14.76 10.25L17.17 16L10 12.75L2.83 16L5.24 10.25L0.22 6.75H7.61L10 1Z');

      svgElement.appendChild(pathElement);
      starButton.appendChild(svgElement);
      starsDiv.appendChild(starButton);
  }

  flexDiv.appendChild(starsDiv);

  ratingDiv.appendChild(flexDiv);
  const rating = document.getElementById("rating");
  rating.appendChild(ratingDiv)
  var elements = document.querySelectorAll(".js-stars");
  if (elements.length > 0) {
      elements.forEach(function (link) {
        link.addEventListener("click", function (event) {
          event.preventDefault();
          const url = this.href;
          const icon = this.querySelector("svg");
          var siblings = Array.from(this.parentNode.children);
      
          var currentIndex = siblings.indexOf(this);
          var nextSiblings = siblings.slice(currentIndex + 1);
          var previousSiblings = siblings.slice(0, currentIndex);
          console.log(nextSiblings,currentIndex,previousSiblings)
          axios.post(url).then(function (response) {
            if (icon.classList.contains("text-gray-300")) {
              icon.classList.replace("text-gray-300", "text-yellow-500");
            }
            nextSiblings.forEach(function (nextSibling) {
              nextSibling
                .querySelector("svg")
                .classList.replace("text-yellow-500", "text-gray-300");
            });
            previousSiblings.forEach(function (previousSibling) {
              previousSibling
                .querySelector("svg")
                .classList.replace("text-gray-300", "text-yellow-500");
            });
          });
        });
      });
  } else {
      console.log('Aucun élément avec la classe .js-stars n\'a été trouvé');
  }
  // Ajoute le nouvel élément à la page
}

