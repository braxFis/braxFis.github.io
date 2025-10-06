const products = [
    { id: 1, name: "Laptop", description: "A high-performance laptop", price: 999.99, image: "laptop-image-url.jpg" },
    { id: 2, name: "Smartphone", description: "Latest model smartphone", price: 799.99, image: "smartphone-image-url.jpg" },
    { id: 3, name: "Headphones", description: "Noise-cancelling headphones", price: 199.99, image: "headphones-image-url.jpg" }
];

let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

function renderProducts() {
    const productList = document.getElementById("productList");
    productList.innerHTML = "";
    products.forEach(product => {
        const li = document.createElement("li");
        li.innerHTML = `
                    <h4>${product.name}</h4>
                    <p>${product.description}</p>
                    <p>Price: $${product.price}</p>
                    <button onclick="addToWishlist(${product.id})">Add to Wishlist</button>
                `;
        productList.appendChild(li);
    });
}

function renderWishlist() {
    const wishlistEl = document.getElementById("wishlist");
    wishlistEl.innerHTML = "";
    wishlist.forEach((product, index) => {
        const li = document.createElement("li");
        li.innerHTML = `
                    <h4>${product.name}</h4>
                    <p>${product.description}</p>
                    <p>Price: $${product.price}</p>
                    ${product.image ? `<img src="${product.image}" alt="${product.name}" width="100">` : ""}
                    <button onclick="removeFromWishlist(${index})">🗑 Remove</button>
                `;
        wishlistEl.appendChild(li);
    });
    localStorage.setItem("wishlist", JSON.stringify(wishlist));
}

function addToWishlist(productId) {
    const product = products.find(p => p.id === productId);
    if (product && !wishlist.some(p => p.id === productId)) {
        wishlist.push(product);
        renderWishlist();
    }
}

function removeFromWishlist(index) {
    wishlist.splice(index, 1);
    renderWishlist();
}

document.addEventListener("DOMContentLoaded", () => {
    renderProducts();
    renderWishlist();
});