// ---------------- IMAGE SLIDER ----------------
const slider = document.querySelector('.image-slider');
let scrollAmount = 0;

function autoSlide() {
    if (!slider) return;
    scrollAmount += 1;
    if (scrollAmount > slider.scrollWidth - slider.clientWidth) scrollAmount = 0;
    slider.scrollTo({ left: scrollAmount, behavior: 'smooth' });
}
setInterval(autoSlide, 50);


// ---------------- CART SYSTEM ----------------
let cart = JSON.parse(localStorage.getItem("cart")) || [];

function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}

function updateCartCount() {
    const badge = document.getElementById("cart-count");
    if (badge) {
        badge.textContent = cart.reduce((sum, item) => sum + item.qty, 0);
    }
}

function updateCartDisplay() {
    const cartItemsList = document.getElementById("cart-items");
    const cartTotal = document.getElementById("cart-total");

    if (!cartItemsList || !cartTotal) return;

    cartItemsList.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
        total += item.price * item.qty;

        const li = document.createElement("li");
        li.innerHTML = `
            <span>${item.name}</span>
            <div>
                <button class="qty-btn" data-index="${index}" data-action="decrease">-</button>
                <span>${item.qty}</span>
                <button class="qty-btn" data-index="${index}" data-action="increase">+</button>
                <button class="remove-btn" data-index="${index}">🗑</button>
            </div>
        `;
        cartItemsList.appendChild(li);
    });

    cartTotal.textContent = `Total: $${total.toFixed(2)}`;
    saveCart();
    updateCartCount();
}

function addToCart(name, price, image) {
    let existing = cart.find(item => item.name === name);

    if (existing) {
        existing.qty++;
    } else {
        cart.push({ name, price, image, qty: 1 });
    }

    updateCartDisplay();
}


// ---------------- DOM LOADED ----------------
document.addEventListener("DOMContentLoaded", () => {

    // ADD TO CART BUTTONS
    document.querySelectorAll(".add-to-cart").forEach(btn => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".food-card") || btn.closest(".card");
            if (!card) return;

            const name = card.dataset.name;
            const price = parseFloat(card.dataset.price);
            const image = card.dataset.image;

            addToCart(name, price, image);
        });
    });

    // CART ELEMENTS
    const cartBtn = document.querySelector(".cart-btn");
    const cartModal = document.getElementById("cart-modal");
    const cartOverlay = document.getElementById("cart-overlay");
    const closeCart = document.getElementById("close-cart");
    const clearCartBtn = document.getElementById("clear-cart-btn");
    const cartItemsList = document.getElementById("cart-items");

    // OPEN CART
    if (cartBtn && cartModal) {
        cartBtn.addEventListener("click", () => {
            cartModal.style.display = "block";
            updateCartDisplay();
        });
    }

    // CLOSE CART
    if (closeCart) {
        closeCart.addEventListener("click", () => {
            cartModal.style.display = "none";
        });
    }

    // CLOSE CART BY OVERLAY CLICK
    if (cartOverlay) {
        cartOverlay.addEventListener("click", () => {
            cartModal.style.display = "none";
        });
    }

    // CART ITEM EVENTS
    if (cartItemsList) {
        cartItemsList.addEventListener("click", (e) => {
            const index = e.target.dataset.index;

            if (e.target.classList.contains("qty-btn")) {
                const action = e.target.dataset.action;
                if (action === "increase") cart[index].qty++;
                if (action === "decrease" && cart[index].qty > 1) cart[index].qty--;
            }

            if (e.target.classList.contains("remove-btn")) {
                cart.splice(index, 1);
            }

            updateCartDisplay();
        });
    }

    // CLEAR CART
    if (clearCartBtn) {
        clearCartBtn.addEventListener("click", () => {
            if (confirm("Clear your cart?")) {
                cart = [];
                saveCart();
                updateCartDisplay();
            }
        });
    }

    updateCartDisplay();
    <script src="cart.js"></script>
});
