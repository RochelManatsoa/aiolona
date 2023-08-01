/** @format */

document.querySelectorAll(".js-stars").forEach(function (link) {
  link.addEventListener(
    "click",
    function (e) {
      e.preventDefault();
      const url = this.getAttribute('href');
      
      const icon = this.querySelector("svg");
      const paragraph = document.getElementById("desc");
      var siblings = Array.from(this.parentNode.children);

      var currentIndex = siblings.indexOf(this);
      var nextSiblings = siblings.slice(currentIndex + 1);
      var previousSiblings = siblings.slice(0, currentIndex);

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
        paragraph.textContent = response.data.desc;
      });
    },
    true
  );
});
