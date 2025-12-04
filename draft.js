const slider = document.querySelector('.image-slider');
let scrollAmount = 0;

function autoSlide() {
    if (!slider) return;
    scrollAmount += 1;
if (scrollAmount > slider.scrollWidth - slider.clientWidth) scrollAmount = 0;
slider.scrollTo({ left: scrollAmount, behavior: 'smooth' });
}
setInterval(autoSlide, 50);

const cartBtn = document.querySelector('.cart-btn');
const cartModal = document.getElementById('cart-modal');
const cartOverlay = document.getElementById('cart-overlay'); // ✅ added
const closeCart = document.getElementById('close-cart');
const cartItemsList = document.getElementById('cart-items');
const cartTotal = document.getElementById('cart-total');
const cartCount = document.getElementById('cart-count');
const addToCartBtns = document.querySelectorAll('.add-to-cart');


let cart = JSON.parse(localStorage.getItem('cart')) || [];

function updateCartDisplay() {
if (!cartItemsList) return;

cartItemsList.innerHTML = '';
let total = 0;

cart.forEach((item, index) => {
    total += item.price * item.qty;

    const li = document.createElement('li');
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
cartCount.textContent = cart.reduce((sum, i) => sum + i.qty, 0);
localStorage.setItem('cart', JSON.stringify(cart));
}

if (addToCartBtns) {
    addToCartBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const name = btn.dataset.name;
        const price = parseFloat(btn.dataset.price);
        const existing = cart.find(i => i.name === name);
        if (existing) existing.qty++;
        else cart.push({ name, price, qty: 1 });
        updateCartDisplay();
    });
});
}

function closeModal() {
    cartModal.classList.remove('show');
    setTimeout(() => (cartModal.style.display = 'none'), 200);
}

if (cartBtn) {
    cartBtn.addEventListener('click', () => {
    cartModal.classList.add('show');
    cartModal.style.display = 'block';
});
}

if (closeCart) closeCart.addEventListener('click', closeModal);
if (cartOverlay) cartOverlay.addEventListener('click', closeModal);

if (cartItemsList) {
cartItemsList.addEventListener('click', e => {
    const index = e.target.dataset.index;
    if (e.target.classList.contains('qty-btn')) {
    const action = e.target.dataset.action;
    if (action === 'increase') cart[index].qty++;
    if (action === 'decrease' && cart[index].qty > 1) cart[index].qty--;
    }
    if (e.target.classList.contains('remove-btn')) cart.splice(index, 1);
    updateCartDisplay();
});
}

const checkoutBtn = document.getElementById('checkout-btn');
if (checkoutBtn)
checkoutBtn.addEventListener('click', () => {
    window.location.href = 'checkout.html';
});

const orderSummary = document.getElementById('order-summary');
if (orderSummary) {
    let storedCart = JSON.parse(localStorage.getItem('cart')) || [];
    let total = 0;
    const ul = document.createElement('ul');

storedCart.forEach(item => {
    total += item.price * item.qty;
    const li = document.createElement('li');
    li.textContent = `${item.name} x${item.qty} - $${(item.price * item.qty).toFixed(2)}`;
    ul.appendChild(li);
});

orderSummary.appendChild(ul);
const totalP = document.createElement('p');
totalP.textContent = `Total: $${total.toFixed(2)}`;
orderSummary.appendChild(totalP);
}

const checkoutForm = document.getElementById('checkout-form');
if (checkoutForm) {
    checkoutForm.addEventListener('submit', e => {
    e.preventDefault();
    alert('✅ Order placed successfully!');
    localStorage.removeItem('cart');
    window.location.href = 'index.html';
});
}

const clearCartBtn = document.getElementById('clear-cart-btn');
if (clearCartBtn) {
    clearCartBtn.addEventListener('click', () => {
    if (confirm('Are you sure you want to clear your cart?')) {
        cart = [];
        localStorage.removeItem('cart');
        updateCartDisplay();
    }
});
}

const closeCheckoutBtn = document.getElementById('close-checkout');
if (closeCheckoutBtn) {
    closeCheckoutBtn.addEventListener('click', () => {
    window.location.href = 'index.html';
});
}


document.querySelectorAll(".switch-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".switch-btn").forEach(a => a.classList.remove("active"));
        btn.classList.add("active");
    });
});


// -------------------------------
updateCartDisplay();


