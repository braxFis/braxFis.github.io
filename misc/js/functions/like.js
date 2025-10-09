const likeBtn = document.getElementById("likeBtn");
const dislikeBtn = document.getElementById("dislikeBtn");
const message = document.getElementById("message");

let liked = false;
let disliked = false;

likeBtn.addEventListener("click", () => {
    liked = true;
    disliked = false;
    updateUI();
});

dislikeBtn.addEventListener("click", () => {
    liked = false;
    disliked = true;
    updateUI();
});

function updateUI() {
    likeBtn.classList.toggle("liked", liked);
    dislikeBtn.classList.toggle("disliked", disliked);

    if (liked) {
        message.textContent = "You liked this content!";
    } else if (disliked) {
        message.textContent = "You disliked this content!";
    } else {
        message.textContent = "";
    }
}