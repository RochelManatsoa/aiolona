/** @format */

// assets/controllers/custom-autocomplete_controller.js
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  initialize() {
    this._onPreConnect = this._onPreConnect.bind(this);
    this._onConnect = this._onConnect.bind(this);
  }

  connect() {
    this.element.addEventListener(
      "autocomplete:pre-connect",
      this._onPreConnect
    );
    this.element.addEventListener("autocomplete:connect", this._onConnect);
  }

  disconnect() {
    // You should always remove listeners when the controller is disconnected to avoid side-effects
    this.element.removeEventListener(
      "autocomplete:pre-connect",
      this._onConnect
    );
    this.element.removeEventListener(
      "autocomplete:connect",
      this._onPreConnect
    );
  }

  _onPreConnect(event) {
    // TomSelect has not been initialized - options can be changed
    console.log(event.detail.options); // Options that will be used to initialize TomSelect
    const popup = document.getElementById("buttonModal");
    event.detail.options.onItemAdd = (value) => {
      console.log(value)
      fetchData(value);
      popup.click();
    };
  }
  
  _onConnect(event) {
    // TomSelect has just been intialized and you can access details from the event
    console.log(event.detail.tomSelect); // TomSelect instance
    console.log(event.detail.options); // Options used to initialize TomSelect
  }
}

async function fetchData(id) {
  console.log(id)
    const title = document.getElementById("exampleModalScrollableLabel");
    const stars = document.querySelectorAll(".js-stars");
    const url = "/ajax-skill/" + id;
    const urlActuelle = window.location.origin;
    const urlComplete = urlActuelle + url;
    await axios.get(urlComplete).then(function(resp){
        console.log(resp.data)
        title.textContent = resp.data.skill.name
        stars.forEach((link, index) =>{
            link.href = "/ajax-skill/" + resp.data.skill.slug + "/" + (index  + 1)
        })
    })
}