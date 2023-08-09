
const cartLinks = document.querySelectorAll(".js-cart");

if (cartLinks.length > 0) {
    cartLinks.forEach(function (link) {
        link.addEventListener("click", function(event) {

            event.preventDefault();
            const url = this.href;    
            console.log(url)
            const count = document.getElementById("count");
            const clickedLink = this;

            axios.post(url).then(function (response) {
                clickedLink.classList.replace("bg-primary", "bg-transparent");
                clickedLink.classList.replace("text-white", "text-primary");
                clickedLink.classList.add("border-primary");
                clickedLink.classList.add("border");
                count.textContent = response.data.count;
                clickedLink.textContent = response.data.message;
            });
        });
    });
} else {
    console.log("No elements with the class .js-cart were found.");
}

