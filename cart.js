let cart = JSON.parse(localStorage.getItem("cart")) || [];

function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}

function updateCartCount() {
    const count = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById("cart-count");
    if (badge) badge.textContent = count;
}

// ------------------------------------------
// ADD TO CART
// ------------------------------------------
function addToCart(name, price, image) {
    let existing = cart.find(item => item.name === name);

    if (existing) {
        existing.qty++;
    } else {
        cart.push({ name, price, image, qty: 1 });
    }

    saveCart();
    updateCartCount();
}

// ------------------------------------------
// DOM READY
// ------------------------------------------
document.addEventListener("DOMContentLoaded", () => {

    // ADD-TO-CART BUTTON HANDLERS
    document.querySelectorAll(".add-to-cart").forEach(btn => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".food-card");

            const name  = card.dataset.name;
            const price = parseFloat(card.dataset.price);
            const image = card.dataset.image;

            addToCart(name, price, image);
        });
    });

    // ------------------------------------------
    // CART OPEN/CLOSE LOGIC
    // ------------------------------------------
    const cartBtn        = document.querySelector(".cart-btn");
    const cartModal      = document.getElementById("cart-modal");
    const cartOverlay    = document.getElementById("cart-overlay");
    const cartItemsList  = document.getElementById("cart-items");
    const cartTotal      = document.getElementById("cart-total");
    const closeCart      = document.getElementById("close-cart");
    const clearCartBtn   = document.getElementById("clear-cart-btn");

    function updateCartDisplay() {
        if (!cartItemsList) return;

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

    
cartBtn.addEventListener("click", () => {
    cartModal.classList.add("show");
    updateCartDisplay();
});

closeCart.addEventListener("click", () => {
    cartModal.classList.remove("show");
});

cartOverlay.addEventListener("click", () => {
    cartModal.classList.remove("show");
});


    // CLEAR CART
    if (clearCartBtn) {
        clearCartBtn.addEventListener("click", () => {
            cart = [];
            saveCart();
            updateCartDisplay();
        });
    }

    // QTY + REMOVE
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

    // ------------------------------------------
// CHECKOUT
// ------------------------------------------
const checkoutBtn = document.getElementById("checkout-btn");
if (checkoutBtn) {
    checkoutBtn.addEventListener("click", () => {

        if (cart.length === 0) {
            alert("Your cart is empty.");
            return;
        }

        const total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);

        fetch("checkout.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                cart: cart,
                total: total
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                localStorage.removeItem("cart"); // Clear cart
                window.location.href = "order_success.php?order_id=" + data.order_id;
            } else {
                alert("Checkout failed: " + data.message);
            }
        });
    });
}


    updateCartCount();
});