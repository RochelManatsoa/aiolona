/** @format */

document.addEventListener("DOMContentLoaded", function () {
  const messageTable = document.getElementById("messageTable");
  const messageContent = document.getElementById("messageContent");
  const messageSentTable = document.getElementById("messageSentTable");
  const messageSentContent = document.getElementById("messageSentContent");

  messageTable.addEventListener("click", function (event) {
    let target = event.target;

    // Traverse les parents jusqu'à trouver une ligne de tableau (tr) ou retourner null
    while (target != this) {
      if (target.tagName == "TR") break;
      target = target.parentNode;
    }

    if (target == this) return; // si le clic était en dehors d'une ligne, ne faites rien

    // Obtenez l'identifiant du message de l'attribut data-message-id
    const messageId = target.getAttribute("data-message-id");

    // Effectuez un appel AJAX avec axios pour récupérer le contenu du message
    axios
      .get("/ajax/message/show/" + messageId)
      .then((response) => {
        console.log(response)
        // Cachez le tableau et affichez le contenu du message
        let FileName = "https://www.jea.com/cdn/images/avatar-gray.png";
        if (response.data.content.sender.identity.fileName !== null) {
          FileName =
            "/uploads/experts/" +
            response.data.content.sender.identity.fileName;
        }
        messageTable.style.display = "none";
        document.getElementById("messageTitle").textContent =
          response.data.content.title;
        document.getElementById("senderAvatar").src = FileName;
        document.getElementById("senderName").textContent =
          response.data.content.sender.firstName +
          " " +
          response.data.content.sender.lastName;
        document.getElementById("senderEmail").textContent =
          response.data.content.sender.email;
        document.getElementById("sendDate").textContent = formatDate(
          response.data.content.createdAt
        );
        document.getElementById("messageBody").textContent =
          response.data.content.message;
        messageContent.style.display = "block";
      })
      .catch((error) => {
        console.error("Une erreur est survenue:", error);
      });
  });


  messageSentTable.addEventListener("click", function (event) {
    let target = event.target;

    // Traverse les parents jusqu'à trouver une ligne de tableau (tr) ou retourner null
    while (target != this) {
      if (target.tagName == "TR") break;
      target = target.parentNode;
    }

    if (target == this) return; // si le clic était en dehors d'une ligne, ne faites rien

    // Obtenez l'identifiant du message de l'attribut data-message-id
    const messageId = target.getAttribute("data-message-id");

    // Effectuez un appel AJAX avec axios pour récupérer le contenu du message
    axios
      .get("/ajax/message/show/" + messageId)
      .then((response) => {
        console.log(response)
        // Cachez le tableau et affichez le contenu du message
        let FileName = "https://www.jea.com/cdn/images/avatar-gray.png";
        if (response.data.content.sender.identity.fileName !== null) {
          FileName =
            "/uploads/experts/" +
            response.data.content.sender.identity.fileName;
        }
        messageSentTable.style.display = "none";
        document.getElementById("messageSentTitle").textContent =
          response.data.content.title;
        document.getElementById("senderSentAvatar").src = FileName;
        document.getElementById("senderSentName").textContent =
          response.data.content.sender.firstName +
          " " +
          response.data.content.sender.lastName;
        document.getElementById("senderSentEmail").textContent =
          response.data.content.sender.email;
        document.getElementById("sendSentDate").textContent = formatDate(
          response.data.content.createdAt
        );
        document.getElementById("messageSentBody").textContent =
          response.data.content.message;
        messageSentContent.style.display = "block";
      })
      .catch((error) => {
        console.error("Une erreur est survenue:", error);
      });
  });

});

function formatDate(dateString) {
  const date = new Date(dateString);
  const day = String(date.getDate()).padStart(2, "0");

  const months = [
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre",
  ];

  const month = months[date.getMonth()];
  const year = date.getFullYear();

  const hours = String(date.getHours()).padStart(2, "0");
  const minutes = String(date.getMinutes()).padStart(2, "0");

  return `${day} ${month} ${year}, ${hours}:${minutes}`;
}

function showTable(content, table) {
  // Cachez le contenu du message
  document.getElementById(content).style.display = "none";

  // Montrez la liste des messages
  document.getElementById(table).style.display = "block";
}

// Pour l'onglet "Reçus"
document
  .getElementById("pills-profile-tab")
  .addEventListener("click", function () {
    showTable("messageContent","messageTable");
  });
document
  .getElementById("pills-unlocked-tab")
  .addEventListener("click", function () {
    showTable("messageSentContent","messageSentTable");
  });
