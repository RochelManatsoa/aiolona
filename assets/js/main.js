/** @format */

const dropdownButton = document.getElementById("dropdown-menu-button");
const dropdownMenu = document.getElementById("dropdown-menu");

dropdownButton.addEventListener("click", function () {
  const expanded =
    dropdownButton.getAttribute("aria-expanded") === "true" || false;
  dropdownButton.setAttribute("aria-expanded", !expanded);
  console.log(dropdownMenu);
  dropdownMenu.classList.toggle("hidden");
});
