
const slider = document.querySelector(".image-slider");
let scrollAmount = 0;

function autoSlide() {
    if (!slider) return;
    scrollAmount += 1;

    if (scrollAmount > slider.scrollWidth - slider.clientWidth) {
        scrollAmount = 0;
    }

    slider.scrollTo({ left: scrollAmount, behavior: "smooth" });
}
setInterval(autoSlide, 40);


let cart = JSON.parse(localStorage.getItem("cart")) || [];

function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}

function updateCartCount() {
    const badge = document.getElementById("cart-count");
    if (!badge) return;

    badge.textContent = cart.reduce((sum, item) => sum + item.qty, 0);
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
            <div class="item-controls">
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
    const existing = cart.find(item => item.name === name);

    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ name, price, image, qty: 1 });
    }

    updateCartDisplay();
}



document.addEventListener("DOMContentLoaded", () => {
    const addButtons = document.querySelectorAll(".add-to-cart");

    addButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".food-card") || btn.closest(".card");
            if (!card) return;

            addToCart(
                card.dataset.name,
                parseFloat(card.dataset.price),
                card.dataset.image
            );
        });
    });

    const cartBtn       = document.querySelector(".cart-btn");
    const cartModal     = document.getElementById("cart-modal");
    const cartOverlay   = document.getElementById("cart-overlay");
    const closeCart     = document.getElementById("close-cart");
    const clearCartBtn  = document.getElementById("clear-cart-btn");
    const checkoutBtn   = document.getElementById("checkout-btn");
    const cartItemsList = document.getElementById("cart-items");


cartBtn.addEventListener("click", () => {
    cartModal.classList.add("show");
    cartModal.style.display = "block";
    updateCartDisplay();
});

function closeCartModal() {
    cartModal.classList.remove("show");
    setTimeout(() => {
        cartModal.style.display = "none";
    }, 300); 
}

closeCart.addEventListener("click", closeCartModal);
cartOverlay.addEventListener("click", closeCartModal);

    if (cartItemsList) {
        cartItemsList.addEventListener("click", (e) => {
            const index = e.target.dataset.index;
            if (index === undefined) return;

            if (e.target.classList.contains("qty-btn")) {
                const action = e.target.dataset.action;

                if (action === "increase") {
                    cart[index].qty++;
                } else if (action === "decrease" && cart[index].qty > 1) {
                    cart[index].qty--;
                }
            }

            if (e.target.classList.contains("remove-btn")) {
                cart.splice(index, 1);
            }

            updateCartDisplay();
        });
    }

    if (clearCartBtn) {
        clearCartBtn.addEventListener("click", () => {
            if (confirm("Clear your entire cart?")) {
                cart = [];
                saveCart();
                updateCartDisplay();
            }
        });
    }

    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", () => {
            if (cart.length === 0) {
                alert("Your cart is empty!");
                return;
            }

            saveCart();
            window.location.href = "checkout.php";
        });
    }

    updateCartDisplay();
});
