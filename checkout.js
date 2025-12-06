document.addEventListener("DOMContentLoaded", async () => {

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const orderSummary = document.getElementById("order-summary");
    const totalEl = document.getElementById("checkout-total");

    let saved = {};
    try {
        const r = await fetch("load_address.php");
        saved = await r.json();
    } catch (e) {
        console.log("Address fetch failed");
    }

    document.getElementById("fullname").value = saved.fullname || "";
    document.getElementById("address").value = saved.address || "";
    document.getElementById("city").value = saved.city || "";
    document.getElementById("phone").value = saved.phone || "";
    document.getElementById("notes").value = saved.notes || "";

    function renderCart() {
        orderSummary.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            total += item.price * item.qty;

            const div = document.createElement("div");
            div.className = "checkout-item";

            div.innerHTML = `
                <span>${item.name} × ${item.qty}</span>
                <div class="checkout-item-controls">
                    <button onclick="updateQty(${index}, -1)">−</button>
                    <button onclick="updateQty(${index}, 1)">+</button>
                    <button onclick="removeItem(${index})">🗑</button>
                </div>
            `;

            orderSummary.appendChild(div);
        });

        totalEl.textContent = "Total: $" + total.toFixed(2);
        localStorage.setItem("cart", JSON.stringify(cart));
    }

    window.updateQty = function(index, change) {
        if (cart[index].qty + change <= 0) return;
        cart[index].qty += change;
        renderCart();
    };

    window.removeItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    };

    renderCart();

    document.getElementById("checkout-form").addEventListener("submit", async e => {
        e.preventDefault();

        if (!cart.length) return alert("Cart is empty!");

        const orderData = {
            cart,
            fullname: fullname.value,
            address: address.value,
            city: city.value,
            phone: phone.value,
            notes: notes.value,
            payment: document.querySelector("input[name='payment']:checked").value
        };

        const response = await fetch("place_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(orderData)
        });

        const result = await response.text();
        alert(result);

        localStorage.removeItem("cart");
        window.location.href = "orders.php";
    });
});
